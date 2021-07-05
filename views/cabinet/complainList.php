<?php 
use app\assets\TemplateAsset;
use app\assets\RegistrationAsset;

TemplateAsset::register($this);
//CabinetAsset::register($this);
RegistrationAsset::register($this);

?>

<!-- Жалобы -->
<div class="wrapper__addorder wrapper__addorder__card">
	<div class="lk__main lk__main__exec">
		<div class="refer" id="show_user_reviews">Смотреть все</div>
		<p class="subtitle">Жалобы</p>
		<p class="text">
			<!-- Жалоб оставлено -->
			Всего Жалоб оставлено - <?= count($complains)?>
		</p>
		<div class="review_list complains">
			<?php foreach($complains as $r) { ?>
				<div class="review_header">
					<p class="from">От кого</p>
					<p class="for">На кого</p>
					<div class="members">
						<div class="from_user">
							<img src="<?= user_photo($r['fromUser']['avatar'])?>" alt="">
							
							<div class="fio_text">
								<div class="fio">
									<?= $r['fromUser']['username']?>
								</div>
								<div class="stars">Рейтинг - <?= round($r['rating1'],1);?></div>
							</div>
						</div>
						<div class="for_user">
							<img src="<?= user_photo($r['forUser']['avatar'])?>" alt="">
							
							<div class="fio_text">
								<div class="fio">
									<?= $r['forUser']['username']?>
								</div>
								<div class="stars">Рейтинг - <?= round($r['rating2'],1);?></div>
							</div>
						</div>
					</div>		           								.
				</div>

				<div class="review_details">
					<p><?php $dt=convert_datetime_en_ru($r['complain_date']);
                    echo $dt['dmYHi'] ?></p>
					<p><?= $r['complain'] ?></p>
				</div>
			<?php } ?>
		</div>	
	</div>
</div>	 