$(function () {
  $('#app').on('change', '.js-cat-field', updatePreview);
  $('#app').on('change', '.js-distribution-status', distributionChanged);
  $('#app').on('change', '.js-nutrition-house', updateNutritionHouse);
  $('#app').on('change', '.js-health-first', updateHealthFirst);
  $('#app').on('change', '.js-brokers', brokersChanged);
  $('#app').on('click', '.js-restore-media', window.restoreMedia);
  $('#app').on('click', '.js-show-cat-preview', toggleCatPreview);

  $('.js-cat-field').trigger('change');
});

if (!window.onRender) {
  window.onRender = function () {
    $('.js-brokers').on('change', brokersChanged);
    distributionChanged();
    updateNutritionHouse();
    updateHealthFirst();
    $('.js-cat-field').trigger('change');
  };
}

function updatePreview() {
  const target = $(this).data('cat-target');
  const action = $(this).data('cat-action');

  switch (action) {
    case 'visibility':
      if (($(this).is(':checkbox') && $(this).is(':checked')) || ($(this).is(':radio') && $(this).is(':checked') && $(this).val() === "1")) {
        $(`.brand-catalogue-display__${target}`).css('visibility', 'visible');
      } else {
        $(`.brand-catalogue-display__${target}`).css('visibility', 'hidden');
      }
      break;
    default:
      $(`.brand-catalogue-display__${target}`).text($(this).val());
  }
}

function brokersChanged() {
  let selectedOptions = [];
  $('.js-brokers option:selected').each(function () { selectedOptions.push($(this).text()) });
  $('.brand-catalogue-display__broker').text(selectedOptions.join(' '));
}

function distributionChanged() {
  const purityExclusive = $('[name="contract_exclusive"]:checked').val() === '1';
  const noOtherDistributors = $('[name="no_other_distributors"]').is(':checked');

  if (purityExclusive || noOtherDistributors) {
    $('.js-also-distributed').css('visibility', 'hidden').find('input').val('');
  } else {
    $('.js-also-distributed').css('visibility', 'visible');
  }

  if (purityExclusive)
    $('.js-other-distributor-wrap').css('visibility', 'hidden');
  else $('.js-other-distributor-wrap').css('visibility', 'visible');
}

function updateNutritionHouse() {
  if ($('[name="nutrition_house"]:checked').val() === '1') {
    $('.js-nutrition-house-wrapper').show();
    if ($('[name="nutrition_house_payment_type"]:checked').val() === 'purity') {
      $('.js-nutrition-house-split').show();
    } else {
      $('.js-nutrition-house-split').hide().find('input').val('');
    }
  } else {
    $(".js-nutrition-house-wrapper").hide();
    $('[name="nutrition_house_payment_type"][value="vendor"]').prop('checked', true);
  }
}

function updateHealthFirst() {
  if ($('[name="health_first"]:checked').val() === '1') {
    $('.js-health-first-wrapper').show();
    if ($('[name="health_first_payment_type"]:checked').val() === 'purity') {
      $('.js-health-first-split').show();
    } else {
      $('.js-health-first-split').hide().find('input').val('');
    }
  } else {
    $('.js-health-first-wrapper').hide();
    $('[name="health_first_payment_type"][value="vendor"]').prop('checked', true);
  }
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
