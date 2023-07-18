<?php

namespace App\Notifications;

use App\Helpers\SignoffNotificationTypeHelper;
use App\Models\Signoff;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

abstract class SignoffNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public ?SignoffNotificationTypeHelper $signoffType;

    public function __construct(
        public ?Signoff $signoff,
        public ?User $user,
        public ?string $comment,
        protected string $adjective,
        protected string $verb,
        ) {
    }

    public function shouldSend($notifiable, $channel)
    {
        if ($channel != 'mail') {
            return true;
        }

        $count = Cache::remember('signoff_notification_count', 60, fn () => 0);

        while ($count >= config('plic.notifications.email.max_per_minute')) {
            sleep(5);
            $count = Cache::remember('signoff_notification_count', 60, fn () => 0);
        }

        Cache::increment('signoff_notification_count');

        return true;
    }

    public function getSubject(): string
    {
        return __('notifications.signoff_subject', [
            'signoff_type' => $this->signoff->proposed->getShortClassName(true),
            'verb' => $this->verb,
            'adjective' => $this->adjective,
            'dynamic_properties' => $this->resolveDynamicProperties(),
        ]);
    }

    public function getHeaderText(): string
    {
        return __("notifications.signoff_{$this->adjective}_header");
    }

    public function via($notifiable)
    {
        $via = ['database'];
        if ($notifiable->wantsMailNotification($this)) {
            $via[] = 'mail';
        }

        return $via;
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject($this->getSubject())
            ->markdown($this->resolveView(), [
                'header' => $this->getHeaderText(),
                'textDetails' => $this->resolveTextDetails(),
                'summaryData' => $this->getSummaryData(),
                'url' => $this->resolveUrl(),
            ]);
    }

    public function getBody($data)
    {
        $views = [
            'approved' => 'SignoffApproved',
            'pending' => 'SignoffPending',
            'rejected' => 'SignoffRejected',
            'submitted' => 'SignoffSubmitted',
        ];

        $view = 'notifications.messages.' . $views[$data['adjective']];

        if (! view()->exists($view)) {
            return Str::of($data['type'])->ucsplit()->implode(' ');
        }

        return view($view)->with(['notification' => $data])->render();
    }

    public function resolveView(): string
    {
        return 'mail.signoff.signoff';
    }

    public function resolveType(): string
    {
        return strtolower($this->signoff->proposed->getShortClassName());
    }

    public function resolveUrl(): string
    {
        return route('signoffs.edit', $this->signoff->id);
    }

    public function toArray($notifiable)
    {
        $modelType = $this->signoff->proposed->getShortClassName();
        $arrayData = [
            'type' => class_basename($this),
            'signoff_id' => $this->signoff->id,
            'model_name' => $this->signoff->proposed->displayName,
            'model_type' => $modelType,
            'adjective' => $this->adjective,
            'action' => route('signoffs.edit', $this->signoff->id),
        ];

        $arrayData['title'] = "Signoff Pending - {$modelType}";
        $arrayData['body'] = $this->getBody($arrayData);

        return $arrayData;
    }

    public function getSignoffNotificationType(): string
    {
        if (in_array($this->signoff->signoffConfig->model, [\App\Models\ProductDelistRequest::class, \App\Models\BrandDiscoRequest::class])) {
            return SignoffNotificationTypeHelper::DISCONTINUATION;
        }

        switch ($this->verb) {
            case 'change':
                return SignoffNotificationTypeHelper::CHANGE;
            case 'discontinuation':
                return SignoffNotificationTypeHelper::DISCONTINUATION;
            case 'listing':
                return SignoffNotificationTypeHelper::LISTING;
            case 'price change':
                return SignoffNotificationTypeHelper::PRICE_CHANGE;
            case 'relisting':
                return SignoffNotificationTypeHelper::RELISTING;
            case 'submission':
                return SignoffNotificationTypeHelper::SUBMISSION;
            case 'unsubmission':
                return SignoffNotificationTypeHelper::UNSUBMISSION;
            default:
                return SignoffNotificationTypeHelper::UNKNOWN;
        }
    }

    abstract protected function resolveAttributes(): array;

    protected function resolveDynamicProperties(): string
    {
        return $this->signoff->proposed->name;
    }

    protected function resolveTextDetails()
    {
        return null;
    }

    protected function getSummaryData(): array
    {
        $summaryData = [];

        $summaryData['Submitted By'] = $this->signoff->user->name;

        if ($this->adjective === 'pending') {
            $summaryData['Approved By'] = $this->user->name;
        } elseif ($this->adjective === 'rejected') {
            $summaryData['Rejected By'] = $this->user->name;
        }

        if ($this->adjective === 'rejected' && $this->comment) {
            $summaryData['Comment'] = $this->comment;
        }

        return array_merge($this->resolveAttributes(), $summaryData);
    }
}
