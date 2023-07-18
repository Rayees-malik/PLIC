$(function () {
  $('.js-promo-form').on('keyup', '.js-search', doSearch);
  $('.js-promo-form').on('change', '.js-discount-field', discountChanged);

  $('#updateModal').on('change', '.js-quick-all-brands', modalAllBrandsChanged);
  $('#updateModal').on('click', '.js-quick-apply', modalQuickApply);

  discountChanged();
});

function doSearch() {
  const searchTerm = $(this).val().trim().toLowerCase();
  if (searchTerm.length === 0) {
    $('.js-promo-row').removeClass('row-search');
    $('.js-promo-brand').each((_index, element) => {
      if ($(element).find('input:visible').filter((_index, element) => element.value).length)
        window.openAcc($(element));
      else
        window.closeAcc($(element));
    });
    return;
  }

  $('.js-promo-brand').each((_index, element) => {
    let brandMatch = false;
    $(element).find('.js-promo-row').each((_index, element) => {
      let productMatch = false;
      $(element).find('.js-search-field').each((_index, element) => {
        productMatch = $(element).text().toLowerCase().includes(searchTerm);
        brandMatch = brandMatch || productMatch;
        return !productMatch;
      });
      productMatch ? $(element).addClass('row-search') : $(element).removeClass('row-search');
    });
    brandMatch ? window.openAcc($(element)) : window.closeAcc($(element));
  });
}

function discountChanged() {
  if (this != window) return calcDiscount($(this).closest('.js-promo-row'));

  $('.js-promo-row').each((_index, element) => {
    calcDiscount($(element));
  });
}

function calcDiscount($row) {
  let percentDiscount = 0;
  let price = parseFloat($row.find('.js-product-price').first().val());
  if (isNaN(price)) price = 0;

  $row.find('.js-discount-field').each((_index, element) => {
    const val = parseFloat($(element).val());
    if (!isNaN(val) && val > 0)
      percentDiscount += val;
  });

  const finalPrice = (price * (1 - (percentDiscount / 100))).toFixed(2);
  if (percentDiscount > 0) {
    if (price > 0) {
      $row.find('.js-final-price').html(`<strong>$${finalPrice}</strong>`);
      $row.find('.js-final-discount').html(`${percentDiscount.toFixed(2)}%`);
    } else {
      $row.find('.js-final-price').html(`<strong>N/A</strong>`);
      $row.find('.js-final-discount').html('N/A');
    }
  } else {
    $row.find('.js-final-price').html(price > 0 ? `$${price}` : 'N/A');
    $row.find('.js-final-discount').html('-');
  }
}

function modalAllBrandsChanged() {
  $(this).is(':checked') ? $('.js-quick-brand').attr('disabled', true) : $('.js-quick-brand').removeAttr('disabled');
}

function modalQuickApply() {
  $('.js-quick-field').each(function () {
    const valid = $(this).is(':checkbox') ? true : $(this).val();
    if (!valid) return;

    if ($('.js-quick-all-brands').is(':checked')) {
      if ($(this).is(':checkbox')) {
        $(`.${$(this).data('target')}[value="${$(this).val()}"]`).prop('checked', $(this).is(':checked'));
      } else {
        $(`.${$(this).data('target')}`).val($(this).val());
      }
    } else {
      if ($(this).is(':checkbox')) {
        $(`.js-promo-brand[data-brand="${$('.js-quick-brand').val()}"]`).find(`.${$(this).data('target')}[value="${$(this).val()}"]`).prop('checked', $(this).is(':checked'));
      } else {
        $(`.js-promo-brand[data-brand="${$('.js-quick-brand').val()}"]`).find(`.${$(this).data('target')}`).val($(this).val());
      }
    }
  });

  // reload calculations
  discountChanged();

  // Reset form on success
  $('#updateModal').find(':input').not('.js-quick-brand').not(':button, :submit, :reset, :hidden, :checkbox, :radio').val('');
  $('#updateModal').modal('hide');
}