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

// ввод файла        
    // $("#input-44").fileinput({
    // 	uploadUrl:  'loadimg/uploadimg',
    //     //uploadUrl: '/uploads',  
    //     showPreview: true,
    //     maxFileSize: 10000,    
    //     maxFilePreviewSize: 10240,
    //     allowedFileExtensions: ["jpg", "jpeg", "gif", "png"],
    //     deleteUrl: "/site/file-delete",
    // });
// ввод файла КОНЕЦ  

// показ пароля
  $('body').on('click', '.password-control1', function(){     
    if ($('#regcustform-password').attr('type') == 'password'){
      $(this).addClass('view');
      $('#regcustform-password').attr('type', 'text');
    } else {
      $(this).removeClass('view');
      $('#regcustform-password').attr('type', 'password');
    }
    return false;
  });

  $('body').on('click', '.password-control2', function(){      
    if ($('#regcustform-password_repeat').attr('type') == 'password'){
      $(this).addClass('view');
      $('#regcustform-password_repeat').attr('type', 'text');
    } else {
      $(this).removeClass('view');
      $('#regcustform-password_repeat').attr('type', 'password');
    }
    return false;
  });
// показ пароля КОНЕЦ  


    $('.chkbox').click(function(){
        //alert("Check");
      var val=$(this).val();
      if ($(this).is(':checked')){    
        // alert('Value='+val);
        $('#wt'+val+ ' input:checkbox').prop('checked', true);
      } else {
        $('#wt'+val+ ' input:checkbox').prop('checked', false);
      }
    });


});


