<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die;
}

$this->setFrameMode(true);
?>
<div class="b-news">
	<div class="b-news__top"> 
		<div class="b-news__title">Новости</div>
		<div class="b-news__top-link">
			<a href="<?=preg_replace('/^\/\/+/', '/', CComponentEngine::makePathFromTemplate($arResult['LIST_PAGE_URL']))?>">Все новости</a>
		</div>
	</div>
	<div class="b-news__list">
		<?php foreach ($arResult['ITEMS'] as $arItem): ?>
			<div class="b-news__item">
				<div class="b-news__item-inner">
					<div class="b-news__item-top">
						<div class="b-news__item-title"><a href="<?=$arItem['~DETAIL_PAGE_URL']?>"><?=$arItem['~NAME']?></a></div>
						<div class="b-news__item-text"><?=$arItem['~PREVIEW_TEXT']?></div>
					</div>
				</div>
				<div class="b-news__item-date"><?=FormatDate('j F Y', MakeTimeStamp($arItem['ACTIVE_FROM']))?></div>
			</div>
		<?php endforeach; ?>
	</div>
</div>
