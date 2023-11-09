$(document).ready(function() {
  $('#update-button').on('click', update);
});

function update() {
    $.LoadingOverlay('show');
    var name = $('#project-name').val();
    var linkContent = $('input[name="link_content[]"]').map(function(){return $(this).val();}).get();
    var contentLink = $('textarea[name="content[]"]').map(function(){return $(this).val();}).get();

    $.ajax({
        type: 'PUT',
        url: updateUrl,
        data: {
            name: name,
            link_contents: linkContent,
            content_links: contentLink,
            contents: textInputArray,
            subscription_plan_id: subscriptionPlanId,
            inputFiles: filesInputArray.length > 0 ? filesInputArray : [],
            totalDetectChars: totalDetectLength,
        },
        success: function(response) {
            $('.toast').addClass('show');
            $('#project-name').text('');
            $('#project-content').text('');
            window.location.href = projectIndexUrl;
        },
        error: function(error) {
            error.responseJSON.meta.errors && error.responseJSON.meta.errors.name ?
                $('#error-name').text(error.responseJSON.meta.errors.name[0]) :
                '';
            error.responseJSON.meta.errors && error.responseJSON.meta.errors.contents ?
                $('#error-contents-text').text(error.responseJSON.meta.errors.contents[0]) :
                '';
            error.responseJSON.meta.errors && error.responseJSON.meta.errors.inputFiles ?
                $('#error-contents-file').text(error.responseJSON.meta.errors.inputFiles[0]) :
                '';
            error.responseJSON.meta.errors && error.responseJSON.meta.errors.link_contents ?
                $('#error-contents-link').text(error.responseJSON.meta.errors.link_contents[0]) :
                '';
            error.responseJSON.meta.errors && error.responseJSON.meta.errors.totalDetectChars ?
                $('#total-detect-chars-error').text(error.responseJSON.meta.errors.totalDetectChars[0]) :
                '';
            $.LoadingOverlay('hide');
       }
    });
}

var filesInputArray = files;
var totalFiles= files.length;
var totaltextFileLength = files.reduce((sum, item) => sum + item.text.length, 0);
// var fileDeleteIds = [];
var textInputArray = [];
// var textContentDeleteIds = [];

textContents.forEach((item) => textInputArray.push({
  id: item.id,
  page_name: item.name,
  content: item.text
}));

viewFileCharacter();

//Handle file update.
document.addEventListener("DOMContentLoaded", function () {
    const dropArea = document.getElementById('dropArea');
    const fileInput = document.getElementById('fileInput');
    const fileList = document.getElementById('fileList');

    dropArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropArea.classList.add('bg-info', 'text-white');
    });

    dropArea.addEventListener('dragleave', () => {
        dropArea.classList.remove('bg-info', 'text-white');
    });

    dropArea.addEventListener('drop', (e) => {
        e.preventDefault();
        dropArea.classList.remove('bg-info', 'text-white');
        const files = e.dataTransfer.files;
        handleFiles(files);
    });

    fileInput.addEventListener('input', () => {
        const files = fileInput.files;
        handleFiles(files);
    });

    function handleFiles(files) {
      for (let i = 0; i < files.length; i++) {
        const fileItem = document.createElement('div');
        fileItem.className = 'alert alert-secondary d-flex justify-content-between align-items-center';

        const fileName = document.createElement('span');
        var file = files[i];

        //Read file PDF
        if (file.type === 'application/pdf') {
          if (checkExistFile(file, filesInputArray)) {
            alert(`File ${file.name} is already exists`);
          } else {
            const reader = new FileReader();
            reader.onload = async function () {
              const typedArray = new Uint8Array(reader.result);
              const pdf = await pdfjsLib.getDocument({ data: typedArray }).promise;
              const totalPages = pdf.numPages;
              let text = "";

              for (let pageNumber = 1; pageNumber <= totalPages; pageNumber++) {
                const page = await pdf.getPage(pageNumber);
                const textContent = await page.getTextContent();
                text += textContent.items.map(item => item.str).join(" ");
              }

              filesInputArray.push({
                'name': file.name,
                'text': text,
                'type': file.type
              });

              fileName.textContent = files[i].name + " (" + text.length  + " characters)";
              viewFileCharacter();
            };
            reader.readAsArrayBuffer(file);
            fileItem.appendChild(fileName) ;

            const deleteButton = document.createElement('i');
            deleteButton.className = 'ti ti-trash fs-6 text-danger cusor-pointer';
            deleteButton.dataset.fileName = files[i].name
            deleteButton.addEventListener('click', (event) => {
                const fileName = event.target.dataset.fileName;
                fileItem.remove();
                filesInputArray = filesInputArray.filter(function (item) {
                  return item.name !== fileName;
                });
                viewFileCharacter();
            });
            fileItem.appendChild(deleteButton);
            fileList.appendChild(fileItem);
          }

        } else if (file.type === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
            if (!file) {
              reject("No file provided");
              return;
            }
            if (checkExistFile(file, filesInputArray)) {
              alert(`File ${file.name} is already exists`);
            } else {
              const reader = new FileReader();

              reader.onload = function (e) {
                const content = e.target.result;

              mammoth.extractRawText({ arrayBuffer: content })
                .then(result => {
                    let text = result.value;
                    filesInputArray.push({
                      'name': file.name,
                      'text': text,
                      'type': file.type
                    });
                    viewFileCharacter();
                    fileName.textContent = files[i].name + " (" + text.length  + " characters)";
                })
                .catch(error => {
                    alert("Error extracting text: " + error);
                });
              };
              reader.readAsArrayBuffer(file);
              fileItem.appendChild(fileName) ;

              const deleteButton = document.createElement('i');
              deleteButton.className = 'ti ti-trash fs-6 text-danger cusor-pointer';
              deleteButton.dataset.fileName = files[i].name
              deleteButton.addEventListener('click', (event) => {
                  const fileName = event.target.dataset.fileName;
                  fileItem.remove();
                  filesInputArray = filesInputArray.filter(function (item) {
                    return item.name !== fileName;
                  });
                  viewFileCharacter();
              });
              fileItem.appendChild(deleteButton);
              fileList.appendChild(fileItem);
            }
        } else {
            alert('File type is not supported.');
        }
      }
    }
});

$(document).on('click', 'i[name="delete-file"]', function() {
  var fileName = $(this).data('file-name');
  if (fileName) {
    filesInputArray = filesInputArray.filter(function (item) {
      return item.name !== fileName;
    });

    $(this).closest('div.alert').filter(function() {
      return $(this).data('file-name') === fileName;
    }).remove();
    viewFileCharacter();
  }
})


