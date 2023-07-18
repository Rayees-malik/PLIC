$(function () {
  $('.js-promo-form').on('change', '.js-brand-id', brandChanged);
  $('.js-promo-form').on('change', '.js-discount-type', discountTypeChanged);
  $('.js-promo-form').on('change', '.js-discount-field', discountChanged);
  $('.js-promo-form').on('change', '.js-oi-field', discountChanged);
  $('.js-promo-form').on('change', '.js-promo-oi', oiChanged);

  $('#updateModal').on('change', '.js-quick-all-categories', modalAllCategoriesChanged);
  $('#updateModal').on('click', '.js-quick-apply', modalQuickApply);

  refreshOnChange();
  checkForProducts();
  discountChanged();
});

function refreshOnChange() {
  if ($('select.js-period-id').length) {
    $('.js-period-id').chosen({
      disable_search_threshold: 10,
      width: '100%'
    }).on('change', periodChanged);
  }
}

function checkForProducts() {
  if ($('.js-no-products').length)
    $('.js-submit').prop('disabled', true).hide();
  else
    $('.js-submit').prop('disabled', false).show();
}

function updateQuickUpdate() {
  const options = [];
  $('.js-promo-category').each((_index, element) => {
    const category = $(element).data('category');
    options.push(`<option value="${category}">${category}</option>`);
  });

  $('.js-quick-category').html(options.join('\n'));
}

function brandChanged() {
  $(".ajax-loader").fadeIn(250);
  axios.post(`${$('.js-route-prefix').val()}/promos/periods/render`, {
    'brandId': $(this).val(),
  })
    .then(response => {
      const oldVal = $('.js-period-id').val();
      $('.js-period-select').html(response.data);

      if (!$('.js-period-id[type=hidden]').length) {
        $('.js-period-id').val(oldVal);
        if (!$('.js-period-id').val()) $('.js-period-id').val($('.js-period-id option:first').val());
        refreshOnChange();
      }

      if ($('.js-period-select option').length || $('.js-period-id[type=hidden]').length) {
        axios.post(`/promos/products/render`, {
          'brandId': $(this).val(),
          'periodId': $('.js-period-id').val(),
          'dollarDiscount': $('.js-discount-type:checked').val() == '1'
        })
          .then(response => {
            $('.js-promo-container').html(response.data);
            $('.js-search').val('');

            $('.js-oi-dates').hide(250);
            if ($('.js-brand-id').children("option:selected").data('oi') == '1') {
              $('.js-oi-row').show(250);
              $('.js-oi-column').show(250);
            } else {
              $('.js-oi-row').hide(250);
              $('.js-oi-column').hide(250);
              $('.js-promo-oi[value="0"]').prop('checked', true);
            }
            updateQuickUpdate();
            discountChanged();
            checkForProducts();

            $(".ajax-loader").fadeOut(250);
          });
      } else {
        $('.js-submit').prop('disabled', false).show();
        $('.js-promo-container').html('<h2 class="text-center">No available promo periods for selected brand.</h2>');
        $(".ajax-loader").fadeOut(250);
      }
    });
}

function periodChanged() {
  $(".ajax-loader").fadeIn(250);
  axios.post(`/promos/products/render`, {
    'brandId': $('.js-brand-id').val(),
    'periodId': $(this).val(),
    'dollarDiscount': $('.js-discount-type:checked').val() == '1'
  })
    .then(response => {
      const values = $('.js-promo-container').values();
      delete values['products[]'];
      $('.js-promo-container').html(response.data).values(values);
      $('.js-search').val('');
      discountChanged();

      $(".ajax-loader").fadeOut(250);
    });
}

function discountTypeChanged() {
  if ($(this).val() == '1') {
    $('.js-discount-icon').text('$');
    $('.js-discount-dynamic').removeClass('js-discount-percent').addClass('js-discount-dollar');
  } else {
    $('.js-discount-icon').text('%');
    $('.js-discount-dynamic').removeClass('js-discount-dollar').addClass('js-discount-percent');
  }

  discountChanged();
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
  let poPrice = parseFloat($row.find('.js-product-po-price').first().val());
  let baseDiscount = parseFloat($row.find('.js-product-base-discount').first().text().replace('%', ''));
  let exchangeRate = parseFloat($('.js-exchange-rate').val());

  if (isNaN(price)) price = 0;
  if (isNaN(poPrice)) poPrice = 0;
  if (isNaN(baseDiscount)) baseDiscount = 0;
  if (isNaN(exchangeRate)) exchangeRate = 1;

  const oi = $row.find('.js-oi-field').is(':checked');

  $row.find('.js-discount-field').each((_index, element) => {
    const val = parseFloat($(element).val());
    if (!isNaN(val) && val > 0) {
      if ($(element).hasClass('js-discount-percent')) {
        if (oi && !$(element).hasClass('js-ignore-oi')) {
          const discountAmount = (poPrice * ((val * exchangeRate) / 100)).toFixed(2);
          percentDiscount += (discountAmount / price) * 100;
        } else {
          percentDiscount += val;
        }
      } else {
        percentDiscount += ((val * exchangeRate) / price) * 100;
      }
    }
  });

  percentDiscount += baseDiscount;

  const finalPrice = (price * (1 - (percentDiscount / 100))).toFixed(2);
  const finalPriceExchanged = exchangeRate === 1 ? null : (finalPrice / exchangeRate).toFixed(2);
  if (percentDiscount > 0) {
    if (price > 0) {
      $row.find('.js-final-price').html(`<strong>$${finalPrice}</strong>`);
      if (finalPriceExchanged) $row.find('.js-final-price-exchanged').html(`<strong>~$${finalPriceExchanged}</strong>`);
      $row.find('.js-final-discount').html(`${percentDiscount.toFixed(2)}%`);
    } else {
      $row.find('.js-final-price').html(`<strong>N/A</strong>`);
      if (finalPriceExchanged) $row.find('.js-final-price-exchanged').html(`<strong>N/A</strong>`);
      $row.find('.js-final-discount').html('N/A');
    }
  } else {
    $row.find('.js-final-price').html(price > 0 ? `$${price}` : 'N/A');
    if (finalPriceExchanged) $row.find('.js-final-price-exchanged').html(price > 0 ? `~$${finalPriceExchanged}` : 'N/A');
    $row.find('.js-final-discount').html('-');
  }
}

function oiChanged() {
  if ($(this).val() == '1') {
    $('.js-oi-dates').show(250);
    $('.js-oi-column').show(250);
  } else {
    $('.js-oi-dates').hide(250);
    $('.js-oi-column').hide(250);
    $('.js-oi-field').prop('checked', false);
  }
  discountChanged();
}

function modalAllCategoriesChanged() {
  $(this).is(':checked') ? $('.js-quick-category').attr('disabled', true) : $('.js-quick-category').removeAttr('disabled');
}

function modalQuickApply() {
  $('.js-quick-field').each(function () {
    const valid = $(this).is(':checkbox') ? true : $(this).val();
    if (!valid) return;

    if ($('.js-quick-all-categories').is(':checked')) {
      if ($(this).is(':checkbox')) {
        $(`.${$(this).data('target')}[value="${$(this).val()}"]`).prop('checked', $(this).is(':checked'));
      } else {
        $(`.${$(this).data('target')}`).val($(this).val());
      }
    } else {
      if ($(this).is(':checkbox')) {
        $(`.js-promo-category[data-category="${$('.js-quick-category').val()}"]`).find(`.${$(this).data('target')}[value="${$(this).val()}"]`).prop('checked', $(this).is(':checked'));
      } else {
        $(`.js-promo-category[data-category="${$('.js-quick-category').val()}"]`).find(`.${$(this).data('target')}`).val($(this).val());
      }
    }
  });

  // reload calculations
  discountChanged();

  // Reset form on success
  $('#updateModal').find(':input').not('.js-quick-category').not(':button, :submit, :reset, :hidden, :checkbox, :radio').val('');
  $('#updateModal').modal('hide');
}