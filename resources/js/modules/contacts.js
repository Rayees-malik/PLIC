let addContactIndex = 0;
$(function () {
  $("#app").on("click", ".js-add-contact", addContact);
  $("#app").on("click", ".js-delete-contact", deleteContact);
});

function addContact() {
  const role = $(this).data("role");

  const $newContact = $(".js-contact-wrap-new")
    .clone()
    .removeClass("js-contact-wrap-new")
    .addClass(`js-contact-wrap-${role}`);

  $newContact.find("input:disabled").removeAttr("disabled");
  $newContact.find('[name="contact-role[new]"').val(role);
  $newContact.find('.js-delete-contact').data("role", role);
  $newContact.find('[name$="[new]"]').each(function () {
    this.name = this.name.replace("[new]", `[new${addContactIndex}]`);
  });
  $newContact.appendTo($(`.js-contact-role-${role}`));
  addContactIndex++;
}

function deleteContact() {
  const role = $(this).data('role');
  const index = $(this).data('index');
  const id = $(this).data('id');

  const $parentWrap = $(this).closest(`.js-contact-wrap-${role}`)

  const $deleted = $(`[name="contact-deleted[${index}]"]`).first();
  if (!$deleted.length) return;

  if ($deleted.val() == 1) {
    return doRestore($(this), $deleted, $parentWrap);
  }
  doDelete($(this), $deleted, $parentWrap);
}

function doDelete($button, $deleted, $parentWrap) {
  $deleted.val(1);
  $button.html($button.html().replace('delete_forever', 'restore_from_trash').replace('Delete', 'Restore')).removeClass('delete');
  $parentWrap.addClass('pending-delete');
  $parentWrap.find(':input').attr('readonly', true);
}

function doRestore($button, $deleted, $parentWrap) {
  $deleted.val(0);
  $button.html($button.html().replace('restore_from_trash', 'delete_forever').replace('Restore', 'Delete')).addClass('delete');
  $parentWrap.removeClass('pending-delete');
  $parentWrap.find(':input').attr('readonly', false);
}
