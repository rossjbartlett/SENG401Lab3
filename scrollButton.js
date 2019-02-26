$(document).ready(function () {
    //make back to top button appear 
    $(window).scroll(function () {
        if ($(this).scrollTop()>1000) {
            $('#scrollButton').fadeIn();
        } else {
            $('#scrollButton').fadeOut();
        }
    });
    //back to top button
    $("#scrollButton").click(function () {
        //1 second of animation time
        $("html, body").animate({ scrollTop: 0 }, 1000);
    });
});