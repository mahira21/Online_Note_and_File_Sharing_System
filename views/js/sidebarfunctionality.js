function openForm() {
    document.getElementById('disableDiv').style.display = 'block';
    document.getElementById('loginForm').style.display = 'block';
}

function closeForm() {
    document.getElementById('unamebox').value = '';
    document.getElementById('passbox').value = '';
    document.getElementById('errProfile').innerHTML = '';
    document.getElementById('errUname').innerHTML = '';
    document.getElementById('errPass').innerHTML = '';
    $('.input-sec input').removeClass('focus');
    document.getElementById('disableDiv').style.display = 'none';
    document.getElementById('loginForm').style.display = 'none';
}

$('#ham').click(function () {
    $('.sidebar-holder').addClass('sidebar-holder-active');
});

$(document).bind('click mouseup wheel', function (e) {
    var container = $('.sidebar-holder');
    var ham = $('#ham');

    if (
        !container.is(e.target) &&
        !ham.is(e.target) &&
        container.has(e.target).length === 0
    ) {
        container.removeClass('sidebar-holder-active');
    }
});

$('.primary-sidebar-a').click(function (e) {
    e.preventDefault();
    let that = $(this).next('.secondary-sidebar-content');
    if ($(this).next('.secondary-sidebar-content').is(':visible')) {
        $(this).next('.secondary-sidebar-content').slideToggle();
    } else {
        $('.primary-sidebar-a')
            .not(this)
            .each(function () {
                if ($(this).next('.secondary-sidebar-content').is(':visible')) {
                    $(this)
                        .next('.secondary-sidebar-content')
                        .slideUp(function () {
                            that.slideDown();
                        });
                } else {
                    that.slideDown();
                }
            });
    }
});

$('.primary-index-sidebar-a').click(function (e) {
    e.preventDefault();
    let that = $(this).next('.secondary-index-sidebar-content');
    if ($(this).next('.secondary-index-sidebar-content').is(':visible')) {
        $(this).next('.secondary-index-sidebar-content').slideToggle();
    } else {
        $('.primary-index-sidebar-a')
            .not(this)
            .each(function () {
                if (
                    $(this)
                        .next('.secondary-index-sidebar-content')
                        .is(':visible')
                ) {
                    $(this)
                        .next('.secondary-index-sidebar-content')
                        .slideUp(function () {
                            that.slideDown();
                        });
                } else {
                    that.slideDown();
                }
            });
    }
});

$('.secondary-index-sidebar-content #sideLoginBtn').click(function (e) {
    e.preventDefault();
    openForm();
});
$('.secondary-sidebar-content #sideLoginBtn').click(function (e) {
    e.preventDefault();
    openForm();
});

$('.guest-secondary-a').click(function (e) {
    e.preventDefault();
    throwlert(0, 'You need to create an account to access this feature');
});
$('.guest-secondary-index-a').click(function (e) {
    e.preventDefault();
    throwlert(0, 'You need to create an account to access this feature');
});
