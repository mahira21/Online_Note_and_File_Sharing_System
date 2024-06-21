$('.top-li').mouseenter(function () {
    $(this).find('ul').slideDown();
});
$('.top-li').mouseleave(function () {
    $(this).find('ul').css('display', 'none');
});
$('.nav').mouseleave(function () {
    $('.top-li ul').css('display', 'none');
});
