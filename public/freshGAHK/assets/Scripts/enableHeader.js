$(document).ready(function () {

    $(window).scroll(function () {
        var scroll = $(window).scrollTop();
        if (scroll !== 0) {
            $(".following.bar").addClass("light");
            $(".ui.inverted.menu .item > a:not(.ui)").addClass("light");
        } else {
            $(".following.bar").removeClass("light");
            $(".ui.inverted.menu .item > a:not(.ui)").removeClass("light");
        }
    });

    $(".blurring .image").dimmer({
        on: 'hover'
    });

});
