$(function () {

    var top_show = 300;
    var delay = 500;
//    var menuShow = 120;
//    var fxMenuShow = 320;
    $(window).scroll(function () {
        if ($(this).scrollTop() > top_show)
            $("#top").fadeIn();
        else
            $("#top").fadeOut();
//        var footerY = +$('footer').position().top;
//        if ($(this).scrollTop() > +footerY - 10 - $('.float-menu').height())
//            $('.float-menu').css('position', 'absolute').css('top', (+footerY - $('.float-menu').height()) + 'px');
//        else if ($(this).scrollTop() > menuShow)
//            $('.float-menu').css('position', 'fixed').css('top', '70px');
//        else
//            $('.float-menu').css('position', 'absolute').css('top', '190px');
//        if ($(this).scrollTop() > fxMenuShow)
//            $(".fixed-menu").slideDown();
//        else
//            $(".fixed-menu").slideUp();
    });

    $("#top").click(function () {
        $("body, html").animate({
            scrollTop: 0
        }, delay);
    });

    $('.checkbox input[type=checkbox]').change(function () {
        $(this).parent().toggleClass('checked');
    });

    $('[data-back]').each(function () {
        $(this).css({backgroundImage: 'url(' + $(this).data('back') + ')'});
    });
    
     $('.slider-nav').on('click', 'a', function () {
        var slider = $(this).parents('.slider-nav').prev('.slider');
        var w = $(slider).find('li').first().width()+15;
        var cnt = $(slider).find('li').length;
        var vis = parseInt($(slider).width()/w);
        var cur = parseInt($(slider).find('ul').css('margin-left'));
        if ($(this).hasClass('prev')&&cur<0)
        {
            $(slider).find('ul').animate({marginLeft: (+cur+w)+'px'}, 300);
        }
        if ($(this).hasClass('next')&&cur>-(+cnt-vis-1)*w)
        {
            $(slider).find('ul').animate({marginLeft: (+cur-w)+'px'}, 300);
        }
        return false;
    });

});
