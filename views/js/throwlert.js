function throwlert(type, message = '', timer = '') {
    if (type == 1) {
        $('.type-error').css('display', 'none');
    }
    if (type == 0) {
        $('.type-success').css('display', 'none');
    }
    $('.alert-dialog').text(message);
    $('.throwlert').addClass('throwlert-active');
    $('.alert-box').addClass('active-alert-box');
}

$('.alert-close-button button').click(function () {
    $('.throwlert').removeClass('throwlert-active');
    $('.alert-box').removeClass('active-alert-box');
});
