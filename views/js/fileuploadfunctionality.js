let formData,
    fileList,
    totalFiles = 0,
    totalSize = 0;
let fileInfo = [];
let info = [];
let fileInfoAsElem = '';
$('#choose').click(function () {
    $(this)[0].value = null;
});

$('#choose').change(function () {
    fileInfoAsElem = '';
    $('.row4').html(fileInfoAsElem);
    formData = new FormData();
    fileList = $(this)[0].files;
    totalFiles = fileList.length;

    for (const file of fileList) {
        totalSize = totalSize + file.size;
        formData.append('file[]', file);
        fileInfo.push(file.name);
        fileInfoAsElem += `<div>${file.name}</div>`;
    }
    totalSize = Math.ceil(totalSize / 1024);

    if (totalSize > 1024 * 10) {
        throwlert(0, 'You can not upload file more than 10MB at a time');

        formData = null;
        fileInfo = [];
        fileInfoAsElem = '';
        return;
    }

    $('.row4').html(fileInfoAsElem);
    return;

    // console.log(formData);
});

$('.row2').on('dragover', function () {
    $(this).addClass('row2-drag');
    return false;
});
$('.row2').on('dragleave', function () {
    $(this).removeClass('row2-drag');
    return false;
});
$('.row2').on('drop', function (e) {
    e.preventDefault();
    $(this).removeClass('row2-drag');

    fileInfoAsElem = '';
    $('.row4').html(fileInfoAsElem);
    formData = new FormData();
    fileList = e.originalEvent.dataTransfer.files;
    totalFiles = fileList.length;

    for (const file of fileList) {
        totalSize = totalSize + file.size;
        formData.append('file[]', file);
        fileInfo.push(file.name);
        fileInfoAsElem += `<div>${file.name}</div>`;
    }
    totalSize = Math.ceil(totalSize / 1024);
    // console.log(totalSize);
    // console.log(fileInfo);
    if (totalSize > 1024 * 10) {
        throwlert(0, 'You can not upload file more than 10MB at a time');

        formData = null;
        fileInfo = [];
        fileInfoAsElem = '';
        return;
    }

    $('.row4').html(fileInfoAsElem);
    return;

    // console.log(formData);
});

$('#uploadButton').click(function () {
    if (formData == null || formData == undefined) {
        throwlert(0, 'Choose Files First');

        return;
    } else {
        $('#prog').show();
        if (totalFiles > 0 && totalSize <= 1024 * 10) {
            $.ajax({
                url: 'controllers/filehandler.php',
                type: 'POST',
                data: formData,

                // Tell jQuery not to process data or worry about content-type
                // must* include
                cache: false,
                contentType: false,
                processData: false,

                // Custom XMLHttpRequest
                xhr: function () {
                    var myXhr = $.ajaxSettings.xhr();
                    if (myXhr.upload) {
                        // For handling the progress of the upload
                        myXhr.upload.addEventListener(
                            'progress',
                            function (e) {
                                if (e.lengthComputable) {
                                    $('progress').attr({
                                        value: e.loaded,
                                        max: e.total,
                                    });
                                }
                            },
                            false
                        );
                    }
                    return myXhr;
                },
                success: function (data) {
                    $('.res').html(data);
                    $('#prog').fadeOut();

                    formData = null;
                    fileInfo = [];
                    fileInfoAsElem = '';
                    $('.row4').html(fileInfoAsElem);
                },
            });
        } else {
            $('#prog').fadeOut();

            throwlert(0, 'You can not upload file more than 10MB at a time');

            formData = null;
            fileInfo = [];
            fileInfoAsElem = '';
            $('.res').html(fileInfoAsElem);
            return;
        }
    }
});
