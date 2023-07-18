<h6>Previously Deleted</h6>
<div class="fileuploader fileuploader-theme-thumbnails">
    <div class="fileuploader-items">
        <ul class="fileuploader-items-list">
            @foreach ($deletedMedia as $media)
            <li class="fileuploader-item file-type-image file-ext-jpg file-has-popup">
                <div class="fileuploader-item-inner fileuploader-item-restore">
                    <div class="type-holder fileuploader-action-popup">
                        {{ explode('/', $media->mime_type)[1] }}
                    </div>
                    <div class="actions-holder">
                        <a href="{{ $media->getUrl() }}" class="fileuploader-action fileuploader-action-download" title="Download" download="">
                            <i class="material-icons">save_alt</i>
                        </a>
                        <button type="button" class="fileuploader-action fileuploader-action-restore js-restore-media" title="Restore" data-file-id="{{ $media->id }}" data-collection="{{ $media->collection_name }}" data-type="{{ $type }}">
                            <i class="material-icons">settings_backup_restore</i>
                        </button>
                    </div>
                    <div class="thumbnail-holder js-media-{{ $media->id }}">
                        <div class="fileuploader-item-image">
                            {{ $media('thumb') }}
                        </div>
                        <span class="fileuploader-action-popup"></span>
                    </div>
                    <div class="content-holder fileuploader-action-popup">
                        <h5 title="{{ $media->file_name }}">
                            {{ $media->file_name }}
                        </h5>
                    </div>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
</div>
