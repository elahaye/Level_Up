"use strict";

document.addEventListener("DOMContentLoaded", function () {
  var headerResponsiveBar = document.querySelector(".headerResponsiveBar");
  var navBar = document.querySelector(".navBar");

  headerResponsiveBar.addEventListener("click", function () {
    navBar.classList.toggle("show");
  });
});
