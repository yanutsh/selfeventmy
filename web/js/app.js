// считываем положение ползунка
var polzunok=50; // начальное значение ползунка
var first_time=1; // первый раз запускается скрипт

$( ".polzunok" ).slider({
	slide: function( event, ui ) { 
		polzunok = ui.value;
		Init();
    }
});	

//Холст и его контекст
const canvas = document.getElementById("canvas");
const wrapper = document.getElementById("wrapper");
const ctx = canvas.getContext("2d");

//Поля ввода
const widthBox = document.getElementById("widthBox");    	// 150
const heightBox =document.getElementById("heightBox");		// 150
const topBox =  document.getElementById("topBox");			//  50
const leftBox = document.getElementById("leftBox");			//	50

//Кнопка сохранения
const saveBtn = document.getElementById("saveBtn");
const saveIcon = document.getElementById("saveIcon");
//Ссылка на новое изображение
const newImg = document.getElementById("newImg");


//widthBox.addEventListener("change", function () { ChangeBoxes(); });
//heightBox.addEventListener("change", function () { ChangeBoxes(); });
topBox.addEventListener("change", function () { ChangeBoxes(); });
leftBox.addEventListener("change", function () { ChangeBoxes(); });

// если есть кнопка saveBtn
if ($('.button').is('#saveBtn')){
	saveBtn.addEventListener("click", function () { 
		event.preventDefault(); 
		
		// если захотим сохранять со старым именем
		
		old_name = $('#saveBtn').attr('data-avatar-name');
		console.log("old_name="+old_name);
		Save(old_name); 		
	});
}

// если есть кнопка saveIcon
if ($('.button').is('#saveIcon')){
	saveIcon.addEventListener("click", function () { 
		event.preventDefault();
		
		// если захотим сохранять со старым именем
		old_name = $('#saveIcon').attr('data-icon-name');
		console.log("old_name="+old_name);
		SaveIcon(old_name); 
	});
}	

canvas.addEventListener("mousedown", function (e) { MouseDown(e); });
canvas.addEventListener("mousemove", function (e) { MouseMove(e); });
//document.addEventListener("mousemove", function (e) { MouseMove(e); });
document.addEventListener("mouseup", function (e) { MouseUp(e); });

//canvas.addEventListener("mouseover", function (e) { MouseOver(e); });

canvas.addEventListener('touchmove', function (e) { MouseMove(e); });
canvas.addEventListener('touchstart', function (e) { MouseDown(e); });
canvas.addEventListener('touchend', function (e) { MouseUp(e); });

var selection = 
{
	mDown: false,
	x: 0,
	y: 0,
	top: 50,
	left: 50,
	width: 150,
	height: 150
};

const image = document.getElementById("image");

image.addEventListener("load", function () { Init(); });

//image.src = "images/photo.jpg";

window.addEventListener("resize", function () { Init(); });


//}

function Init()
{
	//$('body').css('overflow','hidden'); 
	
	canvas.width = image.width;
	canvas.height = image.height;

	square=Math.min(image.width*polzunok/100, image.height*polzunok/100);

	selection.width= square;  //image.width/3;
	selection.height=square;  // image.width/3;

	// начальное положение бокса
	if (first_time==1) {
		selection.top = 0.5*image.height-0.5*selection.height;
		selection.left = 0.5*image.width-0.5*selection.width;
		first_time=0;
	}	

	canvas.setAttribute("style", "top: " + (image.offsetTop + 5) + "px; left: " + (image.offsetLeft + 5) + "px;");

	// leftBox.setAttribute("max", image.width - 100);
	// topBox.setAttribute("max", image.height - 100);
	
	
	//widthBox.setAttribute("max", image.width);
	//heightBox.setAttribute("max", image.height);

	DrawSelection();
}


function MouseDown(e)
{	
	
	selection.mDown = true;
	$('body').css('overflow','hidden');
}


function MouseMove(e)
{   
	if(selection.mDown)
	{
		if ((e.clientX)&&(e.clientY)) {
		    posX = e.clientX;
		    posY = e.clientY;
		} else if (e.targetTouches) 
		{
		    posX = e.targetTouches[0].clientX;
		    posY = e.targetTouches[0].clientY;
		    //e.preventDefault();
		}

		//определяем на сколька прокручена страница
		var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
		
		selection.x = posX - $('#image').offset().left;   //canvas.offsetLeft;
		selection.y = posY - $('#wrapper').offset().top;  //wrapper.offsetTop;

		selection.left = selection.x - selection.width / 2;
		selection.top = selection.y - selection.height / 2 + scrollTop;

		CheckSelection();

		Update();
	}
}

function MouseUp(e)
{	
	selection.mDown = false;
	$('body').css('overflow','visible');
}


function Update()
{
	UpdateBoxes();
	DrawSelection();
}

function DrawSelection()
{
	ctx.fillStyle = "rgba(0, 0, 0, 0.7)";

	ctx.clearRect(0, 0, canvas.width, canvas.height);

	ctx.fillRect(0, 0, canvas.width, canvas.height);

	ctx.clearRect(selection.left, selection.top, selection.width, selection.height);

	ctx.strokeStyle = "#fff";

	ctx.beginPath();

	ctx.moveTo(selection.left, 0);
	ctx.lineTo(selection.left, canvas.height);

	ctx.moveTo((selection.left + selection.width), 0);
	ctx.lineTo((selection.left + selection.width), canvas.height);

	ctx.moveTo(0, selection.top);
	ctx.lineTo(canvas.width, selection.top);

	ctx.moveTo(0, (selection.top + selection.height));
	ctx.lineTo(canvas.width, (selection.top + selection.height));

	ctx.stroke();
}

function ChangeBoxes()
{
	//selection.width = Math.round(widthBox.value);
	//selection.height = Math.round(heightBox.value);
	selection.top = Math.round(topBox.value);
	selection.left = Math.round(leftBox.value);

	CheckSelection();
	Update();
}

function CheckSelection()
{
	if(selection.width < 50)
	{
		selection.width = 50;
	}

	if(selection.height < 50)
	{
		selection.height = 50;
	}

	if(selection.width > canvas.width)
	{
		selection.width = canvas.width;
	}

	if(selection.height > canvas.height)
	{
		selection.height = canvas.height;
	}

	if(selection.left < 0)
	{
		selection.left = 0;
	}

	if(selection.top < 0)
	{
		selection.top = 0;
	}

	if(selection.left > canvas.width - selection.width)
	{
		selection.left = canvas.width - selection.width;
	}

	if(selection.top > canvas.height - selection.height)
	{
		selection.top = canvas.height - selection.height;
	}
}

function UpdateBoxes()
{
	//widthBox.value = Math.round(selection.width);
	//heightBox.value = Math.round(selection.height);
	topBox.value = Math.round(selection.top);
	leftBox.value = Math.round(selection.left);
}

function Save(old_name)
{    
	// считываем данные ориентации фотографии
	orient=$('#image').attr('data-orientation');	
	
	var params = "width=" + selection.width + "&height=" + selection.width + "&top=" + 
		topBox.value + "&left=" + leftBox.value + "&cw=" + canvas.width + "&ch=" +
		canvas.height +"&photo_name="+old_name+"&orient="+orient+"&noCache=" + 
		(new Date().getTime()) + Math.random();
	
	$.ajax({
      url         : '/page/editavatar',  //editor.php',
      type        : 'GET', 
      data        :  params,
      cache       :  false,
      success     : function(msg){   // возвращает имя файла
        
        if (msg != "" ) // возвращает имя файла с расширением
			 {	
			 	//alert("msg="+msg);
				$('#preview_avatar').attr('src','/web/uploads/images/users/'+ msg);		      

				$('div.preview_avatar').css('display','block');
				//$('div.preview_avatar').animate({display:'block'},{duration: 3000});
				$('.wrapper.wrapper__avatar').css('display','none');
				$('img.user_photo').attr('src','/web/uploads/images/users/'+ msg);  //old_name);
				
				//console.log("msg2: " + msg);
			}
			else
			{
				alert("Ошибка- "+xhr.responseText);
			}
        },
      error: function(msg) { // Данные не отправлены
        console.log ("Ошибка сохранения конечного аватара-"+msg);
        }
    }); 
}     

function SaveIcon(old_name)
{    
	//console.log (old_name);
	// считываем данные ориентации фотографии
	orient=$('#image').attr('data-orientation');
		
	var params = "width=" + selection.width + "&height=" + selection.width + "&top=" + 
		topBox.value + "&left=" + leftBox.value + "&cw=" + canvas.width + "&ch=" +
		canvas.height +"&photo_name="+old_name+"&orient="+orient+"&noCache=" + 
		(new Date().getTime()) + Math.random();

    //console.log (params);
	//$.ajaxSetup({cache: false});
	$.ajax({
      url         : 'editor_icon.php',
      type        : 'GET', 
      data        :  params,
      cache       :  false,
      success     : function(msg){   // возвращает имя файла
        
        if (msg != "" ) // возвращает имя файла с расширением
			 {	
				$('#preview_avatar').attr('src','img/'+ msg);		      

				$('div.preview_avatar').css('display','block');
				//$('div.preview_avatar').animate({display:'block'},{duration: 3000});
				$('.wrapper').css('display','none');
				$('img.user_photo').attr('src','img/'+ msg);  //old_name);
				
				//console.log("msg2: " + msg);
			}
			else
			{
				alert("Ошибка- "+xhr.responseText);
			}
        },
      error: function(msg) { // Данные не отправлены
        console.log ("Ошибка сохранения конечного аватара-"+msg);
        }
    });
}


	
