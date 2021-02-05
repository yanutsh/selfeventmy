//ssend_code = null;

$(document).ready(function() {
    
    //console.log ("ssend_code-ready="+ssend_code);

    // управление css кнопок на странице Подтверждения данных  
  if(typeof(ssend_code) != "undefined" && ssend_code !== null) {  
    if (ssend_code=='ok' || ssend_code=='confirm_error' ){   
    // если код подтверждения отправлен или подтвержден с ошибкой
      $('#send_code').attr('disabled','disabled');
      $('#send_code').css('opacity','0.5');
      $("select[name='phone_email']").attr('readonly','readonly');      
      $('.choose_send').css('opacity','0.5');

      $('#confirm_code').removeAttr('disabled');
      $('#confirm_code').css('opacity','1');
      $("input[name='code']").removeAttr('disabled'); 
      $('label.choose_code').css('opacity','1');
      //ssend_code=null;
    }

  }   

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
    $('#send_code').on('click', function() {
        //alert("нажали кнопку Выслать код");
      $('#send_code').submit();   
      $('#send_code').attr('disabled','disabled');
      $('#send_code').css('opacity','0.5');
      $("select[name='phone_email']").attr('readonly','readonly');      
      $('.choose_send').css('opacity','0.5');

      $('#confirm_code').removeAttr('disabled');
      $('#confirm_code').css('opacity','1');
      $("input[name='code']").removeAttr('disabled'); 
      $('label.choose_code').css('opacity','1');
  
    })    
// управление видом кнопок на странице Подтверждения данных


// управление видом кнопок на странице Подтверждения данных Конец

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




