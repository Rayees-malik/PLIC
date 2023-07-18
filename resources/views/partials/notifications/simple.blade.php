<div class="notification notification-{{ Arr::get($notification->data, 'status') ?? 'default' }}">
    <a href="{{ route('notifications.read', $notification->id) }}" style="text-decoration: none; color: inherit;">
        <h4>{{ Arr::get($notification->data, 'title') ?? 'Notice' }}</h4>
        <p>{!! Arr::get($notification->data, 'body') ?? 'No data.' !!}</p>
    </a>
    <div class="close js-close-notification" data-id="{{ $notification->id }}">
        <i class="material-icons">close</i>
    </div>
</div>
