<?php
use yii\helpers\Url;
//<!-- Ввод и редактирование аватара --> 
	//require_once('user_photo.php') ?>

		<div class="change_avatar">		    
			
			<div class="preview_avatar">
				<?php //debug (Url::to([user_photo($_SESSION['avatar'])] )) ?>

		    	<img id="preview_avatar" src="<?= user_photo($_SESSION['avatar']) ?>" alt="Аватар" title="Выберите файл для аватара.">
		    	 <p>Кликните и выберите фотографию для профиля</p>
		    	<div class="form-group">
		         <!--  <label for="avatar"></label> -->	        	
		            <input type="file" name="avatar[]" class="form-control add-avatar"  id="avatar" onchange="ShowAvatar(this.files);"  multiple />
		        </div>	
		        
		    </div>
		   

		    <div class="wrapper wrapper__avatar" id="wrapper">
				<main class="main">
					<div class="main__content">
						<div class="avatar">
							
							<img id="image" class="image">
							<canvas id="canvas" class="canvas">
								Your browser does not support JS or HTML5!
							</canvas>
							<div class="polzunok"></div>
							
						</div>
						
						<div class="cut-btn">
							<button class="button" id="saveBtn" data-avatar-name="<?=$_SESSION['avatar'] ?>">Вырезать</button>			
						</div>										

					</div>
				</main>
			</div> 
		</div>

	<!-- поля ввода параметров рамки - скрываю -->
			
		<!-- <input type="number" name="widthBox" id="widthBox" value="150" min="100" title="Width" hidden>								
		<input type="number" name="heightBox" id="heightBox" value="150" min="100" title="Height" hidden>
	
		<input type="number" name="topBox" id="topBox" value="50" min="0" title="Top" hidden><br><br>
		<input type="number" name="leftBox" id="leftBox" value="50" min="0" title="Left" hidden> -->

		<div id="widthBox" value="150" min="100" title="Width" hidden></div>								
		<div id="heightBox" value="150" min="100" title="Height" hidden></div>
	
		<div type="number" name="topBox" id="topBox" value="50" min="0" title="Top" hidden><br><br></div>
		<div type="number" name="leftBox" id="leftBox" value="50" min="0" title="Left" hidden></div>
			
	<!-- конец поля ввода параметров рамки - скрываю -->
   <!-- </form> -->
   
