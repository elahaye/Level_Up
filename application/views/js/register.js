"use strict";

document.addEventListener("DOMContentLoaded", loadComplete);

function loadComplete() {
  var register = document.getElementById("register");

  var form = [
    {
      key: document.getElementById("firstname"),
      selector: document.querySelector("#formFields li:first-child p"),
      value: "prénom",
    },
    {
      key: document.getElementById("lastname"),
      selector: document.querySelector("#formFields li:nth-child(2) p"),
      value: "nom",
    },
    {
      key: document.getElementById("nickname"),
      selector: document.querySelector("#formFields li:nth-child(3) p"),
      value: "pseudonyme",
    },
    {
      key: document.getElementById("phone"),
      selector: document.querySelector("#formFields li:nth-child(4) p"),
      value: "téléphone",
    },
    {
      key: document.getElementById("address"),
      selector: document.querySelector("#formFields li:nth-child(6) p"),
      value: "adresse",
    },
    {
      key: document.getElementById("postCode"),
      selector: document.querySelector("#formFields li:nth-child(7) p"),
      value: "code postal",
    },
    {
      key: document.getElementById("city"),
      selector: document.querySelector("#formFields li:last-child p"),
      value: "ville",
    },
    {
      key: document.getElementById("mail"),
      selector: document.querySelector("#connexionFields li:first-child p"),
      value: "mail",
    },
    {
      key: document.getElementById("password"),
      selector: document.querySelector("#connexionFields li:last-child p"),
      value: "mot de passe",
    },
  ];

  var keyboardResult;

  /////////////////////////////////////////////////////////////////////////////////////////
  // FONCTIONS                                                                           //
  /////////////////////////////////////////////////////////////////////////////////////////

  function validateForm(event) {
    function verifyField(name, tag, field) {
      if (tag.value.length == 0) {
        field.innerHTML = "Veuillez rentrer votre " + name;
        tag.classList.add("red");
        event.preventDefault();
      } else {
        field.innerHTML = "";
        tag.classList.remove("red");
      }
    }

    function verifyLengthField(name, tag, field, number) {
      if (tag.value.length != number) {
        field.innerHTML =
          "Le champ '" + name + "' doit comporter " + number + " caractères.";
        tag.classList.add("red");
        event.preventDefault();
      } else {
        field.innerHTML = "";
        tag.classList.remove("red");
      }
    }

    function verifyLengthPassword(name, tag, field) {
      if (tag.value.length < 8) {
        field.innerHTML =
          "Le champ '" + name + "' doit comporter au minimum 8 caractères.";
        tag.classList.add("red");
        event.preventDefault();
      } else {
        field.innerHTML = "";
        tag.classList.remove("red");
      }
    }

    for (let i = 0; i < form.length; i++) {
      verifyField(form[i].value, form[i].key, form[i].selector);
    }

    verifyLengthField(form[3].value, form[3].key, form[3].selector, 10); // PHONE
    verifyLengthField(form[5].value, form[5].key, form[5].selector, 5); // POSTCODE
    verifyLengthPassword(form[8].value, form[8].key, form[8].selector); // PASSWORD
  }

  /////////////////////////////////////////////////////////////////////////////////////////
  // AJAX                                                                                //
  /////////////////////////////////////////////////////////////////////////////////////////

  // Validation Nickname

  function nicknameOnKeyUp() {
    var keyboardResult = form[2].key.value;

    //XMLHttpRequest
    var xhttp = new XMLHttpRequest();
    var request_url = "nicknameExists";

    xhttp.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        var nicknameKey = JSON.parse(this.response);

        if (nicknameKey == true) {
          form[2].selector.innerHTML =
            "Ce pseudonyme est déjà utilisé, veuillez en sélectionner un autre.";
          form[2].key.classList.add("red");
        } else {
          form[2].selector.innerHTML = "";
          form[2].key.classList.remove("red");
        }
      }
    };

    xhttp.open("POST", request_url, true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("key=" + keyboardResult);
  }

  // Validation Email

  function emailOnKeyUp() {
    var keyboardResult = form[7].key.value;

    //XMLHttpRequest
    var xhttp = new XMLHttpRequest();
    var request_url = "emailExists";

    xhttp.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        console.log(this.response);
        var emailKey = JSON.parse(this.response);

        if (emailKey == true) {
          form[7].selector.innerHTML =
            "Ce mail est déjà utilisé, veuillez en sélectionner un autre.";
          form[7].key.classList.add("red");
        } else {
          form[7].selector.innerHTML = "";
          form[7].key.classList.remove("red");
        }
      }
    };

    xhttp.open("POST", request_url, true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("key=" + keyboardResult);
  }

  /////////////////////////////////////////////////////////////////////////////////////////
  // CODE PRINCIPAL                                                                      //
  /////////////////////////////////////////////////////////////////////////////////////////

  if (register) {
    register.addEventListener("submit", validateForm);
  }
  if (form[2] && form[2].key) {
    form[2].key.addEventListener("keyup", nicknameOnKeyUp); // nickname
  }
  if (form[7] && form[7].key) {
    form[7].key.addEventListener("keyup", emailOnKeyUp); // email
  }
}
