<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die;
}
?>
<div class="b-news">
	<div class="b-news__inner">
		<h1 class="b-news__title b-title b-title-strip"><?=(mb_strtoupper($arResult['IBLOCK']['~NAME']))?></h1>
		<div class="b-news__wrap b-list__wrap">
			<div class="b-news__left b-list__item">
				<div class="b-list__item-img"><img src="<?=SITE_TEMPLATE_PATH?>/images/news-ico.svg" alt=""></div>
			</div>
			<div class="b-news__middle b-list__item">
				<div class="b-news__list b-news__list-p">
					<?php foreach ($arResult['ITEMS'] as $arItem): ?>
						<a class="b-news__item b-news__item-p" href="<?=$arItem['~DETAIL_PAGE_URL']?>">
							<div class="b-news__item-inner">
									<div class="b-news__item-top">
										<div class="b-news__item-title"><?=$arItem['~NAME']?></div>
										<div class="b-news__item-text"><?=$arItem['~PREVIEW_TEXT']?></div>
									</div>
							</div>
							<div class="b-news__item-date"><?=FormatDate('j F Y', MakeTimeStamp($arItem['ACTIVE_FROM']))?></div>
						</a>
					<?php endforeach; ?>
				</div>
				<?=$arResult['NAV_STRING']?>
			</div>
			<div class="b-news__right b-list__item">
				<?php
				$APPLICATION->includeComponent(
					'bitrix:news.detail',
					'share.buttons',
					[
						'DISPLAY_DATE' => 'N',
						'DISPLAY_NAME' => 'N',
						'DISPLAY_PICTURE' => 'N',
						'DISPLAY_PREVIEW_TEXT' => 'N',
						'IBLOCK_ID' => CIBlockTools::getIBlockId('contacts'),
						'IBLOCK_TYPE' => 'content',
						'FIELD_CODE' => [''],
						'PROPERTY_CODE' => ['SOCIAL_LINK', ''],
						'ELEMENT_CODE' => 'share_buttons',
						'INCLUDE_IBLOCK_INTO_CHAIN' => 'N',
						'ADD_SECTIONS_CHAIN' => 'N',
						'ADD_ELEMENT_CHAIN' => 'N',
						'SET_TITLE' => 'N',
						'PAGE_TITLE' => $arResult['META_TAGS']['TITLE'] . $arResult['IBLOCK']['~NAME'],
						'CACHE_TYPE' => 'N'
					]
				);
				?>
			</div>
		</div>
	</div>
</div>
