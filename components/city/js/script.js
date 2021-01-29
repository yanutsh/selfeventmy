$(function () {
    $('body').on('click', '.city-select-submit, .city-list a', function () {
        if ($(this).is('a'))
            city = $(this).text();
        else
            city = $('.input-city').val();
        $.post('/ajax/set-city/', 'city=' + city, function () {
            $.pjax.reload('#user-city', {cache: false, async: false});
            $.magnificPopup.close();
            $('#user-city .popup-inline').magnificPopup({
                type: 'inline'
            });
        });
        return false;
    });
});