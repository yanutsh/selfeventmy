$(document).ready(function() {

 	
})	

$('.navbar-nav li a').click(function(event) {

	$('.navbar-nav li a').each(function(){
        $(this).parent().removeClass('active');
    });
    

 		//alert('Меню');
 		console.log(event.target);
 		console.log($(event.target).text());
 		$(event.target).parent().addClass('active');
 		

 })