<?php

namespace App\Models;

use App\Helpers\SignoffStateHelper;
use App\Scopes\SignoffScope;
use App\User;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Signoff extends Model
{
    use SoftDeletes;
    use HasFactory;

    const EXTRA_WIDE_MODELS = [
        Promo::class,
        PricingAdjustment::class,
        MarketingAgreement::class,
        InventoryRemoval::class,
    ];

    protected $guarded = ['id'];

    protected $with = ['signoffConfigSteps'];

    protected $casts = ['submitted_at' => 'datetime'];

    public static function boot()
    {
        parent::boot();
        static::addGlobalScope(new SignoffScope);
    }

    public static function startNewSignoff($initial, $proposed = null, $user = null)
    {
        $config = SignoffConfig::where('model', get_class($initial))->first();
        if (! $config) {
            throw new Exception("Could not find SignoffConfig for class '" . get_class($initial) . "'");
        }

        $signoff = new Signoff;
        $signoff->new_submission = is_null($proposed);
        $signoff->user()->associate($user ?? auth()->user());
        $signoff->initial()->associate($initial);
        $signoff->proposed()->associate($proposed ?? $initial);
        $signoff->signoffConfig()->associate($config);
        $signoff->step = 0;
        $signoff->save();

        $signoff->gotoNextStep();

        return $signoff;
    }

    public function getSavedAttribute()
    {
        return $this->state == SignoffStateHelper::IN_PROGRESS;
    }

    public function getPendingAttribute()
    {
        return $this->state == SignoffStateHelper::PENDING;
    }

    public function getApprovedAttribute()
    {
        return $this->state == SignoffStateHelper::APPROVED;
    }

    public function getRejectedAttribute()
    {
        return $this->state == SignoffStateHelper::REJECTED;
    }

    public function getArchivedAttribute()
    {
        return $this->state == SignoffStateHelper::ARCHIVED;
    }

    public function getStepViewAttribute()
    {
        return $this->getStepConfigAttribute()->form_view;
    }

    public function getStepConfigAttribute()
    {
        return $this->signoffConfigSteps->where('step', max(1, $this->step))->first();
    }

    public function setStepAttribute($value)
    {
        $this->attributes['step'] = max(0, min($this->signoffConfigSteps->count() + 1, $value));
    }

    public function getStepCompleteAttribute()
    {
        return $this->responsesForStep()->count() >= $this->stepConfig->signoffs_required;
    }

    public function getCurrentStepNameAttribute()
    {
        if ($this->approved) {
            return 'Completed';
        } elseif ($this->rejected) {
            return 'Rejected';
        } elseif ($this->saved) {
            return 'Saved';
        }

        return $this->stepConfig->name ?: $this->step;
    }

    public function getExtraWideAttribute()
    {
        return in_array($this->initial_type, $this::EXTRA_WIDE_MODELS);
    }

    public function getState()
    {
        if ($this->pending) {
            return 'Pending';
        }
        if ($this->approved) {
            return 'Approved';
        }
        if ($this->rejected) {
            return 'Rejected';
        }

        return 'Saved ';
    }

    public function gotoNextStep()
    {
        $this->proposed->refresh(); // Ensure model is up to date

        $this->step = $this->proposed->nextStep($this->step, $this);

        if ($this->step > $this->signoffConfigSteps->count()) {
            $this->state = SignoffStateHelper::APPROVED;
            $this->save();

            $this->initial->signoffApproved($this);
        } else {
            $this->save();
        }

        return $this->step;
    }

    public function gotoPrevStep()
    {
        // Create step variable to hold the previous step value
        // otherwise `$this->step` always equals the inital value even after assignment
        $step = $this->proposed->prevStep($this->step, $this);

        // Set the step property
        $this->step = $step;

        if ($step == 0) {
            $this->state = SignoffStateHelper::REJECTED;
            $this->save();

            $this->initial->signoffRejected($this);
        } else {
            $this->save();
        }

        //  Archive current (and next) step's responses
        $this->responsesForCurrentAndNextSteps()->update(['archived' => true]);

        return $this->step;
    }

    public function getUserResponse()
    {
        $response = SignoffResponse::where('signoff_id', $this->id)
            ->where([
                'user_id' => auth()->id(),
                'step' => $this->step,
                'archived' => 0,
            ])->first();

        return $response ? $response->approved : null;
    }

    public function getUsers()
    {
        return User::whereHas('roles.abilities.signoffConfigSteps.signoffConfig.signoffs', function ($query) {
            $query
                ->where('id', $this->id)
                ->whereHasMorph('initial', '*', function ($query) {
                    $query->allStates()->whereColumn('signoffs.step', 'signoff_config_steps.step')->signoffFilter();
                });
        })->get();
    }

    public function getSummaryArray($signoff = null)
    {
        $data = ['Submitted By' => $this->user->name];

        return array_merge($data, $this->proposed->getSummaryArray($this));
    }

    public function unsubmit()
    {
        if ($this->pending && $this->proposed->canUnsubmitPending) {
            $this->step = 1;
            $this->submitted_at = null;
            $this->state = SignoffStateHelper::IN_PROGRESS;
            $this->save();

            $this->proposed::disableRecording();
            $this->proposed->{$this->proposed->stateField()} = SignoffStateHelper::IN_PROGRESS;
            $this->proposed->save();
            $this->proposed::enableRecording();

            flash('Successfully unsubmitted submission. You can find it in your Drafts.', 'success');
        } elseif ($this->approved && $this->proposed->canUnsubmitApproved) {
            $oldSignoffs = Signoff::where('initial_id', $this->initial->id)->get();
            foreach ($oldSignoffs as $oldSignoff) {
                $oldSignoff->state = SignoffStateHelper::UNSUBMITTED;
                $oldSignoff->save();
            }

            $this->initial::disableRecording();
            $this->initial->{$this->initial->stateField()} = SignoffStateHelper::UNSUBMITTED;
            $this->initial->save();
            $this->initial::enableRecording();

            $this->proposed::disableRecording();
            $this->proposed->{$this->proposed->stateField()} = SignoffStateHelper::UNSUBMITTED;
            $this->proposed->save();
            $this->proposed::enableRecording();

            // $this->proposed::loadLookups($this->proposed);
            // $signoff = $this->proposed->createSignoff();
            // $signoff->new_submission = true;
            // $signoff->save();

            $this->initial->onUnsubmit($this);

            flash('Successfully unsubmitted submission.', 'success');
        } else {
            flash('Unable to unsubmit submission.', 'warning');
        }

        return redirect()->back();
    }

    public function signoffConfig(): BelongsTo
    {
        return $this->belongsTo(SignoffConfig::class);
    }

    public function signoffConfigSteps(): HasMany
    {
        return $this->hasMany(SignoffConfigStep::class, 'signoff_config_id', 'signoff_config_id');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(SignoffResponse::class);
    }

    public function responsesForStep(): HasMany
    {
        return $this->hasMany(SignoffResponse::class)->where([
            'archived' => false,
            'step' => $this->step,
        ]);
    }

    public function responsesForCurrentAndNextSteps(): HasMany
    {
        return $this->hasMany(SignoffResponse::class)->where('archived', false)->where('step', '>=', $this->step);
    }

    public function initial(): MorphTo
    {
        return $this->morphTo()->allStates();
    }

    public function proposed(): MorphTo
    {
        return $this->morphTo()->allStates();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\User::class)->withTrashed();
    }
}
