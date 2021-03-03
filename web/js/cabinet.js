$(document).ready(function() {
    // Предотвращение отправки формы по ENTER
    // $("input").keydown(function(event){
    //   if(event.keyCode == 13){
    //       //$( document.activeElement ).next().focus(); 
    //       event.preventDefault();          
    //       return false;
    //    }
    // });


    // сброс фильтра заказов и вывод всех заказов
    $('#reset').click(function(event) {    
        //alert ('Reset');
        $(this).closest('form')[0].reset();
        event.preventDefault();
        // Получаем объект формы
        //var $testform = $(this);
        // отправляем данные на сервер
        $.ajax({
            // Метод отправки данных (тип запроса)
            type : 'post',
            // URL для отправки запроса
            url : '/cabinet/index',
            // Данные формы
            data : {'data':'reset'}
        }).done(function(data) {
                if (data.error == null) {
                    // Если ответ сервера успешно получен
                    //console.dir(data);
                    $('#filter-form .register__user span').text(data.data);
                    //$("#output").text(data.data);
                    $("#orders_list").html(data.orders);

                } else {
                    // Если при обработке данных на сервере произошла ошибка
                    console.log('data='+data.error);
                    //$("#output").text(data.error)
                }
        }).fail(function() {
            // Если произошла ошибка при отправке запроса
            console.log('error3='+data.error);
            //$("#output").text("error3");
        })
        // Запрещаем прямую отправку данных из формы
        return false;
    })
    	
    // При изменении любого эл-та фильтра - отправляем данные фильтра 
    // на просчет числа заказов
    $('#filter-form').change(function(event) {    
    	//alert ('Change');
        //event.preventDefault();
        // Получаем объект формы
        var $testform = $(this);
        // отправляем данные на сервер
        $.ajax({
            // Метод отправки данных (тип запроса)
            type : $testform.attr('method'),
            // URL для отправки запроса
            url : $testform.attr('action'),
            // Данные формы
            data : $testform.serializeArray()
        }).done(function(data) {
        		if (data.error == null) {
                    // Если ответ сервера успешно получен
                    //console.dir(data);
                    $('#filter-form .register__user span').text(data.data);
                    //$("#output").text(data.data);
                    $("#orders_list").html(data.orders);

                } else {
                    // Если при обработке данных на сервере произошла ошибка
                    console.log('data='+data.error);
                    //$("#output").text(data.error)
                }
        }).fail(function() {
            // Если произошла ошибка при отправке запроса
            console.log('error3='+data.error);
            //$("#output").text("error3");
        })
        // Запрещаем прямую отправку данных из формы
        return false;
    })
 	
})

// управление видом активного меню - остальные сброс в неактивные
$('.navbar-nav li a').click(function(event) {

	$('.navbar-nav li a').each(function(){
        $(this).parent().removeClass('active');
    });
    $(event.target).parent().addClass('active');
 })


