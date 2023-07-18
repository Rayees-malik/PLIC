$(function () {
  $('#app').on('keyup', '.js-promo-search', doSearch);
});

function doSearch() {
  const searchTerm = $(this).val().trim().toLowerCase();
  if (searchTerm.length === 0) {
    $('.js-promo-row').removeClass('row-search');
    $('.js-promo-category').each((_index, element) => {
      if ($(element).data('has-content') || $(element).find('input:visible').filter((_index, element) => element.value).length)
        window.openAcc($(element));
      else
        window.closeAcc($(element));
    });
    return;
  }

  $('.js-promo-category').each((_index, element) => {
    let categoryMatch = false;
    $(element).find('.js-promo-row').each((_index, element) => {
      let productMatch = false;
      $(element).find('.js-search-field').each((_index, element) => {
        productMatch = $(element).text().toLowerCase().includes(searchTerm);
        categoryMatch = categoryMatch || productMatch;
        return !productMatch;
      });
      productMatch ? $(element).addClass('row-search') : $(element).removeClass('row-search');
    });
    categoryMatch ? window.openAcc($(element)) : window.closeAcc($(element));
  });
}