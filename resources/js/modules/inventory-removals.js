$(function () {
  $('#app').on('click', '.js-pp-stockid-button', addByStockId);
  $('#app').on('click', '.js-pp-category-button', addByCategory);
  $('#app').on('click', '.js-pp-product-button', addProducts);

  $('#app').on('change', '.js-delete-row', toggleDeleteButton);
  $('#app').on('change', '.js-delete-header', function () { $('.js-delete-row').prop('checked', $(this).is(':checked')); toggleDeleteButton(); });

  $('#app').on('change', '.js-quantity-field', calculateTotals);
  $('#app').on('change', '.js-expiry-field', updateExpiry);

  $('#app').on('change', '.js-check-field', checkChanged);
});

function toggleDeleteButton() {
  $('.js-delete-row:checked').length ? $('.js-removal-delete').show(250) : $('.js-removal-delete').hide(250);
}

window.deleteRemovalRows = function () {
  $deletedIds = $('.js-delete-input');
  const ids = $deletedIds.val().split(',');
  $('.js-delete-row:checked').each(function () {
    $tr = $(this).closest('tr');
    if ($tr.hasClass('js-removal-template-row')) return;

    const id = $tr.find('.js-id').val();
    if (id) ids.push(id);

    $tr.remove();
  });
  $deletedIds.val(ids.filter(n => n).join(','));
  $('.js-removal-delete').hide(250);
}

function addByStockId() {
  const data = { type: 'product', ids: $('.js-pp-stockid').val() };
  $('.js-pp-stockid').val('').trigger('chosen:updated');
  addRemovalRows(data);
}

function addByCategory() {
  const data = { type: 'category', ids: $('.js-pp-category').val() };
  $('.js-pp-category').val('').trigger('chosen:updated').trigger('change');
  addRemovalRows(data);
}

function addProducts() {
  const data = { type: 'product', ids: $('.js-pp-product').val() };
  $('.js-pp-product').val('').trigger('chosen:updated');
  addRemovalRows(data);
}

function addRemovalRows(searchData) {
  axios.post('/removals/productsearch', searchData).then(function (data) {
    $errorCount = 0;
    data.data.forEach(record => {
      if (!record.stock_data) {
        $errorCount++;
        return;
      }

      $row = $('.js-removal-template-row').clone();
      $row.find('.js-template-delete-row').removeClass('js-template-delete-row').addClass('js-delete-row');
      $row.find('.js-product-id').val(record.id);
      $row.find('.js-description-row').html(`<strong>${record.stock_id}</strong><br />${record.description}`);
      $row.find('.js-brand-row').text(record.brand);

      $.map(record.stock_data, function (warehouse, wIndex) {
        const options = [];
        $.map(warehouse, function (stock, sIndex) {
          const display = `${wIndex.padStart(2, '0')}: ${stock.expiry} (QTY ${stock.quantity}, $${stock.average_landed_cost})`;
          const dataParams = `data-expiry="${stock.expiry}" data-quantity="${stock.quantity}" data-cost="${stock.unit_cost}" data-average-landed-cost="${stock.average_landed_cost}" data-warehouse="${wIndex}"`;
          options.push(`<option value="${sIndex}" ${dataParams}>${display}</option>`);
        });

        const label = `WHSE #${wIndex.padStart(2, '0')}`;
        $row.find('.js-expiry-field').append(`<optgroup label="${label}">${options.join('\n')}</optgroup>`);
      });
      $row.find('input,select').removeAttr('disabled');
      $row.removeClass('js-removal-template-row').insertBefore('.js-removal-template-row').show();
    });

    if ($errorCount) {
        document.getElementById('notification').dispatchEvent(new CustomEvent('notify', {detail: {content: 'Selected product(s) have no current warehouse stock!', type: 'error'}, bubbles: true, cancelable: true}));
    }

    window.skipRemovalTotals = true;
    $('.js-expiry-field').each(function () {
      $(this).trigger('change');
    });
    window.skipRemovalTotals = false;
    calculateTotals();
  });
}

function updateExpiry() {
  const option = $(this).find(":selected").first();
  $(this).closest('tr').find('.js-cost').text($(option).data('cost'));
  $(this).closest('tr').find('.js-average-landed-cost').text($(option).data('average-landed-cost'));
  $(this).closest('.dropdown-wrap').find('.js-hidden-average-landed-cost').val($(option).data('average-landed-cost'));
  $(this).closest('.dropdown-wrap').find('.js-hidden-cost').val($(option).data('cost'));
  $(this).closest('.dropdown-wrap').find('.js-hidden-warehouse').val($(option).data('warehouse'));
  $(this).closest('.dropdown-wrap').find('.js-hidden-expiry').val($(option).data('expiry'));

  if (!window.skipRemovalTotals)
    calculateTotals();
}

function calculateTotals() {
  let total = 0;
  let averageLandedTotal = 0;

  $('.js-quantity-field').each(function () {
    let quantity = parseFloat($(this).val());
    quantity = isNaN(quantity) ? 0 : quantity;

    let averageLandedCost = parseFloat($(this).closest('tr').find('.js-average-landed-cost').text());
    averageLandedCost = isNaN(averageLandedCost) ? 0 : averageLandedCost;

    let cost = parseFloat($(this).closest('tr').find('.js-cost').text());
    cost = isNaN(cost) ? 0 : cost;

    const averageLineTotal = (quantity * averageLandedCost).toFixed(2);
    $(this).closest('tr').find('.js-adj-average-landed-cost').text(averageLineTotal);
    averageLandedTotal += parseFloat(averageLineTotal);

    const lineTotal = (quantity * cost).toFixed(2);
    $(this).closest('tr').find('.js-adj-cost').text(lineTotal);
    total += parseFloat(lineTotal);
  });

  $('.js-average-landed-total').text(averageLandedTotal.toFixed(2));
  $('.js-total').text(total.toFixed(2));
}

function checkChanged() {
  if ($(this).is(':checked')) {
    $(this).attr('name', $(this).data('name'));
    $(this).prev().removeAttr('name');
  } else {
    $(this).removeAttr('name');
    $(this).prev().attr('name', $(this).data('name'));
  }
}
