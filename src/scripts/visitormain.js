async function addVisitorDetails() {
  clearErrors();
  const inputs = document.querySelectorAll(
    `#addVisitor .dialog__form input, #addVisitor .dialog__form select`
  );
}

function closeDialog(id) {
  const dialog = document.querySelector(`#${id}`);
  dialog.remove();
}