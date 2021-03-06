//  прокрутка до элемента
$(document).ready(function() { 

    //сперва получаем позицию элемента относительно документа
    if ($('div').is('.buttons__dialog')) {
        var destination = $('.buttons__dialog').offset().top-500;
    }else if ($('div').is('.chat_close')) {
        var destination = $('.chat_close').offset().top-500;
    }else return false;  

    // скроллим страницу на значение равное позиции элемента
    if ( /^((?!chrome|android).)*safari/i.test(navigator.userAgent)) {
        // Это Safari
        $('body').animate({ scrollTop: destination }, 1100); //1100 - скорость
    } else {
        $('html').animate({ scrollTop: destination }, 1100);
    }
    return false;                     
})

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
    $('#order-filter-form').change(function(event) {    
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
                    $('#order-filter-form .register__user span').text(data.data);
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

    // Сброс отдельных полей фильтра ЗАКАЗОВ
        $('#order_reset_category').on('click', function(){ // Категория Услуги. 

            $('#orderfiltrform-category_id').val(""); // сбросили значение поля        
            $('#order-filter-form').change();         // сгенерировали изменение формы
        });

        $('#order_reset_city').on('click', function(){ // Сброс городов.                  
            $('.search-choice-close').click();         // имитируем клик-закрытие
            $('#order-filter-form').change();          // сгенерировали изменение формы
        });
        

        $('#order_reset_work_form').on('click', function(){ // Форма работы.            
            $('#orderfiltrform-work_form_id').val(""); // сбросили значение поля        
            $('#order-filter-form').change();          // сгенерировали изменение формы
        });        
    // Сброс отдельных полей фильтра ЗАКАЗОВ КОНЕЦ    

    // При изменении любого эл-та фильтра ИСПОЛНИТЕЛЕЙ- отправляем данные фильтра 
    // на просчет числа заказов
    $('#exec-filter-form').change(function(event) {    
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
                    $('#exec-filter-form .register__user span').text(data.data);                    
                    $("#exec_list").html(data.orders);
                } else {
                    // Если при обработке данных на сервере произошла ошибка
                    console.log('data='+data.error);                   
                }
        }).fail(function(data) {
            // Если произошла ошибка при отправке запроса
            console.log('error3='+data);            
        })
        // Запрещаем прямую отправку данных из формы
        return false;
    })

    // Сброс отдельных полей фильтра ИСПОЛНИТЕЛЕЙ
        $('#exec_reset_category').on('click', function(){ // Категория Услуги.            
            $('#execfiltrform-category_id').val(""); // сбросили значение поля        
            $('#exec-filter-form').change();         // сгенерировали изменение формы
        });

        $('#exec_reset_city').on('click', function(){ // Сброс городов.                  
            $('.search-choice-close').click();      // имитируем клик-закрытие городов
            $('#exec-filter-form').change();          // сгенерировали изменение формы
        });        

        // $('#order_reset_work_form').on('click', function(){ // Форма работы.            
        //     $('#orderfiltrform-work_form_id').val(""); // сбросили значение поля        
        //     $('#order-filter-form').change();          // сгенерировали изменение формы
        // });        
    // Сброс отдельных полей фильтра ИСПОЛНИТЕЛЕЙ КОНЕЦ 

    // для выпадающего списка Город
    $(document).ready(function(){
        $('.js-chosen.city').chosen({
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
    // $('.slider').not('.slick-initialized').slick({    
      //dots:true,
      centerMode: true,
      centerPadding: '10px',  
      infinite: true,
      slidesToShow: 3,
      slidesToScroll: 1
    });

    // Cлайдер Портфолио исполнителя- Указываем что будем выводить по 3 слайда на экран
    // $('.portfolio-slider').slick({
    $('[class ^= portfolio-slider]').slick({
      //dots:true,
      centerMode: false,
      centerPadding: '10px',  
      infinite: false,
      slidesToShow: 3,
      slidesToScroll: 1
    });


    // управление видом активного меню - остальные сброс в неактивные
    // $('.navbar-nav li a').click(function(event) {
    //     alert("Меню");
    //     $('.navbar-nav li a').each(function(){
    //         $(this).parent().removeClass('active');
    //     });
    //     $(event.target).parent().addClass('active');
    // })
   
})

// для highslider
hs.graphicsDir = '../css/graphics/';
hs.maxWidth = 800;
hs.align = 'center';
//hs.transitions = ['expand','crossfade'];
//hs.fadeInOut = true;

// Показываем загружаемые фотки
function readmultifiles(files, max_photos) {
  // превью нескольких картинок при вводе файлов  
  // max_photos - максим. кол. фоток передаем из order_edit.php 
  // photo_qwt - сколько фоток уже есть
    
  if (files.length >= 1) { //меняем название кнопки button .save__photo
     
      if ($('button').is('.save__photo')) {  // если есть такая кнопка       
         $('button.save__photo').text('Добавить');
      }
  }

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

  //console.log('photo_qwt='+photo_qwt);
     
    // ограничиваемся max_photos файлами
    // определяем сколько фоток еще можно добавить  
        if (files.length<=(max_photos-photo_qwt)) num_files = files.length;
        else num_files=max_photos-photo_qwt;

        console.log("добавляем "+num_files+" файлов");        

    var reader = new FileReader();  

    function readFile(index) {

        //console.log("Index="+index);
        if( (index+1) > num_files ) return;
        
        //console.log(files);
        if (!empty(files[index])) 
            file = files[index];
        else return;
        
        reader.onload = function(e) { 
          
          // ищем свободное место для превью и определяем номер фотки
          for (n=1; n<=max_photos; n++) {
            //console.log("n="+n);
            if (!$("#preview"+n).length) { // длина 0 - место свободно          
              console.log("Есть место-"+n);
              // создаем элемент DOM
              if (add_new_order==0) { // фотки с возможностью удаления
                var image = $("<div class='photo_item'><img id='preview"+n+"' src='' alt='Фото задания'>"+                       
                          "<div class='imgdelete'>"+
                              //"<img src='/web/uploads/images/icon_delete48x48.png' alt='Удалить' title='Удалить фото' onclick='delete_photo()'>"+
                              "<img src='/web/uploads/images/delete_icon_32px.png' alt='Удалить' title='Удалить фото' onclick='delete_photo()'>"+
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

    //var i=1;
    //var index = 0;
    readFile(0);
}

function empty( mixed_var ) {   // Determine whether a variable is empty
    //
    // +   original by: Philippe Baumann
    return ( mixed_var === "" || mixed_var === 0   || mixed_var === "0" || mixed_var === null  || mixed_var === false  ||  ( Array.isArray(mixed_var) && mixed_var.length === 0 ) );
}