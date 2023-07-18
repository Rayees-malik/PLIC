$(function () {

  loadBrands();
  $('.js-pp-stockid').on('change', stockIdChanged);
  $('.js-pp-brand').on('change', brandChanged);
  $('.js-pp-category').on('change', categoryChanged);
  $('.js-pp-product').on('change', productChanged);

  $('.js-pp-stockid').parent().find('.chosen-search-input').autocomplete({
    source: function (request, response) {
      axios.post('/products/search/', { stock_id: request.term, ignore_status: $('.js-ignore-status').length }).then(function (data) {
        if (!data.data || (data.data.length === 0)) return;
        $('.js-pp-stockid').empty();
        response($.map(data.data, function (item) {
          $('.js-pp-stockid').append(`<option value="${item.id}">${item.stock_id} - ${item.name || item.name_fr} (${item.size || 1}${!item.uom ? 'un' : item.uom.unit})</option>`);
        }));
        $('.js-pp-stockid').trigger('chosen:updated');
      });
    }
  });
});

function loadBrands() {
  axios.get(`/brands/search/`).then(function (data) {
    $('.js-pp-brand').empty();

    if (!$('.js-pp-brand').prop('multiple'))
      $('.js-pp-brand').append(`<option>Select Brand</option>`);

    $.map(data.data, function (item) {
      $('.js-pp-brand').append(`<option value="${item.id}">${item.name} (${item.brand_number})</option>`);
    });
    $('.js-pp-brand').trigger('chosen:updated');
  });
}

function loadCategories(brandId) {
  $('.js-pp-brand').prop('disabled', true).trigger('chosen:updated')
  axios.get(`/brands/${brandId}/search/categories`).then(function (data) {
    $('.js-pp-category').empty();

    if (!data.data || (data.data.length === 0)) {
      $('.js-pp-category').append(`<option selected>No categories found</option>`).prop('disabled', true).trigger('chosen:updated');
      $('.js-pp-category-row').show(250);
      $('.js-pp-brand').prop('disabled', false).trigger('chosen:updated')
      return;
    }

    $.map(data.data, function (item) {
      $('.js-pp-category').append(`<option value="${item.id}">${item.name}</option>`);
    });
    $('.js-pp-category').prop('disabled', false).trigger('chosen:updated');
    $('.js-pp-category-row').show(250);
    loadProducts();
  });
}

function loadProducts(categoryId) {
  const searchData = categoryId ? { catalogue_category_id: categoryId } : { brand_id: $('.js-pp-brand').prop('multiple') ? $('.js-pp-brand').val()[0] : $('.js-pp-brand').val() };
  if ($('.js-ignore-status').length) {
    searchData.ignore_status = true;
  }

  axios.post('/products/search/', searchData).then(function (data) {
    $('.js-pp-product').empty();

    if (!data.data || (data.data.length === 0)) {
      $('.js-pp-product').append(`<option selected>No products found</option>`).prop('disabled', true).trigger('chosen:updated');
      $('.js-pp-product-row').show(250);
      $('.js-pp-category').prop('disabled', false).trigger('chosen:updated');
      $('.js-pp-brand').prop('disabled', false).trigger('chosen:updated');
      return;
    }

    $.map(data.data, function (item) {
      $('.js-pp-product').append(`<option value="${item.id}">${item.stock_id} - ${item.name || item.name_fr} (${item.size || 1}${!item.uom ? 'un' : item.uom.unit})</option>`);
    });
    $('.js-pp-product').prop('disabled', false).trigger('chosen:updated');
    $('.js-pp-product-row').show(250);

    $('.js-pp-brand').prop('disabled', false).trigger('chosen:updated')
    $('.js-pp-category').prop('disabled', false).trigger('chosen:updated');
  });
}

function stockIdChanged() {
  const selectedCount = $('.js-pp-stockid').val().length;
  if (selectedCount === 0) {
    $('.js-pp-stockid-button').addClass('disabled-btn').prop('disabled', true);
  } else if (selectedCount === 1) {
    $('.js-pp-stockid-button').removeClass('disabled-btn').prop('disabled', false).text('Add Product');
  } else if (selectedCount === 2) {
    $('.js-pp-stockid-button').text('Add Products');
  }
}

function brandChanged() {
  $('.js-pp-product').empty().prop('disabled', true).trigger('chosen:updated');
  $('.js-pp-category').empty().prop('disabled', true).trigger('chosen:updated');

  const selectedCount = $('.js-pp-brand').prop('multiple') ? $('.js-pp-brand').val().length : 1;
  if (selectedCount === 0) {
    $('.js-pp-brand-button').addClass('disabled-btn').prop('disabled', true).text('Add Line Drive');
    $('.js-pp-category-row').hide(250);
  } else if (selectedCount === 1) {
    $('.js-pp-brand-button').removeClass('disabled-btn').prop('disabled', false).text('Add Line Drive');
    loadCategories($(this).val());
  } else if (selectedCount === 2) {
    $('.js-pp-brand-button').removeClass('disabled-btn').prop('disabled', false).text('Add Line Drives');
    $('.js-pp-category-row').hide(250);
  }
}

function categoryChanged() {
  $('.js-pp-product').empty().prop('disabled', true).trigger('chosen:updated');

  const selectedCount = $('.js-pp-category').val().length;
  if (selectedCount === 0) {
    $('.js-pp-category').prop('disabled', true).trigger('chosen:updated');
    $('.js-pp-category-button').addClass('disabled-btn').prop('disabled', true).text('Add Category');
    loadProducts();
  } else if (selectedCount === 1) {
    $('.js-pp-category').prop('disabled', true).trigger('chosen:updated');
    $('.js-pp-category-button').removeClass('disabled-btn').prop('disabled', false).text('Add Category');
    loadProducts($(this).val());
  } else if (selectedCount === 2) {
    $('.js-pp-category-button').text('Add Categories');
    $('.js-pp-product-row').hide(250);
  }
}

function productChanged() {
  const selectedCount = $('.js-pp-product').val().length;
  if (selectedCount === 0) {
    $('.js-pp-product-button').addClass('disabled-btn').prop('disabled', true);
  } else if (selectedCount === 1) {
    $('.js-pp-product-button').removeClass('disabled-btn').prop('disabled', false).text('Add Product');
  } else if (selectedCount === 2) {
    $('.js-pp-product-button').text('Add Products');
  }
}