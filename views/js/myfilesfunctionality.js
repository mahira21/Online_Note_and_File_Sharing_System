//handling the copy to clipboard method on copy button click
$('.result-row-plate-container').on('click', '.col5 a', function (e) {
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
$('.result-row-plate-container').on('click', '.col7 a', function (e) {
    e.preventDefault();
    $('.semiloader').fadeIn();
    let that = this;
    $.ajax({
        url: 'controllers/deletemodule.php',
        method: 'POST',
        dataType: 'JSON',
        data: {
            delete: 'file',
            fileID: $(this).attr('id'),
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

//sorting row-plates ////this code is not used
$('#sortSel').on('change', function () {
    let miniContainer = $('.mini-container');
    if (this.value == 'bydate') {
        $('.row-plate')
            .sort(sort_li) // sort elements
            .appendTo(miniContainer); // append again to the list
        // sort function callback
        function sort_li(a, b) {
            an = Date.parse($(a).find('.col2').text());
            bn = Date.parse($(b).find('.col2').text());

            return bn < an ? 1 : -1;
        }
    } else if (this.value == 'byname') {
        $('.row-plate')
            .sort(sort_li) // sort elements
            .appendTo(miniContainer); // append again to the list
        // sort function callback
        function sort_li(a, b) {
            console.log('in');
            an = Date.parse($(a).find('.col1').text());
            bn = Date.parse($(b).find('.col1').text());

            return bn < an ? 1 : -1;
        }
    }
});

////radio
$('.result-row-plate-container').on('change', '.col3-inner input', function (
    e
) {
    let privacy;
    let oldprivacy = this.value == 0 ? 1 : 0;
    let that = this;

    let fileID = $(this).parents('.row-plate').attr('id');
    // console.log(fileID);

    if (this.value == 0) {
        privacy = 0;
    } else {
        privacy = 1;
    }
    $.ajax({
        url: 'controllers/updatemodule.php',
        method: 'POST',
        dataType: 'JSON',
        data: {
            update: 'filePrivacy',
            filePrivacy: privacy,
            fileID: fileID,
        },
        success: function (data) {
            if (data.success == 'false') {
                $(`#${fileID}`)
                    .find(`input:radio[value='${oldprivacy}']`)
                    .prop('checked', true);
            }
        },
    });
});

$('.search-row input').keyup(function () {
    $.ajax({
        url: 'controllers/myfileshandler.php',
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
        url: 'controllers/myfileshandler.php',
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
        url: 'controllers/myfileshandler.php',
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
