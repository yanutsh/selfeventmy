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
    	
    // При изменении любого эл-та фильтра ЗАКАЗОВ- отправляем данные фильтра 
    // на просчет числа заказов
    $('#filter-form').change(function(event) {    
    	  //alert ('Change filter-form');
        //event.preventDefault();
        // Получаем объект формы
        var $form = $(this);
        // отправляем данные на сервер
        $.ajax({
            // Метод отправки данных (тип запроса)
            type : $form.attr('method'),
            // URL для отправки запроса
            url : $form.attr('action'),
            // Данные формы
            data : $form.serializeArray()
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
        }).fail(function(data) {
            // Если произошла ошибка при отправке запроса
            console.log('error3='+data);
            //$("#output").text("error3");
        })
        // Запрещаем прямую отправку данных из формы
        return false;
    }) 

    // При изменении любого эл-та фильтра ИСПОЛНИТЕЛЕЙ- отправляем данные фильтра 
    // на просчет числа заказов
    $('#filter-form-exec').change(function(event) {    
      //alert ('Change filter-form-exec');
        //event.preventDefault();
        // Получаем объект формы
        var $form = $(this);
        // отправляем данные на сервер
        $.ajax({
            // Метод отправки данных (тип запроса)
            type : $form.attr('method'),
            // URL для отправки запроса
            url : $form.attr('action'),
            // Данные формы
            data : $form.serializeArray()
        }).done(function(data) {
            if (data.error == null) {
                    // Если ответ сервера успешно получен
                    //console.dir(data);
                    $('#filter-form-exec .register__user span').text(data.data);
                    //$("#output").text(data.data);
                    $("#exec_list").html(data.orders);

                } else {
                    // Если при обработке данных на сервере произошла ошибка
                    console.log('data='+data.error);
                    //$("#output").text(data.error)
                }
        }).fail(function(data) {
            // Если произошла ошибка при отправке запроса
            console.log('error3='+data);
            //$("#output").text("error3");
        })
        // Запрещаем прямую отправку данных из формы
        return false;
    }) 

    // для выпадающего списка Город
    $(document).ready(function(){
        $('.js-chosen').chosen({
            width: '100%',
            no_results_text: 'Совпадений не найдено',
            placeholder_text_single: 'Выберите город',
            placeholder_text_multiple: 'Любой город',
        });
    }); 

    // имитация клика на поле город при клике по gliphicon
    $('.glyphicon.glyphicon-chevron-down').on('click', function(){        
        $('.chosen-choices').trigger('click');
    }); 


    // Слайдер Заказов - Указываем что будем выводить по 3 слайда на экран
    $('.slider').slick({
      //dots:true,
      centerMode: true,
      centerPadding: '10px',  
      infinite: true,
      slidesToShow: 3,
      slidesToScroll: 1
    });

    // Cлайдер Портфолио исполнителя- Указываем что будем выводить по 3 слайда на экран
    $('.portfolio-slider').slick({
      //dots:true,
      centerMode: false,
      centerPadding: '10px',  
      infinite: false,
      slidesToShow: 3,
      slidesToScroll: 1
    });
   
})
 
// управление видом активного меню - остальные сброс в неактивные
$('.navbar-nav li a').click(function(event) {

	$('.navbar-nav li a').each(function(){
        $(this).parent().removeClass('active');
    });
    $(event.target).parent().addClass('active');
 })


// Показываем загружаемые фотки
function readmultifiles(files, max_photos) {
  // превью нескольких картинок при вводе файлов    
  // photo_qwt - сколько фоток уже есть
  // max_photos - максим. кол. фоток передаем из order_edit.php 

  // чистим предыдущие картинки
    var blok = document.querySelector ('.image-preview');
  // удаляем существующие фотки - только для add.php
  
    if (add_new_order==1) {
        while (blok.hasChildNodes()) {
          blok.removeChild(blok.firstChild);
        }        
    }    
  //--------------------

  // определяем количество существующих фоток в задании
  elements = $("[id^='preview']");
  photo_qwt = elements.length;
     
    // ограничиваемся max_photos файлами
    // определяем сколько фоток еще можно добавить  
        if (files.length<=(max_photos-photo_qwt)) num_files = files.length;
        else num_files=max_photos-photo_qwt;

        //console.log("добавляем "+num_files+" файлов");        

    var reader = new FileReader();  

    function readFile(index) {

        console.log("Index="+index);
        if( (index+1) > num_files ) return;
        
        var file = files[index];
        
        reader.onload = function(e) { 
          
          // ищем свободное место дл я превью и определяем номер форки
          for (n=1; n<=max_photos; n++) {
            console.log("n="+n);
            if (!$("#preview"+n).length) { // длина 0 - место свободно          
              console.log("Есть место-"+n);
              // создаем элемент DOM
              if (add_new_order==0) { // фотки с возможностью удаления
                var image = $("<div class='photo_item'><img id='preview"+n+"' src='' alt='Фото задания'>"+                       
                          "<div class='imgdelete'>"+
                              "<img src='img/icon_delete48x48.png' alt='Удалить' title='Удалить фото' onclick='delete_photo()'>"+
                          "</div>"+
                          "</div>");
              }else { // фотки БЕЗ возможности удаления
                var image = $("<div class='photo_item'><img id='preview"+n+"' src='' alt='Фото задания'>"+                       
                              "</div>");
              }

              image.appendTo(blok);              
              $('#preview'+n).attr('src', e.target.result); 
              break;               
            }//else console.log("Нет места");             
                          
          }
          readFile(index+1);                                
        }
        reader.readAsDataURL(file);        
    }

    var i=1;
    readFile(0);
}





