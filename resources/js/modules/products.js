$(function () {
  $('.js-country-shipped').on('change', countryChanged);
  $('#app').on('change', '.js-cat-field', updatePreview);
  $('#app').on('change', '.js-brand', brandChanged);
  $('#app').on('change', '.js-category', categoryChanged);
  $('#app').on('change', '.js-cat-category', catCategoryChanged);
  $('#app').on('change', '.js-measurement', measurementChanged);
  $('#app').on('change', '.js-not-for-resale', notForResaleChanged);
  $('#app').on('change', '.js-tester-available', testerAvailableChanged);
  $('#app').on('change', '.js-cert-switch', certSwitchChanged);
  $('#app').on('change', '.js-medical-class', medicalClassChanged);
  $('#app').on('change', '.js-importer', importerChanged);
  $('#app').on('change', '.js-landed-field', calcLandedCost);
  $('#app').on('change', '.js-wholesale-price', calcWholesaleMargin);
  $('#app').on('change', '.js-srp', calcSRPMargin);
  $('#app').on('click', '.js-restore-media', window.restoreMedia);
  $('#app').on('click', '.js-show-cat-preview', toggleCatPreview);

  $('.js-cat-field').trigger('change');
  $('.js-cat-category').trigger('change');

  $('.js-supersedes').parent().find('.chosen-search-input').autocomplete({
    source: function (request, response) {
      axios.post('/products/search/', { all: request.term, brand_id: $('.js-brand').val() }).then(function (data) {
        if (!data.data || (data.data.length == 0)) return;
        $('.js-supersedes').empty().append('<option></option>');
        response($.map(data.data, function (item) {
          $('.js-supersedes').append(`<option value="${item.id}">${item.stock_id} - ${item.name || item.name_fr} (${item.size || 1}${!item.uom ? 'un' : item.uom.unit})</option>`);
        }));
        $(".js-supersedes").trigger("chosen:updated");
      });
    }
  });
});

if (!window.onRender) {
  window.onRender = function () {
    $('.js-country-shipped').on('change', countryChanged);
    updatePlaceholders($('.js-measurement').val());
    $('.js-cat-field').trigger('change');
    calcLandedCost();
  };
}

function updatePreview() {
  const target = $(this).data('cat-target');
  const action = $(this).data('cat-action');

  let val = $(this).val();
  if (val === "" && $(this).data('cat-ifnull')) {
    val = $(`.${$(this).data('cat-ifnull')}`).val();
  }

  switch (action) {
    case 'currency':
      const currencyVal = _.round(Number(val), 2).toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1,');
      $(`.product-catalogue-display__${target}`).text(`$${currencyVal}`);
      break;
    case 'upc':
      const upc = val.substr(val.length - 6).padStart(6, '0');
      $(`.product-catalogue-display__${target}`).text(upc);
      break;
    case 'select':
      const $child = $(this).children(':selected').first();
      const selectVal = val === '' ? '' : $child.data('cat-val') ? $child.data('cat-val') : $child.text();
      $(`.product-catalogue-display__${target}`).text(selectVal);
      break;
    default:
      $(`.product-catalogue-display__${target}`).text(val);
  }
}

function countryChanged() {
  if ($(this).val() == 40)
    $('.js-tariff').hide(250);
  else
    $('.js-tariff').show(250);
}

function brandChanged() {
  $('.ajax-loader').fadeIn(250);
  axios
    .get(`/brands/${$(this).val()}/categories/json`)
    .then(function (response) {
      $('.js-cat-category').empty();

      $('.js-cat-category').append('<option value="" disabled selected>Select Category</option>');
      if (Array.isArray(response.data) && response.data.length) {
        response.data.forEach(category => {
          $('.js-cat-category').append(`<option data-cat-category-name-fr="${category.name_fr}" value="${category.id}">${category.name}</option>`);
        });
      }

      $('.js-cat-category').append('<option value="0">Add New Category</option>');
      catCategoryChanged();
    })
    .catch(function (error) {
      console.log(error);
    })
    .finally(function () {
      $('.ajax-loader').fadeOut(250);
    });
}

function categoryChanged() {
  if (!$(this).val()) {
    $('.js-sub-category').append(`<option value selected>No Subcategories Found</option>`);
    return;
  }

  const previousCatId = $('.js-sub-category').val();

  // Refresh subcategories on category selection
  $('.ajax-loader').fadeIn(250);
  axios
    .post(`/categories/${$(this).val()}/subcategories`, {
      previous_category_id: previousCatId
    }).then(function (response) {
      $('.js-sub-category').empty();

      if (Array.isArray(response.data) && response.data.length) {
        response.data.forEach(category => {
          $('.js-sub-category').append(`<option value="${category.id}">${category.name}</option>`);
        });
        $('.js-sub-category').val(previousCatId);
      } else {
        $('.js-sub-category').append(`<option value selected>No Subcategories Found</option>`);
      }
    })
    .catch(function (error) {
      console.log(error);
    })
    .finally(function () {
      $('.ajax-loader').fadeOut(250);
    });
}

function catCategoryChanged() {
  if ($('.js-cat-category').val() === '0') {
    $('.js-cat-category-fr').hide(50);
    $('.js-cat-category-fr span').text('');

    $('.js-new-cat-category').show(250);
  }
  else {
    $('.js-new-cat-category').hide(250);

    if ($('.js-cat-category').val() && $('.js-cat-category').find(":selected").data('catCategoryNameFr') !== '') {
      $('.js-cat-category-fr').show(50);
      $('.js-cat-category-fr span').text($('.js-cat-category').find(":selected").data('catCategoryNameFr'));
    } else {
      $('.js-cat-category-fr').hide();
    }
  }
}

function notForResaleChanged() {
  if ($(this).is(':checked')) {
    $('.js-unit-cost').attr('readonly', true).val(0.00)
    $('.js-price-change-reason').attr('readonly', true).val('')
  } else {
    $('.js-unit-cost').attr('readonly', false);
    $('.js-price-change-reason').attr('readonly', false);
  }
}

function getSellByCount() {
  const sellBy = $('.js-purity-sell-by').val();

  let sellByCount = 1;
  if (sellBy == 2)
    sellByCount = $('.js-inner-units').val();
  else if (sellBy == 4)
    sellByCount = $('.js-master-units').val();

  if (isNaN(sellByCount)) sellByCount = 1;

  return sellByCount;
}

function calcLandedCost() {
  let cost = $('.js-unit-cost').val();
  if (cost === '') cost = $('.js-current-unit-cost').val();
  cost = isNaN(cost) ? 0 : Number(cost);

  const extraAddonPercent = 1 + (Number($('.js-extra-addon-percent').val()) / 100);
  const exchangeRate = Number($('.js-exchange-rate').val());
  const freightPercent = 1 + (Number($('.js-freight').val()) / 100);
  const dutyPercent = 1 + (Number($('.js-duty').val()) / 100);
  const edlp = 1 - (Number($('.js-edlp').val()) / 100);
  const margin = 1 - (Number($('.js-margin').val()) / 100);

  const landedCost = _.round(cost * exchangeRate * extraAddonPercent * freightPercent * dutyPercent * edlp, 2);
  const wholesalePrice = _.round(landedCost / margin, 3);

  const sellByCount = getSellByCount();

  const srp = _.max([_.round(((_.round((wholesalePrice / sellByCount) / 0.06) * 10) - 1) / 100, 2), 0]);
  $('.js-landed-cost').val(landedCost);
  $('.js-landed-cost-display').text(landedCost.toFixed(2));
  $('.js-wholesale-display').text(wholesalePrice);
  $('.js-srp-display').text(srp.toFixed(2));
}

function calcWholesaleMargin() {
  const landedCost = $('.js-landed-cost').val();
  const wholesalePrice = $('.js-wholesale-price').val();

  if (!isNaN(wholesalePrice) && wholesalePrice > 0) {
    const margin = ((1 - (landedCost / wholesalePrice)) * 100).toFixed(2);
    $('.js-wholesale-margin').text(margin);
  } else {
    $('.js-wholesale-margin').text('-');
  }

  calcSRPMargin();
}

function calcSRPMargin() {
  const wholesalePrice = $('.js-wholesale-price').val();
  const sellByCount = getSellByCount();
  const srp = $('.js-srp').val();

  if (!isNaN(srp) && srp > 0) {
    const margin = ((1 - ((wholesalePrice / sellByCount) / srp)) * 100).toFixed(2);
    $('.js-srp-margin').text(margin);
  } else {
    $('.js-srp-margin').text('-');
  }
}

function measurementChanged() {
  const system = $(this).val();
  updatePlaceholders(system);

  $('.js-cm > input').filter(function () { return this.value; }).each(function () {
    $(this).val(_.round(system === 'metric' ? this.value * 2.54 : this.value / 2.54, 4));
  })
  $('.js-kg > input').filter(function () { return this.value; }).each(function () {
    $(this).val(_.round(system === 'metric' ? this.value / 2.2046 : this.value * 2.2046, 4));
  })
}

function updatePlaceholders(system) {
  $('.js-cm').each(function () {
    $(this).attr('data-placeholder', system === 'metric' ? 'cm' : 'in');
  });
  $('.js-kg').each(function () {
    $(this).attr('data-placeholder', system === 'metric' ? 'kg' : 'lb');
  });
}

function testerAvailableChanged() {
  $(this).val() === '1'
    ? $('.js-tester-code').show(250)
    : $('.js-tester-code').hide(250);
}

function certSwitchChanged() {
  $(this).is(':checked')
    ? $(`.js-cert-file-${$(this).val()}`).show(250)
    : $(`.js-cert-file-${$(this).val()}`).hide(250);
}

function importerChanged() {
  $(this).val() === '1'
    ? $('.js-importer-details').hide(250)
    : $('.js-importer-details').show(250);
}

function toggleCatPreview() {
  $div = $('.js-cat-preview');
  if ($div.is(':visible')) {
    $(this).text('Show catalogue preview');
    $div.hide(250);
  } else {
    $(this).text('Hide catalogue preview');
    $div.show(250);
  }
}

function medicalClassChanged() {
  $(this).val() == '2' ? $('.js-medical-device').show() : $('.js-medical-device').hide();
}
