$(function () {
  $('.js-datatable-filter').on('change', refreshDatatable);
});

function refreshDatatable() {
  $('#dataTableBuilder').DataTable().draw();
}

window.addDatatableFilter = function (data) {
  $('.js-datatable-filter').each(function () {
    data[$(this).data('filter')] = $(this).val();
  });
}