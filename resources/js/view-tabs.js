$(function () {
  $("#app").on("click", ".tab-btn", selectTab);
});

function selectTab() {
  const currentTab = $('.tab-selected');
  const currenTabName = currentTab.attr('name');
  $(`.${currenTabName}-tab`).hide();

  const newTab = $(this);
  const newTabName = newTab.attr('name');
  $(`.${newTabName}-tab`).show();

  currentTab.removeClass('tab-selected');
  newTab.addClass('tab-selected');
}
