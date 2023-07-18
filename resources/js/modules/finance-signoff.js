$(function () {
  $('#app').on('change', '.js-selected-header', function () { $('.js-selected-row').prop('checked', $(this).is(':checked')); });

  var $table = $('.table.datatable');

  $table.floatThead({
      position: 'absolute',
      top: 60,
      floatTableClass: 'float-header',
  });
});
