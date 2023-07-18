<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SignoffResponse extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $with = ['user:id,name'];

    public static function saveResponse($signoff, $action, $comment, $commentOnly = false)
    {
        // TODO: This is a hack to prevent saving a comment on step 0
        //       when there is a duplicate form submission.
        if ($signoff->step == 0) {
            return;
        }

        return SignoffResponse::create([
            'signoff_id' => $signoff->id,
            'user_id' => auth()->id(),
            'step' => $signoff->step,
            'approved' => $action,
            'comment' => $comment,
            'archived' => $commentOnly,
            'comment_only' => $commentOnly,
        ]);
    }

    public function signoff(): BelongsTo
    {
        return $this->belongsTo(Signoff::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}
