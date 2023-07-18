$(function () {
  $('#app').on('change', '.js-delete-row', toggleDeleteButton);
  $('#app').on('change', '.js-delete-header', function () { $('.js-delete-row').prop('checked', $(this).is(':checked')); toggleDeleteButton(); });

  $('#app').on('click', '.js-category-add', addCategoryRow);
  $('#app').on('change', '.js-sort', reorderCategories);
});

function toggleDeleteButton() {
  $('.js-delete-row:checked').length ? $('.js-category-delete').show(250) : $('.js-category-delete').hide(250);
}

window.deleteCategoryRows = function () {
  $deletedIds = $('.js-delete-input');
  const ids = $deletedIds.val().split(',');
  $('.js-delete-row:checked').each(function () {
    $tr = $(this).closest('tr');
    if ($tr.hasClass('js-category-template-row')) return;

    const id = $tr.find('.js-id').val();
    if (id) ids.push(id);

    $tr.remove();
  });
  $deletedIds.val(ids.filter(n => n).join(','));
  reorderCategories();
  $('.js-category-delete').hide(250);
}

function addCategoryRow() {
  $row = $('.js-category-template-row').clone();

  $row.find('.js-template-delete-row').removeClass('js-template-delete-row').addClass('js-delete-row');
  $row.find('input').removeAttr('disabled');
  $row.find('.js-sort').val('');
  $row.removeClass('js-category-template-row').insertBefore('.js-category-template-row').show();
  reorderCategories();
}

function reorderCategories() {
  $table = $('.js-category-table');
  $rows = $table.find('tr:not(.js-header-row)');

  $rows.sort(function (a, b) {
    const aSort = $(a).find('.js-sort').val();
    const bSort = $(b).find('.js-sort').val();

    return (aSort ? aSort : 1000) - (bSort ? bSort : 1000);
  });

  $.each($rows, function (_index, row) {
    $table.append(row);
  });
}