const detailsInputs = [
  {
    id: "lastName",
    name: "lastName",
    type: "text",
    label: "Last Name",
    required: true,
  },
  {
    id: "firstName",
    name: "firstName",
    type: "text",
    label: "First Name",
    required: true,
  },
  {
    id: "nationalId",
    name: "nationalId",
    type: "text",
    label: "CNP",
    required: true,
  },
  {
    id: "passportNumber",
    name: "passportNumber",
    type: "text",
    label: "Series and number of ID / Passport",
    required: true,
  },
  {
    id: "email",
    name: "email",
    type: "text",
    label: "Email",
    required: true,
  },
  {
    id: "relationship",
    name: "relationship",
    type: "select",
    label: "The relationship with the inmate",
    options: [
      "First-degree relative",
      "Second-degree relative",
      "Psychologist",
      "Lawyer",
      "Friend",
    ],
    required: true,
  },
];

const renderDialogModal = (id, title, inputs, onAdd, onClose) => {
  const detailsForm = document.createElement("form");
  detailsForm.classList.add("dialog__form");

  const fragment = new DocumentFragment();

  inputs.forEach((element) => {
    if (element.type === "select") {
      const selectWrapper = document.createElement("div");
      selectWrapper.classList.add("select-wrapper");

      const select = document.createElement("select");
      select.id = element.id;
      select.name = element.name;

      const option = document.createElement("option");
      option.value = "";
      option.selected = true;
      option.disabled = true;
      option.hidden = true;
      option.textContent = "Choose an option";

      select.append(option);

      element.options.forEach((option) => {
        const selectOption = document.createElement("option");
        selectOption.value = option;
        selectOption.textContent = option;

        select.append(selectOption);
      });

      selectWrapper.append(select);

      const fieldWrapper = document.createElement("div");
      fieldWrapper.classList.add("field-wrapper");

      const label = document.createElement("label");
      label.textContent = element.label;
      label.htmlFor = element.id;

      const errorParagraph = document.createElement("p");
      errorParagraph.classList.add("error-message");
      errorParagraph.setAttribute("data-error", element.id);

      fieldWrapper.append(label, selectWrapper, errorParagraph);
      fragment.append(fieldWrapper);
      return;
    }

    const fieldWrapper = document.createElement("div");
    fieldWrapper.classList.add("field-wrapper");

    let input, inputLabel;
    input = document.createElement("input");
    inputLabel = document.createElement("label");

    for (const prop of Object.keys(element)) {
      if (prop === "label") {
        inputLabel.textContent = element[prop];
        inputLabel.htmlFor = element["id"];
      } else {
        input[prop] = element[prop];
      }
    }

    const errorParagraph = document.createElement("p");
    errorParagraph.classList.add("error-message");
    errorParagraph.setAttribute("data-error", input.id);

    fieldWrapper.append(inputLabel, input, errorParagraph);
    fragment.append(fieldWrapper);
  });

  let addButton, formFooter;

  if (id === "addVisitor" || id === "editVisitor" || id === "editProfile") {
    let input, inputLabel;
    input = document.createElement("input");
    inputLabel = document.createElement("label");

    input.id = "photo";
    input.name = "photo";
    input.type = "file";
    input.setAttribute("data-parent", id);
    input.accept = "image/jpg, image/jpeg, image/png";

    inputLabel.textContent = "Upload picture";
    inputLabel.htmlFor = "photo";
    inputLabel.setAttribute("data-parent", id);

    addButton = document.createElement("button");

    const fieldWrapper = document.createElement("div");
    fieldWrapper.classList.add("field-wrapper");

    fieldWrapper.append(inputLabel, input);

    formFooter = document.createElement("div");
    formFooter.append(fieldWrapper, addButton);
  } else {
    addButton = document.createElement("button");
    formFooter = document.createElement("div");
    formFooter.append(addButton);
  }

  addButton.classList.add("form-add");
  addButton.textContent = "Add";
  addButton.type = "add";

  formFooter.classList.add("form-footer");

  fragment.append(formFooter);
  detailsForm.append(fragment);

  detailsForm.addEventListener("add", async (event) => {
    event.preventDefault();
    await onAdd();
  });

  const headerWrapper = document.createElement("div");
  headerWrapper.classList.add("header-wrapper");

  const formTitle = document.createElement("h2");
  formTitle.classList.add("form-title");
  formTitle.textContent = title;

  const closeButton = document.createElement("button");
  closeButton.type = "button";
  closeButton.classList.add("form-toggle");

  const bar = document.createElement("span");
  bar.classList.add("bar");

  closeButton.append(bar, bar.cloneNode(true));
  closeButton.addEventListener("click", onClose);

  headerWrapper.append(formTitle, closeButton);

  const dialogWindow = document.createElement("dialog");
  dialogWindow.classList.add("visitor-dialog");

  dialogWindow.append(headerWrapper, detailsForm);
  dialogWindow.id = id;

  document.body.append(dialogWindow);
};

const showDialogModal = (id, title, inputs, onAdd) => {
  renderDialogModal(id, title, inputs, onAdd, () => closeDialog(id));
  if (id === "addVisitor" || id === "editVisitor" || id === "editProfile") {
    const fileInput = document.querySelector(`input[data-parent='${id}']`);
    fileInput.addEventListener("change", () => {
      const file = fileInput.files[0];
      const fileUploadDetails = document.querySelector(
        `label[data-parent='${id}']`
      );
      if (file.name.length > 10) {
        fileUploadDetails.textContent = file.name.slice(0, 10) + "...";
      } else {
        fileUploadDetails.textContent = file.name;
      }
    });
  }
  const dialog = document.querySelector(`#${id}`);
  dialog.showModal();
};

(function () {
  const addButton = document.querySelector("#add-visitor");
  addButton?.addEventListener("click", () => {
    showDialogModal(
      "addVisitor",
      "Enter personal data",
      detailsInputs,
      addVisitorDetails
    );
  });
})();
