$(function () {
  $("#app").on("change", ".js-period-type", typeChanged);
  $("#app").on("change", ".js-period-active", toggleActive);
});

function typeChanged() {
  switch ($(this).val()) {
    case '0':
      $('.js-base-period').hide(250);
      $('[name="base_period_id"]').val('');
      $('.js-orderform-header').hide(250);
      break;
    case '1':
      $('.js-base-period').show(250);
      $('.js-orderform-header').show(250);
      break;
    case '2':
      $('.js-base-period').hide(250);
      $('[name="base_period_id"]').val('');
      $('.js-orderform-header').show(250);
  }
}

function toggleActive() {
  const id = $(this).data('id');
  const dt = $('#dataTableBuilder').DataTable();

  dt.rows(`#${id}`).remove();

  axios.get(`/promos/periods/${id}/toggle`)
    .then(function () {
      dt.rows(`#${id}`).draw();
    }).catch(function (error) {
      console.error(error);
    });
}