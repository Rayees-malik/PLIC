window.uploaderSetups.push(function () {
  $(".js-label-uploader").each(function () {
    var $input = $(this);
    $(this).fileuploader({
      extensions: null,
      changeInput: " ",
      theme: "thumbnails",
      enableApi: true,
      addMore: true,
      maxSize: 30,
      startImageRenderer: false,
      files: $input.data("preloaded"),
      thumbnails: {
        box:
          '<div class="fileuploader-items">' +
          '<ul class="fileuploader-items-list">' +
          '<li class="fileuploader-thumbnails-input"><div class="fileuploader-thumbnails-input-inner"><i>+</i></div></li>' +
          "</ul>" +
          "</div>",
        item:
          '<li class="fileuploader-item">' +
          '<div class="fileuploader-item-inner">' +
          '<div class="type-holder fileuploader-action-popup">${extension}</div>' +
          '<div class="actions-holder">' +
          '<button type="button" class="fileuploader-action fileuploader-action-remove" title="${captions.remove}"><i class="material-icons">close</i></button>' +
          "</div>" +
          '<div class="thumbnail-holder">' +
          "${image}" +
          '<span class="fileuploader-action-popup"></span>' +
          "</div>" +
          '<div class="content-holder fileuploader-action-popup"><h5>${name}</h5><span>${size2}</span></div>' +
          '<div class="progress-holder">${progressBar}</div>' +
          "</div>" +
          '<div class="dropdown-wrap under-image-dropdown">' +
          '<div class="dropdown-icon">' +
          '<select name="file_label[${data.file_id}]" class="file-label">' +
          '<option>Product</option>' +
          '<option>Box Front</option>' +
          '<option>Label</option>' +
          '<option>Product Family</option>' +
          '</select>' +
          '</div>' +
          '</div>' +
          "</li>",
        item2:
          '<li class="fileuploader-item">' +
          '<div class="fileuploader-item-inner">' +
          '<div class="type-holder fileuploader-action-popup">${extension}</div>' +
          '<div class="actions-holder">' +
          '<a href="${file}" class="fileuploader-action fileuploader-action-download" title="${captions.download}" download><i class="material-icons">save_alt</i></a>' +
          '<button type="button" class="fileuploader-action fileuploader-action-remove" title="${captions.remove}"><i class="material-icons">close</i></button>' +
          "</div>" +
          '<div class="thumbnail-holder js-media-${data.file_id}">' +
          "${image}" +
          '<span class="fileuploader-action-popup"></span>' +
          "</div>" +
          '<div class="content-holder fileuploader-action-popup"><h5 title="${name}">${name}</h5><span>${size2}</span></div>' +
          '<div class="progress-holder">${progressBar}</div>' +
          "</div>" +
          '<div class="dropdown-wrap under-image-dropdown">' +
          '<div class="dropdown-icon">' +
          '<select name="file_label[${data.file_id}]" class="file-label">' +
          '<option>Product</option>' +
          '<option>Box Front</option>' +
          '<option>Label</option>' +
          '<option>Product Family</option>' +
          '</select>' +
          '</div>' +
          '</div>' +
          "</li>",
        startImageRenderer: true,
        canvasImage: false,
        _selectors: {
          list: ".fileuploader-items-list",
          item: ".fileuploader-item",
          start: ".fileuploader-action-start",
          retry: ".fileuploader-action-retry",
          remove: ".fileuploader-action-remove"
        },
        onItemShow: function (
          item,
          listEl,
          parentEl,
          newInputEl,
          inputEl
        ) {
          var plusInput = listEl.find(
            ".fileuploader-thumbnails-input"
          ),
            api = $.fileuploader.getInstance(inputEl.get(0));

          plusInput
            .insertAfter(item.html)
          [
            api.getOptions().limit &&
              api.getFiles().length >= api.getOptions().limit
              ? "hide"
              : "show"
          ]();

          if (item.format == "image") {
            item.html.find(".fileuploader-item-icon").hide();
          }

          if (item.data.label)
            item.html.find('.file-label').val(item.data.label);
        },
        onItemRemove: function (
          html,
          listEl,
          parentEl,
          newInputEl,
          inputEl
        ) {
          var plusInput = listEl.find(
            ".fileuploader-thumbnails-input"
          ),
            api = $.fileuploader.getInstance(inputEl.get(0));

          html.children().animate({ opacity: 0 }, 200, function () {
            html.remove();

            if (
              api.getOptions().limit &&
              (api.getChoosedFiles().length + api.getAppendedFiles().length) - 1 <
              api.getOptions().limit
            )
              plusInput.show();
          });
        }
      },
      dragDrop: {
        container: ".fileuploader-thumbnails-input"
      },
      afterRender: function (listEl, parentEl, newInputEl, inputEl) {
        var plusInput = listEl.find(".fileuploader-thumbnails-input"),
          api = $.fileuploader.getInstance(inputEl.get(0));

        plusInput.on("click", function () {
          api.open();
        });

        api.getOptions().dragDrop.container = plusInput;
      },
      upload: {
        url: "/files/upload",
        data: null,
        type: "POST",
        enctype: "multipart/form-data",
        start: true,
        synchron: true,
        beforeSend: function (
          item,
          listEl,
          parentEl,
          newInputEl,
          inputEl
        ) {
          item.upload.data._token = $("[name='_token']").val();
          item.upload.data.modelId = $(".js-uploader-model-id").val();
          item.upload.data.modelClass = $(
            ".js-uploader-model-class"
          ).val();
        },
        onSuccess: function (result, item) {
          var data = {};

          if (result) data = result;
          else data.success = false;

          if (data.success) {
            item.data.file_id = data.file_id;
            item.html.find('.file-label').attr('name', `file_label[${data.file_id}]`);
          }
          if (data.model_id) {
            $(".js-model-id").val(data.model_id);
            $(".js-uploader-model-id").val(data.model_id);
          }

          setTimeout(function () {
            item.html.find(".progress-holder").fadeOut(400);
            item.renderThumbnail();

            item.html
              .find(
                ".fileuploader-action-popup, .fileuploader-item-image"
              )
              .show();
          }, 400);
        },
        onError: function (item) {
          item.html
            .find(
              ".progress-holder, .fileuploader-action-popup, .fileuploader-item-image"
            )
            .hide();
        },
        onProgress: function (data, item) {
          var progressBar = item.html.find(".progress-holder");

          if (progressBar.length > 0) {
            progressBar.show();
            progressBar
              .find(".fileuploader-progressbar .bar")
              .width(data.percentage + "%");
          }

          item.html
            .find(
              ".fileuploader-action-popup, .fileuploader-item-image"
            )
            .hide();
        }
      },
      onRemove: function (item) {
        $(`<input type="hidden" name="media-deleted[${item.data.file_id}]" value="1" />`).insertBefore(item.html);
        window.stepperForm.forceChange();
        window.updateStepperModel();
      }
    });
  });
});