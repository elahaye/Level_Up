"use strict";

document.addEventListener("DOMContentLoaded", function () {
  /////////////////////////////////////////////////////////////////////////////////////////
  // TRUMBOWYG SCRIPT                                                                       //
  /////////////////////////////////////////////////////////////////////////////////////////

  tinymce.init({
    selector: "#contentArticle",
    fontsize_formats: "12px 14px 16px 18px 24px",
    font_formats:
      "EB Garamond=EB Garamond; Arial=arial,helvetica,sans-serif; Courier New=courier new,courier,monospace",
    language: "fr_FR",
    statusbar: false,
    menubar: false,
    toolbar:
      "undo redo | bold italic underline | alignleft aligncenter alignright | fontsizeselect | fontselect | forecolor",
  });

  /////////////////////////////////////////////////////////////////////////////////////////
  // VARIABLES                                                                           //
  /////////////////////////////////////////////////////////////////////////////////////////

  var editUser = document.querySelectorAll("#listUsers .change-status");
  var formUser = document.querySelectorAll("#listUsers .form-user");
  var deleteUser = document.querySelectorAll("#listUsers .delete-user");
  var deleteArticle = document.querySelectorAll(
    "#listArticles .delete-article"
  );

  /////////////////////////////////////////////////////////////////////////////////////////
  // FUNCTIONS                                                                           //
  /////////////////////////////////////////////////////////////////////////////////////////

  function editUserOnClick(index, event) {
    let confirmation = confirm(
      "Souhaitez-vous modifier le status de cet utilisateur?"
    );
    if (!confirmation) {
      event.preventDefault();
    } else {
      formUser[index].classList.remove("notshow");
    }
  }
  function deleteOnClick(event) {
    let confirmation = confirm("Confirmez-vous la suppression ?");
    if (!confirmation) {
      event.preventDefault();
    }
  }

  /////////////////////////////////////////////////////////////////////////////////////////
  // AJAX                                                                                //
  /////////////////////////////////////////////////////////////////////////////////////////

  /////////////////////////////////////////////////////////////////////////////////////////
  // MAIN CODE                                                                           //
  /////////////////////////////////////////////////////////////////////////////////////////

  for (let i = 0; i < editUser.length; i++) {
    editUser[i].addEventListener("click", function () {
      editUserOnClick(i);
    });
  }
  for (let i = 0; i < deleteUser.length; i++) {
    deleteUser[i].addEventListener("click", deleteOnClick);
  }
  for (let i = 0; i < deleteArticle.length; i++) {
    deleteArticle[i].addEventListener("click", deleteOnClick);
  }
});
