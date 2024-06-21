let clicked = false;
let formData;
let fileInfoAsElem = '';

$('#choose').click(function () {
    $(this)[0].value = null;
});

$('#choose').change(function () {
    fileInfoAsElem = '';
    $('.chat-attachment-wrapper').html(fileInfoAsElem);
    formData = new FormData();

    file = $(this)[0].files[0];
    formData.append('file[]', file);
    fileInfoAsElem += `<div class='chat-attach'>${file.name}</div>`;
    $('.chat-attach-wrapper').html(fileInfoAsElem);
    $('.chat-attachment').css('display', 'flex');
});

function sendImg() {
    $.ajax({
        url: `controllers/usermsghandler.php?msgId=${msg_id}`,
        type: 'POST',
        data: formData,

        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            $('.chat-room').html(data);
            formData = null;
            fileInfoAsElem = '';
            $('.chat-attach-wrapper').html(fileInfoAsElem);
            $('.chat-attachment').css('display', 'none');
            $('.chat-room').scrollTop($('.chat-room')[0].scrollHeight);
        },
    });
}

function sendTxt() {
    let msg = $('#msg-txt-field').val();
    if (msg != '') {
        $.ajax({
            url: 'controllers/usermsghandler.php',
            method: 'POST',
            dataType: 'JSON',
            data: {
                msgTxt: msg,
                msgId: msg_id,
            },
            success: function (data) {
                if ('response' in data) {
                    console.log('new msg from admin');
                    $('.chat-room').html(data.response);
                    $('#msg-txt-field').val('');
                    $('.chat-room').scrollTop($('.chat-room')[0].scrollHeight);
                }
            },
        });
    }
}

$('#sendBtn , #chatform').on('click, submit', function (e) {
    e.preventDefault();
    if (formData == undefined || formData == null) {
        sendTxt();
    } else if (formData != undefined || formData != null) {
        console.log(formData);
        sendImg();
        sendTxt();
    }
});

setInterval(() => {
    $.ajax({
        url: 'controllers/usermsghandler.php',
        method: 'POST',
        dataType: 'JSON',
        data: {
            refresh: msg_id,
        },
        success: function (data) {
            if ('response' in data) {
                $('.chat-room').html(data.response);
                let stp = $('.chat-room').scrollTop();
                let sht = $('.chat-room')[0].scrollHeight;
                let ht = $('.chat-room').height();
                let sp = stp + ht;
                let sht2 = sht - 100;
                // console.log(stp + ht);
                // console.log(sht);
                if (clicked || sp >= sht2) {
                    $('.chat-room').scrollTop($('.chat-room')[0].scrollHeight);
                    clicked = false;
                }
            }
        },
    });
}, 1000);

setInterval(() => {
    $.ajax({
        url: 'controllers/usermsghandler.php',
        method: 'POST',
        dataType: 'JSON',
        data: {
            refreshAttach: msg_id,
        },
        success: function (data) {
            if ('response' in data) {
                // console.log('in');
                $('.chat-side-attach').html(data.response);
            }
        },
    });
}, 1000);

$('#clearattach').click(function () {
    formData = null;
    fileInfoAsElem = '';
    $('.chat-attach-wrapper').html(fileInfoAsElem);
    $('.chat-attachment').css('display', 'none');
});
