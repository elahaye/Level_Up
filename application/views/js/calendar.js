"use strict";

document.addEventListener("DOMContentLoaded", function () {
  /////////////////////////////////////////////////////////////////////////////////////////
  // VARIABLES                                                                           //
  /////////////////////////////////////////////////////////////////////////////////////////

  // Current date of the day
  var date = new Date();
  var month = "" + (date.getMonth() + 1);
  var day = "" + date.getDate();
  var year = date.getFullYear();

  if (month.length < 2) {
    month = "0" + month;
  }
  if (day.length < 2) {
    day = "0" + day;
  }
  date = year + "-" + month + "-" + day;

  // Interact with the tasks section
  var waitingSection = document.getElementById("waitingTasks");
  var doneSection = document.getElementById("doneTasks");
  var failedSection = document.getElementById("failedTasks");
  var importantTask = document.querySelectorAll(".importantTask h4");
  var importantContent = document.querySelectorAll(".importantTask p");
  var importantOptions = document.querySelectorAll(".importantTask ul");

  // Interact with the edit button
  var importantEditButton = document.querySelectorAll(
    ".importantTask li:last-of-type"
  );
  var calendarForm = document.getElementById("calendarForm");
  var titleForm = document.getElementById("title");
  var contentForm = document.getElementById("content");
  var priorityForm = document.getElementById("priority");
  var dateForm = document.getElementById("date");
  var legendForm = document.getElementById("legendForm");
  var submitButton = document.querySelector(".submit");

  // Interact with the budget button
  var budgetSection = document.getElementById("budget");
  var removeMoneyForm = document.getElementById("removeMoneyForm");

  /////////////////////////////////////////////////////////////////////////////////////////
  // CALENDAR                                                                           //
  /////////////////////////////////////////////////////////////////////////////////////////

  // Integration of the calendar and interaction
  var calendarEl = document.getElementById("calendar");
  if (calendarEl) {
    var calendar = new FullCalendar.Calendar(calendarEl, {
      plugins: ["dayGrid", "interaction"],
      locale: "fr",
      height: "auto",
      initialView: "dayGridMonth",
      selectable: true,
      dateClick: function (info) {
        changeDate(info.dateStr); // pickedUp Date
      },
    });
    calendar.render();
  }

  /////////////////////////////////////////////////////////////////////////////////////////
  // FUNCTIONS                                                                           //
  /////////////////////////////////////////////////////////////////////////////////////////

  // When you click on a task
  function taskOnClick(index, content, options) {
    displayOnClick(content[index]);
    displayOnClick(options[index]);
  }

  //The eventListener when you click on a task (repeated twice !)
  function listenerTask(task, content, options) {
    for (let i = 0; i < task.length; i++) {
      task[i].addEventListener("click", function () {
        taskOnClick(i, content, options);
      });
    }
  }

  // The eventListener when you click on the edit button of a task (repeated twice !)
  function listenerEdit(button) {
    for (let i = 0; i < button.length; i++) {
      button[i].addEventListener("click", function () {
        editTask(button, i);
      });
    }
  }

  // When you click on an element it appears or disappears
  function displayOnClick(element) {
    element.classList.toggle("notshow");
  }

  /////////////////////////////////////////////////////////////////////////////////////////
  // AJAX                                                                                //
  /////////////////////////////////////////////////////////////////////////////////////////

  // Change date when you click on the calender
  function changeDate(date) {
    //XMLHttpRequest
    var xhttp = new XMLHttpRequest();
    var request_url = "changeDate";

    xhttp.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        var waitingTasks = JSON.parse(this.response).waitingTasks;
        var doneTasks = JSON.parse(this.response).doneTasks;
        var failedTasks = JSON.parse(this.response).failedTasks;

        if (waitingSection) {
          waitingSection.innerHTML = "";
          doneSection.innerHTML = "";
          failedSection.innerHTML = "";

          if (waitingTasks.length == 0) {
            waitingSection.innerHTML +=
              "<p> Vous n'avez rien de prévu pour ce jour.</p>";
          } else {
            for (let i = 0; i < waitingTasks.length; i++) {
              waitingSection.innerHTML +=
                '<article class="task flex-row">' +
                '<a href="changeStatus&id=' +
                waitingTasks[i].id +
                "&status=failed&priority=" +
                waitingTasks[i].priority +
                '"><i class="fas fa-times"></i></a>' +
                "<div>" +
                "<h4>" +
                waitingTasks[i].title +
                "</h4>" +
                '<p class="notshow">' +
                waitingTasks[i].content +
                "</p>" +
                '<ul class="notshow flex-row">' +
                '<li> <a href="deleteTask&id=' +
                waitingTasks[i].id +
                '"><i class="fas fa-trash-alt"></i></a></li>' +
                '<li data-id="' +
                waitingTasks[i].id +
                '"><i class="fas fa-edit"></i></li>' +
                "</ul>" +
                "</div>" +
                '<a href="changeStatus&id=' +
                waitingTasks[i].id +
                "&status=done&priority=" +
                waitingTasks[i].priority +
                '"><i class="fas fa-check"></i></a>' +
                "</article>";
            }
          }

          if (doneTasks.length !== 0) {
            doneSection.classList.add("border");
            for (let i = 0; i < doneTasks.length; i++) {
              doneSection.innerHTML +=
                '<article class="task flex-row">' +
                "<div>" +
                "<h4>" +
                doneTasks[i].title +
                "</h4>" +
                '<p class="notshow">' +
                doneTasks[i].content +
                "</p>" +
                '<ul class="notshow flex-row"></ul>' +
                "</div>" +
                "</article>";
            }
          } else {
            doneSection.classList.remove("border");
          }
          if (failedTasks.length !== 0) {
            failedSection.classList.add("border");
            for (let i = 0; i < failedTasks.length; i++) {
              failedSection.innerHTML +=
                '<article class="task flex-row">' +
                "<div>" +
                "<h4>" +
                failedTasks[i].title +
                "</h4>" +
                '<p class="notshow">' +
                failedTasks[i].content +
                "</p>" +
                '<ul class="notshow flex-row"></ul>' +
                "</div>" +
                "</article>";
            }
          } else {
            failedSection.classList.remove("border");
          }

          var task = document.querySelectorAll(".task h4");
          var content = document.querySelectorAll(".task p");
          var options = document.querySelectorAll(".task ul");
          var editButton = document.querySelectorAll(".task li:last-of-type");

          listenerTask(task, content, options);
          listenerEdit(editButton);
        }
      }
    };

    xhttp.open("POST", request_url, true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("newDate=" + date);
  }

  // When you click on the edit button of a task
  function editTask(button, index) {
    var taskId = button[index].dataset.id;

    //XMLHttpRequest
    var xhttp = new XMLHttpRequest();
    var request_url = "editTask";

    xhttp.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        var editTask = JSON.parse(this.response);

        calendarForm.action = "calendar&taskId=" + editTask.id;
        titleForm.value = editTask.title;
        contentForm.value = editTask.content;
        priorityForm.options[editTask.priority - 1].selected = true;
        dateForm.value = editTask.date;
        legendForm.textContent = "Modification de la tâche";
        submitButton.textContent = "Modifier";
      }
    };

    xhttp.open("POST", request_url, true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("taskId=" + taskId);
  }

  /////////////////////////////////////////////////////////////////////////////////////////
  // MAIN CODE                                                                           //
  /////////////////////////////////////////////////////////////////////////////////////////

  changeDate(date);
  listenerTask(importantTask, importantContent, importantOptions);
  listenerEdit(importantEditButton);

  if (budgetSection) {
    budgetSection.addEventListener("click", function () {
      displayOnClick(removeMoneyForm);
    });
  }
});
