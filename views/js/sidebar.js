$('.ham-sec').click(function () {
    $('.sidebar-holder').addClass('sidebar-holder-active');
});
$(document).bind('click mouseup wheel', function (e) {
    var container = $('.sidebar-holder');
    var ham = $('.ham-sec');

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

$('.guest-secondary-a').click(function (e) {
    e.preventDefault();
    throwlert(0, 'You need to create an account to access this feature');
});
