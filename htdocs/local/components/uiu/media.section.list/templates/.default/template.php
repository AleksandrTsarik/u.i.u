<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die;
}

$APPLICATION->AddChainItem(
	$arResult['IBLOCK']['~NAME'],
	preg_replace('/^\/\/+/', '/', CComponentEngine::makePathFromTemplate($arResult['IBLOCK']['LIST_PAGE_URL']))
);

$APPLICATION->setTitle($arResult['IBLOCK']['NAME']);

$this->setFrameMode(true);
?>
<div class="b-media">
	<div class="b-media__inner">
		<h2 class="b-questions__title b-title b-title-strip"><?=(mb_strtoupper($arResult['IBLOCK']['~NAME']))?></h2>
		<div class="b-media__wrap b-list__wrap">
			<div class="b-media__block b-list__item">
				<div class="b-list__item-img"><img src="<?=SITE_TEMPLATE_PATH?>/images/media-ico.svg" alt=""></div>
			</div>
			<div class="b-media__block b-list__item">
				<div class="b-media__list">
					<?php foreach ($arResult['SECTIONS'] as $arSection): ?>
						<a class="b-media__item" href="<?=$arSection['SECTION_PAGE_URL']?>">
							<div class="b-media__item-img">
								<img src="<?=$arSection['PICTURE'] ?: SITE_TEMPLATE_PATH . '/images/image-ico.svg'?>" alt="">
							</div>
							<div class="b-media__item-title"><?=$arSection['NAME']?></div>
							<div class="b-media__item-subtitle"><?=$arSection['ALBUM_YEAR']?>&nbsp;год</div>
						</a>
					<?php endforeach; ?>
				</div>
				<?php
					$APPLICATION->IncludeComponent(
						'bitrix:main.pagenavigation',
						'',
						[
							'NAV_OBJECT' => $arResult['NAV_OBJECT'],
							'SEF_MODE' => 'N'
						]
					);
				?>
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
					'PAGE_TITLE' => $arResult['IBLOCK']['~NAME']
				]
			);
			?>
			</div>
		</div>
	</div>
</div>
