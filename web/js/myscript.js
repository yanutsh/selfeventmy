$(document).ready(function() {

// управление видом кнопок Вход и Регистрация	
	$('.buttons a.register').on('click', function() {
        //alert("Кликнули");
        $('.buttons a.register').addClass('active');
        $('.buttons a.enter').removeClass('active');
    })

    $('.buttons a.enter').on('click', function() {
        //alert("Кликнули");
        $('.buttons a.enter').addClass('active');
        $('.buttons a.register').removeClass('active');
    })
// управление видом кнопок Вход и Регистрация К

        
    // $("#input-44").fileinput({
    // 	uploadUrl:  'loadimg/uploadimg',
    //     //uploadUrl: '/uploads',  
    //     showPreview: true,
    //     maxFileSize: 10000,    
    //     maxFilePreviewSize: 10240,
    //     allowedFileExtensions: ["jpg", "jpeg", "gif", "png"],
    //     deleteUrl: "/site/file-delete",
    // });
});
