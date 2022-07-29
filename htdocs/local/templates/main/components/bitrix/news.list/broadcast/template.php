<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die;
}

$this->setFrameMode(true);

$rand = mt_rand(0, 3);

$this->SetViewTarget('news_broadcast');?>
<div class="content-wrapper content-wrapper--mobile">
	<div class="header-slider-wrap">
		<div class="hheader-slider--circle"></div>
		<div class="header-slider">
			<div class="header-slider__inner">
				<?php foreach ($arResult['ITEMS'] as $index => $arItem): ?>
					<div class="owl-carousel b-header-slide<?=$rand == $index ? ' show' : ''?>" data-news-content="<?=$index?>">
						<div class="header-news__img">
							<div class="header-news__inner">
								<img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" width="933" height="500" alt="">
								<div class="header-news__info">
									<div class="header-news__date">
										<span>
											<?=FormatDate('j F H:i', MakeTimeStamp($arItem['PROPERTIES']['BROADCAST_DATE_TIME']['VALUE']))?>
										</span>
									</div>
									<div class="header-news__text"><?=$arItem['~NAME']?></div>
								</div>
							</div>
							<div class="header-slider-text">
								<div class="header-slider__title"><?=$arItem['~NAME']?></div>
								<div class="header-slider__subtitle"><?=$arItem['~PREVIEW_TEXT']?></div>
								<div class="header-slider__text">Новый выпуск «Умницы и Умники» на Первом канале.</div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>
<?php
$this->EndViewTarget();
?>
<div class="b__info">
	<div class="b-info__list">
		<?php foreach ($arResult['ITEMS'] as $index => $arItem): ?>
			<a class="b-info__item<?=$rand == $index ? ' active' : ''?>" href="#" data-news="<?=$index?>">
				<div class="b-info__item-inner">
					<div class="b-info__title"><?=htmlspecialchars($arItem['NAME'])?></div>
					<div class="b-info__subttile"><?=htmlspecialchars($arItem['~PREVIEW_TEXT'])?></div>
				</div>
			</a>
		<?php endforeach; ?>
	</div>
</div>
