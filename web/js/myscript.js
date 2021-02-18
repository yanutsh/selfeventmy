//ssend_code = null;

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
// управление видом кнопок Вход и Регистрация Конец




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
    if ($('#regform-password').attr('type') == 'password'){
      $(this).addClass('view');
      $('#regform-password').attr('type', 'text');
    } else {
      $(this).removeClass('view');
      $('#regform-password').attr('type', 'password');
    }
    return false;
  });

  $('body').on('click', '.password-control2', function(){      
    if ($('#regform-password_repeat').attr('type') == 'password'){
      $(this).addClass('view');
      $('#regform-password_repeat').attr('type', 'text');
    } else {
      $(this).removeClass('view');
      $('#regform-password_repeat').attr('type', 'password');
    }
    return false;
  });
// показ пароля КОНЕЦ 

// показ Кода подтверждениея    
  $('body').on('click', '.password-control3', function(){      
    if ($('#code').attr('type') == 'password'){
      $(this).addClass('view');
      $('#code').attr('type', 'text');
    } else {
      $(this).removeClass('view');
      $('#code').attr('type', 'password');
    }
    return false;
  }); 
// показ Кода подтверждениея КОНЕЦ   


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




