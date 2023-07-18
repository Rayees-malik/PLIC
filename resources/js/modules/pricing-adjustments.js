$(function () {
  $('#app').on('click', '.js-pp-stockid-button', addByStockId);
  $('#app').on('click', '.js-pp-brand-button', addLineDrives);
  $('#app').on('click', '.js-pp-category-button', addByCategory);
  $('#app').on('click', '.js-pp-product-button', addProducts);

  $('#app').on('change', '.js-discount-type', discountTypeChanged);
  $('#app').on('change', '.js-ongoing', ongoingChanged);
  $('#app').on('change', '.js-mcb-type', mcbTypeChanged);
  $('#app').on('change', '.js-delete-row', toggleDeleteButton);
  $('#app').on('change', '.js-delete-header', function () { $('.js-delete-row').prop('checked', $(this).is(':checked')); toggleDeleteButton(); });

  $('#updateModal').on('click', '.js-quick-apply', modalQuickApply);

  // Allow selecting/deselecting an entire group
  $(document).on('click', '.group-result', function () {
    const unselected = $(this).nextUntil('.group-result').not('.result-selected');
    if (unselected.length) {
      unselected.trigger('mouseup');
    } else {
      $(this).nextUntil('.group-result').each(function () {
        $('a.search-choice-close[data-option-array-index="' + $(this).data('option-array-index') + '"]').trigger('click');
      });
    }
  });
});

function addByStockId() {
  const data = { type: 'product', ids: $('.js-pp-stockid').val() };
  $('.js-pp-stockid').val('').trigger('chosen:updated');
  addPAFRows(data);
}

function addLineDrives() {
  const data = { type: 'brand', ids: $('.js-pp-brand').val() };
  $('.js-pp-brand').val('').trigger('chosen:updated').trigger('change');
  addPAFRows(data);
}

function addByCategory() {
  const data = { type: 'category', ids: $('.js-pp-category').val() };
  $('.js-pp-category').val('').trigger('chosen:updated').trigger('change');
  addPAFRows(data);
}

function addProducts() {
  const data = { type: 'product', ids: $('.js-pp-product').val() };
  $('.js-pp-product').val('').trigger('chosen:updated');
  addPAFRows(data);
}

function addPAFRows(searchData) {
  axios.post('/pafs/productsearch', searchData).then(function (data) {
    data.data.forEach(record => {

      // Prevent duplicate entries
      let dupe = false;
      const $matched = $(`.js-morph-id[value="${record.morph_id}"]`);

      $matched.each(function () {
        if (!dupe && $matched.next().val() == record.morph_type)
          dupe = true;
      });

      if (dupe)
        return;

      $row = $('.js-adjustments-template-row').clone();

      $row.find('.js-template-delete-row').removeClass('js-template-delete-row').addClass('js-delete-row');
      $row.find('.js-morph-id').val(record.morph_id);
      $row.find('.js-morph-type').val(record.morph_type);
      $row.find('.js-stockid-row').text(record.stock_id);
      $row.find('.js-upc-row').text(record.upc);
      $row.find('.js-brand-row').text(record.brand);
      $row.find('.js-description-row').text(record.description);
      $row.find('.js-who-to-mcb').val(record.who_to_mcb);
      $row.find('input').removeAttr('disabled');
      $row.removeClass('js-adjustments-template-row').insertBefore('.js-adjustments-template-row').show();
    });
  });
}

function toggleDeleteButton() {
  $('.js-delete-row:checked').length ? $('.js-paf-delete').show(250) : $('.js-paf-delete').hide(250);
}

window.deletePAFRows = function () {
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
  $('.js-paf-delete').hide(250);
}

function discountTypeChanged() {
  $('.js-discount-icon').text($(this).val() == '1' ? '$' : '%');
  $('.js-discount-title').text($(this).val() == '1' ? 'Fixed Price' : 'Total Discount');
}

function mcbTypeChanged() {
  $('.js-mcb-discount-icon').text($(this).val() == '1' ? '$' : '%');
  $('.js-mcb_type').text($(this).val() == '1' ? 'MCB Dollar Amount' : 'MCB Portion of Total Discount');
}

function ongoingChanged() {
  if ($(this).val() === '1') {
    $('.js-end-date').val($('.js-ongoing-end-date').val()).attr('disabled', true).trigger("chosen:updated")
  } else {
    $('.js-end-date').removeAttr('disabled').trigger("chosen:updated");
  }
}

function modalQuickApply() {
  $('.js-quick-field').each(function () {
    if (!$(this).val()) return;
    $(`.${$(this).data('target')}`).val($(this).val());
  });

  // Reset form on success
  $('#updateModal').find(':input').not('.js-quick-category').not(':button, :submit, :reset, :hidden, :checkbox, :radio').val('');
  $('#updateModal').modal('hide');
}
