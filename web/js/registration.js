//Управление на странице Регистрация - regcust

$(document).ready(function() {
    
// управление видом кнопок Вход и Регистрация	
  $('.buttons a.register').addClass('active');
  $('.buttons a.enter').removeClass('active');

  // ввод и изменение аватара
  $('.b-change_avatar').click(function(){
      //alert("b-change_avatar---");
    $('.change_avatar').css('display','block');
    $('.preview_avatar').css('display','block');
    $('.wrapper.wrapper__avatar').css('display','table');

    //$('.wrapper').css('display','none');
  });

  // управление ползунком аватара
  $( ".polzunok" ).slider({
        animate: "slow",
        //srange: "min",    
        value: 50,
        max: 100,
        min: 20,
        // orientation: "vertical" 
        orientation: "horizontal", 
  });
    
});

 function ShowAvatar (files) {
  //чтение и превье аватара 
   
    var reader = new FileReader(); 
    readFile(0); 
   
     function readFile(index) {
     
        // ограничиваемся 1-ю файлами
        var file = files[index];

        // запоминаем в DOM выбранный файл
         console.dir(file['name']);
         $('#saveBtn').attr('data-avatar-name',file['name']);
        
        //console.dir(file['name']);
        reader.onload = function(e) {
            
            // показываем фото
            $('#image').attr('src', e.target.result);  // для обрезки изображения              
                                
        }
        //reader.readAsBinaryString(file);
        reader.readAsDataURL(file);                
    }
    // отправка данных формы (исходное фото)
    // ничего не делаем если files пустой
    if( typeof files == 'undefined' ) return;

    // создадим объект данных формы
    var data = new FormData();

    // заполняем объект данных файлами в подходящем для отправки формате
    $.each( files, function( key, value ){
      data.append( key, value );
    });     
         
    $('.preview_avatar').css('display','none');
    $(".wrapper.wrapper__avatar").css({"opacity":"0","display":"table"}).show().animate({opacity:1},2000);
    $('#avatar').prop('value', null);  // сбрасываем выбранное фото в input 
    
    $.ajax({
      url         : 'page/regcust',
      type        : 'POST', 
      data        :  data,
      cache       :  false,
      // отключаем обработку передаваемых данных, пусть передаются как есть
      processData : false,
      // отключаем установку заголовка типа запроса. Так jQuery скажет серверу что это строковой запрос
      contentType : false, 
      // функция успешного ответа сервера
      success     : function(msg){   // возвращает ориентацию фото.
        //alert('Ajax - success - msg='+msg);
        //if (!msg=="")  $('#image').attr('data-orientation', msg);
          //console.log ("Ориентация:"+msg);  
        },
      error: function(msg) { // Данные не отправлены
        console.log ("Ошибка загрузки tmp_photo-"+msg); 
        //console.dir (msg);           
        }
    });  

}