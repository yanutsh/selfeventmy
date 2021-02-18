//Управление на странице Подтверждение данных
$(document).ready(function() {
    
// управление видом кнопок Вход и Регистрация	
  $('.buttons a.register').addClass('active');
  $('.buttons a.enter').removeClass('active');
  //alert("Страница ПереЗагружена"); 
});

// после возвращения данных по PJAX
$(document).on('pjax:success', function() {

	//alert ("PJAX перезагружен");
	// управление видом кнопок на странице Подтверждения данных 
	// после отправки Тел/Email
		$('#send_code').attr('disabled','disabled');
		$('#send_code').css('opacity','0.5');
		$("select[name='phone_email']").attr('readonly','readonly');      
		$('.choose_send').css('opacity','0.5');


		$('#confirm_code').removeAttr('disabled');
		$('#confirm_code').css('opacity','1');
		$("input[name='code']").removeAttr('disabled'); 
		$('label.choose_code').css('opacity','1');		
	// управление видом кнопок на странице Подтверждения данных Конец
	$('.resend').css('display', 'block');
	runCountdown(timer); // запуск обратного отсчета
})

/**
* Set timer countdown in seconds with callback
 */ 
function runCountdown(timer=60){	 
	console.log ('timer='+timer);
	$('#countdown-1').timeTo( 
	    timer,             
	    function(){
	        //alert('Countdown finished');
	        $('.resend').css('display', 'none');
	        $('#send_code').removeAttr('disabled');
			$('#send_code').css('opacity','1');
			$("select[name='phone_email']").attr('readonly','');      
			$('.choose_send').css('opacity','1');
	    },
	    {          
	    displayHours: false,
	    //displayCaptions: true,
	    fontSize: 12,
	    //captionSize: 14,
	    //lang: 'ru', 
	    // step: function() {

	    //   console.log('События каждые 3 сек. ' );
	    // },
	    //stepCount: 3
	    }    
	);

	$('#reset-1').click(function() {
	    $('#countdown-1').timeTo('reset');
	});
}




