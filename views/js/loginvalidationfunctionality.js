$('.input-sec input').on('focus', function () {
    $(this).addClass('focus');
});
$('.input-sec input').on('blur', function () {
    if ($(this).val() == '') {
        $(this).removeClass('focus');
    }
});

$('#login_form').submit(function (e) {
    e.preventDefault();
    let uname = $('#unamebox').val();
    let pass = $('#passbox').val();
    let everythingOk = true;
    if (uname.trim() == '') {
        $('#errUname').html('username can not be empty');
        everythingOk = false;
    } else {
        $('#errUname').html('');
        // everythingOk = true;
    }
    if (pass.trim() == '') {
        $('#errPass').html('password can not be empty');
        everythingOk = false;
    } else {
        $('#errPass').html('');
        // everythingOk = true;
    }

    if (everythingOk) {
        $.ajax({
            url: 'controllers/loginvalidationhandler.php',
            method: 'POST',
            dataType: 'JSON',
            data: {
                login: 'submit',
                uname: uname,
                pass: pass,
            },
            success: function (data) {
                $('#errProfile').html(data.errProfile);
                $('#errUname').html(data.errUname);
                $('#errPass').html(data.errPass);
                if ('loggedAdmin' in data) {
                    loggedAdmin = data.loggedAdmin;
                    window.location.href = 'dashboard';
                } else if ('loggedUser' in data) {
                    loggedUser = data.loggedUser;
                    window.location.href = './';
                    $('.guest-index-sidebar-contents').css('display', 'none');
                    $('.user-index-sidebar-contents').css('display', 'flex');

                    $('.guest-sidebar-contents').css('display', 'none');
                    $('.user-sidebar-contents').css('display', 'flex');
                    closeForm();
                }
            },
        });
    }
});

if ($('#unamebox').val() != '') {
    $(this).addClass('focus');
}
if ($('#passbox').val() != '') {
    $(this).addClass('focus');
}
