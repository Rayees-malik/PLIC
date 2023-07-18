$(function () {
  $('#app').on('change', '.js-account', accountChanged);
  $('#app').on('click', '.js-maf-add', addMAFRow);

  $('#app').on('change', '.js-delete-row', toggleDeleteButton);
  $('#app').on('change', '.js-delete-header', function () { $('.js-delete-row').prop('checked', $(this).is(':checked')); toggleDeleteButton(); });

  $('#app').on('change', '.js-cost-field', calculateTotal);
  $('#app').on('change', '.js-mcb-field', calculateTotal);
  $('#app').on('change', '.js-tax-field', calculateTotal);
});

function accountChanged() {
  if ($(this).val() === 'Other')
    $('.js-account-other-row').show(250);
  else
    $('.js-account-other-row').hide(250);
}

function toggleDeleteButton() {
  $('.js-delete-row:checked').length ? $('.js-maf-delete').show(250) : $('.js-maf-delete').hide(250);
}

window.deleteMAFRows = function () {
  $deletedIds = $('.js-delete-input');
  const ids = $deletedIds.val().split(',');
  $('.js-delete-row:checked').each(function () {
    $tr = $(this).closest('tr');
    if ($tr.hasClass('js-adjustments-template-row')) return;

    const id = $tr.find('.js-id').val();
    if (id) ids.push(id);

    $tr.remove();
  });
  $deletedIds.val(ids.filter(n => n).join(','));
  $('.js-maf-delete').hide(250);
}

function addMAFRow() {
  $row = $('.js-agreement-template-row').clone();

  $row.find('.js-template-delete-row').removeClass('js-template-delete-row').addClass('js-delete-row');
  $row.find('input').removeAttr('disabled');
  $row.find('.js-brand-id').removeAttr('disabled').chosen({
    disable_search_threshold: 10,
    search_contains: true,
    width: "100%"
  });
  $row.removeClass('js-agreement-template-row').insertBefore('.js-agreement-template-row').show();
}

function calculateTotal() {
  let subtotal = 0;

  $('.js-cost-field').each(function () {
    const val = parseFloat($(this).val());
    if (isNaN(val)) return;
    subtotal += parseFloat(val);
  });

  let taxRate = parseFloat($('.js-tax-field').val());
  if (isNaN(taxRate)) taxRate = 13;
  const tax = subtotal * (taxRate / 100);
  const total = subtotal + tax;

  $('.js-subtotal').text(subtotal.toFixed(2));
  $('.js-tax-total').text(tax.toFixed(2));
  $('.js-total').text(total.toFixed(2));
}