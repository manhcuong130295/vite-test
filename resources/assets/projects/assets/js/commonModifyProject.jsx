$(document).ready(function() {
  const tabs = document.querySelectorAll(".nav-link-type");
  const tabContents = document.querySelectorAll(".tab-pane-type");

  tabs.forEach(function(tab, index) {
    tab.addEventListener("click", function() {
        tabContents.forEach(function(content) {
            content.classList.remove("show", "active");
        });
        tabContents[index].classList.add("show", "active");
        tabs.forEach(function(otherTab) {
            otherTab.classList.remove("active");
        });
        tab.classList.add("active");
    });
  });

  let currentTabIndex = 0;
  const vPillsTabType = document.getElementById('v-pills-tabType');
  vPillsTabType.addEventListener("keydown", function(event) {
    if (event.key === "Tab") {
      event.preventDefault();
      const tabs = document.querySelectorAll(".nav-link-type");
      const tabContents = document.querySelectorAll(".tab-pane-type");
      tabContents.forEach(function(content) {
          content.classList.remove("show", "active");
      });
      currentTabIndex = (currentTabIndex + 1) % tabs.length;
      if (currentTabIndex === tabs.length) {
          currentTabIndex = 0;
      }
      tabContents[currentTabIndex].classList.add("show", "active");
      tabs.forEach(function(tab, index) {
          tab.classList.remove("active");
      });
      tabs[currentTabIndex].classList.add("active");
    }
  });

  $('#project-content').on('input', checkLength);
});

var currentLength = 0;
var totalDetectLength = currentLength;
var forms = document.querySelectorAll("#text-form form");

forms.forEach(function(form) {
  var projectContent = form.querySelector(".project-content-input").value;
  currentLength+= projectContent.length;
});

$('#total-text-chars').text(currentLength > 0 ? ReplaceNumberWithCommas(currentLength + " Text input chars") : '');

function checkLength() {
  var totalDetectCrawl = $('#total_detect').html();
  if (totalDetectCrawl && totalDetectCrawl.toString().includes(',')) {
      totalDetectCrawl = totalDetectCrawl.replace(/,/g, '');
  }
  totalDetectCrawl = parseInt(totalDetectCrawl, 10);
  if(isNaN(totalDetectCrawl)) {
      totalDetectCrawl = 0;
  }

  var totalDetectCrawl = parseInt(totalDetectCrawl);

  currentLength = textInputArray.length ? textInputArray.reduce((sum, item) => sum + item.content.length, 0) : 0;
  totalPages = textInputArray.length;
  totalDetectLength = currentLength + totaltextFileLength + totalDetectCrawl;
  $('#total-text-chars').text( totalPages > 0
    ? `${ReplaceNumberWithCommas(totalPages)} Pages text input (${ReplaceNumberWithCommas(currentLength)} chars)`
    : '' );
  $('#total-length').text(ReplaceNumberWithCommas(totalDetectLength));

  if (totalDetectLength >= maxLength) {
    $('#total-text-chars').addClass('text-danger');
    $('#total-length').addClass('text-danger');
    $('#total-file-chars').addClass('text-danger');
    $('#total-link-chars').addClass('text-danger');
    $('.modify-project-btn').attr('disabled', true);
  } else {
    $('#total-text-chars').removeClass('text-danger');
    $('#total-length').removeClass('text-danger');
    $('#total-file-chars').removeClass('text-danger');
    $('#total-link-chars').removeClass('text-danger');
    $('.modify-project-btn').attr('disabled', false);
  }
}
function ReplaceNumberWithCommas(yourNumber) {
  //Seperates the components of the numbers
  var n= yourNumber.toString().split(".");
  //Comma-fies the first part
  n[0] = n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  //Combines the two sections
  return n.join(".");
}

function viewFileCharacter() {
  if (filesInputArray.length) {
    totalFiles= filesInputArray.length;
    totaltextFileLength = filesInputArray.reduce((sum, item) => sum + item.text.length, 0);
    $('#total-file-chars').text( `${totalFiles} Files (${totaltextFileLength} chars)` );
  } else {
    totaltextFileLength = 0;
    $('#total-file-chars').text("");
  }
  checkLength();
}

function checkExistFile(file, files) {
  if (files.length > 0) {
    for (var i = 0; i < files.length; i++) {
      if (files[i].name === file.name) {
        return true; // Exist
      }
    }
    return false; // Not Exist
  }
}

function addForm() {
  // Clone the form
  var formText = document.getElementById("text-form");
  var form = document.querySelector("#text-form .form-modify");

  var formContentClone = document.createElement('div');
  formContentClone.className ='form-content';

  var alertDiv = document.querySelector('#text-form .detail-content');
  var alertClone = alertDiv.cloneNode(true);
  alertClone.querySelector('span').textContent = "Let create content for page to training.";
  alertClone.querySelector('.ti-caret-right-filled').className = 'ti ti-caret-right-filled fs-6 cusor-pointer d-none';
  alertClone.querySelector('.ti-caret-up-filled').className = 'ti ti-caret-up-filled fs-6 cusor-pointer';

  var formClone = form.cloneNode(true);
  formClone.className = "form-modify";
  // Clear input values in the cloned form
  formClone.querySelectorAll("input[type='text'], textarea").forEach(function(input) {
      input.value = "";
  });

  // Append the cloned form
  formContentClone.appendChild(alertClone);
  formContentClone.appendChild(formClone);

  formText.appendChild(formContentClone);
  // Show the remove button in all forms
  showRemoveButtons();
}

function removeForm(button) {
  var form = button.closest(".form-modify");
  var formContent  = form.parentNode;
  var index = Array.from(formContent.parentNode.children).indexOf(formContent);
  if (index > -1) {
    // Remove the form from the DOM
    form.parentNode.remove();
    // Remove the corresponding element from the arrayInput
    textInputArray.splice(index, 1);
    if (textInputArray[index] && textInputArray[index].id) {
      textContentDeleteIds.push(textInputArray[index].id);
    }
    checkLength();
  }
  // Check if there is only one form left, and if so, hide the remove button
  if (document.querySelectorAll("#text-form .form-modify").length === 1) {
    var removeButtons = document.querySelectorAll("#text-form .btn-danger");
    removeButtons.forEach(function (removeButton) {
        removeButton.style.display = "none";
    });
  }
}

showRemoveButtons();

function showRemoveButtons() {
  var forms = document.querySelectorAll("#text-form .form-modify");

  var removeButtons = document.querySelectorAll("#text-form .btn-danger");
  if (forms.length > 1) {
      removeButtons.forEach(function(button) {
          button.style.display = "block";
      });
  } else {
      removeButtons.forEach(function(button) {
          button.style.display = "none";
      });
  }
}

function updateArrayInput(inputElement) {
  var form = inputElement.closest(".form-modify");
  var alertSpan = form.parentNode.querySelector('.detail-content').querySelector('span');
  var formContent  = form.parentNode;

  var index = Array.from(formContent.parentNode.children).indexOf(formContent); // Locate the form in the list
  if (index < textInputArray.length) {
    // Update the corresponding value in the Input array.
    if (inputElement.classList.contains("page-name-input")) {
        textInputArray[index].page_name = inputElement.value;
    } else if (inputElement.classList.contains("project-content-input")) {
        textInputArray[index].content = inputElement.value;
    }

    // Check if both input cells are empty
    if (form.querySelector(".page-name-input").value.trim() === "" && form.querySelector(".project-content-input").value.trim() === "") {
      if (textInputArray[index] && textInputArray[index].id) {
        textInputArray.splice(index, 1);
      }
    }

  } else {
      // Add new Input page.
      var newInput = {
          page_name: "",
          content: ""
      };
      if (inputElement.classList.contains("page-name-input")) {
          newInput.page_name = inputElement.value;
      } else if (inputElement.classList.contains("project-content-input")) {
          newInput.content = inputElement.value;
      }
      textInputArray.push(newInput);
  }
  alertSpan.textContent = textInputArray[index].page_name + ` (${textInputArray[index].content ? textInputArray[index].content.length: 0}  characters)`;
  checkLength();
}

function viewContentValidateMsgs(errors) {
  errors.forEach(function(error, index) {
    var form = document.querySelectorAll("#text-form .form-modify")[index];
    var errorName = form.querySelector(".error-page-name");
    var errorContent = form.querySelector(".error-page-content");
    errorName.textContent = error.name
    errorContent.textContent = error.content;
  });
}

$(document).on('click', 'i[name="show-form"]', function () {
  var form = $(this).closest('div').next('.form-modify');
  form.removeClass('d-none');
  $(this).addClass('d-none');
  $(this).next('i').removeClass('d-none');
});

$(document).on('click', 'i[name="hide-form"]', function () {
  var form = $(this).closest('div').next('.form-modify');
  form.addClass('d-none');
  $(this).addClass('d-none');
  $(this).prev('i').removeClass('d-none');
});

