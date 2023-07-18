// Non-ajax local thumbnail uploader
window.uploaderSetups.push(function () {
  $(".js-local-uploader").each(function () {
    $(this).fileuploader({
      limit: 20,
      maxSize: 30,
      addMore: true
    });
  });
});