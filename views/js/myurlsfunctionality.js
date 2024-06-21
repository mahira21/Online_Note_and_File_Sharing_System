//handling the copy to clipboard method on copy button click
$('.result-row-plate-container').on('click', '.col4 a', function (e) {
    e.preventDefault();
    let urlForClip = $(this)
        .parent()
        .parent()
        .siblings('.row3')
        .find('a')
        .text();

    var temp = $('<input>');
    $('body').append(temp);
    temp.val(urlForClip).select();
    document.execCommand('copy');
    temp.remove();
    throwlert(1, 'URL copied');
});

//handling delete button functionlity
$('.result-row-plate-container').on('click', '.col5 a', function (e) {
    e.preventDefault();
    $('.semiloader').fadeIn();
    let that = this;
    // console.log($(this).attr('id'));
    $.ajax({
        url: 'controllers/deletemodule.php',
        method: 'POST',
        dataType: 'JSON',
        data: {
            delete: 'url',
            surl: $(this).attr('id'),
        },
        success: function (data) {
            $('.semiloader').fadeOut(function () {
                if (data.success == 'true') {
                    $(that).parents('.row-plate').fadeOut(500);
                }
            });
        },
    });
});

$('.search-row input').keyup(function () {
    $.ajax({
        url: 'controllers/myurlshandler.php',
        method: 'POST',
        data: {
            searchKeyword: $('.search-row input').val(),
        },
        success: function (data) {
            $('.result-row-plate-container').html(data);
        },
    });
});

$('.result-row-plate-container').on('click', '#newer', function (e) {
    e.preventDefault();
    $('.semiloader').fadeIn();

    let that = $(this);

    $.ajax({
        url: 'controllers/myurlshandler.php',
        method: 'POST',
        data: {
            searchKeyword: $('.search-row input').val(),
            p: $(this).data('p'),
        },
        success: function (data) {
            $('.result-row-plate-container').html(data);
            $('.semiloader').fadeOut();
        },
    });
});
$('.result-row-plate-container').on('click', '#older', function (e) {
    e.preventDefault();
    $('.semiloader').fadeIn();

    let that = $(this);

    $.ajax({
        url: 'controllers/myurlshandler.php',
        method: 'POST',
        data: {
            searchKeyword: $('.search-row input').val(),
            p: $(this).data('p'),
        },
        success: function (data) {
            $('.result-row-plate-container').html(data);
            $('.semiloader').fadeOut();
        },
    });
});
