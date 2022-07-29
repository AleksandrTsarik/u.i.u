<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die;
}
?>
<div class="b-media b-media-open">
	<div class="b-media__inner">
		<h1 class="b-questions__title b-title b-title-strip"><?=(mb_strtoupper($arResult['SECTION']['PATH'][0]['~NAME']))?></h1>
		<div class="b-media__wrap b-list__wrap">
			<div class="b-media__block b-list__item"><a class="b-back-link" href="<?=$arResult['SECTION']['PATH'][0]['~LIST_PAGE_URL']?>"></a></div>
			<div class="b-media__block b-list__item">
				<div class="b-media__list">
					<?php foreach ($arResult['ITEMS'] as $arItem): ?>
						<a class="b-media__item open-modal" href="#slide-<?=$arItem['ID']?>">
							<div class="b-media__item-img"><img src="<?=($arItem['PREVIEW_PICTURE'] ? $arItem['PREVIEW_PICTURE']['SAFE_SRC'] : SITE_TEMPLATE_PATH . '/images/image-ico.svg')?>" alt=""></div>
							<div class="b-media__item-title"><?=$arItem['~NAME']?></div>
							<div class="b-media__item-subtitle">2021 год</div>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="b-media__block b-list__item">
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
<div class="b-vs__overlay"></div>
<div class="b-vs__wrap">
	<div class="b-vs__list b-vs-carusel">
		<?php foreach ($arResult['DETAILS'] as $itemId => $arItem): ?>
			<div class="b-vs__item" data-hash="slide-<?=$itemId?>">
				<div class="b-vs__item-inner">
						<?php if ($arItem['PICTURE']): ?>
							<img src="<?=$arItem['PICTURE']?>" alt="">
						<?php elseif ($arItem['CONTENT']): ?>
							<div class="b-vs__iframe"><?=$arItem['CONTENT']?></div>
						<?php elseif ($arItem['VIDEO']): ?>
							<video controls="controls">
								<source src="<?=$arItem['VIDEO']?>">
							</video>
						<?php endif; ?>
					<div class="b-vs-text">
						<div class="b-vs-text__title"><?=$arItem['NAME']?></div>
						<div class="b-vs-text__date"><?=$arItem['DETAIL_TEXT']?></div>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>