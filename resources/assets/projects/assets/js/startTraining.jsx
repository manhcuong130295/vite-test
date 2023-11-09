$(document).ready(function() {
  $('#save-button').on('click', save);
});

function save() {
    $.LoadingOverlay('show');
    var name = $('#project-name').val();
    var linkContent = $('input[name="link_content[]"]').map(function(){return $(this).val();}).get();
    var contentLink = $('textarea[name="content[]"]').map(function(){return $(this).val();}).get();
    $.ajax({
        type: 'POST',
        url: createProjectUrl,
        data: {
            user_id: user_id,
            name: name,
            link_contents: linkContent,
            content_links: contentLink,
            contents: textInputArray,
            subscription_plan_id: subscriptionPlanId,
            inputFiles: filesInputArray,
            totalDetectChars: totalDetectLength
        },
        success: function(response) {
            $('.toast').addClass('show');
            $('#project-name').val(''); // Sửa lỗi, sử dụng val() để xóa giá trị trong input
            $('#project-content').val(''); // Sửa lỗi, sử dụng val() để xóa giá trị trong input
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

var filesInputArray = [];
var totalFiles = 0;
var totaltextFileLength = 0;
var textInputArray = [];

document.addEventListener("DOMContentLoaded", function () {
    const dropArea = document.getElementById('dropArea');
    const fileInput = document.getElementById('fileInput');
    const fileList = document.getElementById('fileList');

    dropArea.addEventListener('dragover', handleDragOver);
    dropArea.addEventListener('dragleave', handleDragLeave);
    dropArea.addEventListener('drop', handleDrop);

    fileInput.addEventListener('input', handleFileInputChange);

    function handleDragOver(e) {
        e.preventDefault();
        dropArea.classList.add('bg-info', 'text-white');
    }

    function handleDragLeave() {
        dropArea.classList.remove('bg-info', 'text-white');
    }

    function handleDrop(e) {
        e.preventDefault();
        dropArea.classList.remove('bg-info', 'text-white');
        const files = e.dataTransfer.files;
        handleFiles(files);
    }

    function handleFileInputChange() {
        const files = fileInput.files;
        handleFiles(files);
    }

    function handleFiles(files) {
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            if (file.type === 'application/pdf') {
                handlePdfFile(file);
            } else if (file.type === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
                handleWordFile(file);
            } else {
                alert('File type is not supported.');
            }
        }
    }

    function handlePdfFile(file) {
        if (checkExistFile(file, filesInputArray)) {
            alert(`File ${file.name} is already exists`);
            return;
        }

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

            addFileToArray(file, text, file.type);
        };
        reader.readAsArrayBuffer(file);
    }

    function handleWordFile(file) {
        if (!file) {
            reject("No file provided");
            return;
        }

        if (checkExistFile(file, filesInputArray)) {
            alert(`File ${file.name} is already exists`);
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            const content = e.target.result;

            mammoth.extractRawText({ arrayBuffer: content })
                .then(result => {
                    let text = result.value;
                    addFileToArray(file, text, file.type);
                })
                .catch(error => {
                    alert("Error extracting text: " + error);
                });
        };
        reader.readAsArrayBuffer(file);
    }

    function addFileToArray(file, text, type) {
        filesInputArray.push({
            'name': file.name,
            'text': text,
            'type': type
        });
        const fileItem = createFileItemElement(file, text);
        fileList.appendChild(fileItem);
        viewFileCharacter();
    }

    function createFileItemElement(file, text) {
        const fileItem = document.createElement('div');
        fileItem.className = 'alert alert-secondary d-flex justify-content-between align-items-center';

        const fileName = document.createElement('span');
        fileName.textContent = file.name + " (" + text.length + " characters)";

        const deleteButton = document.createElement('i');
        deleteButton.className = 'ti ti-trash fs-6 text-danger cursor-pointer';
        deleteButton.dataset.fileName = file.name;
        deleteButton.addEventListener('click', handleDeleteButtonClick);

        fileItem.appendChild(fileName);
        fileItem.appendChild(deleteButton);

        return fileItem;
    }

    function handleDeleteButtonClick(event) {
        const fileName = event.target.dataset.fileName;
        const fileItem = event.target.parentElement;
        fileItem.remove();
        filesInputArray = filesInputArray.filter(item => item.name !== fileName);
        viewFileCharacter();
    }
});
