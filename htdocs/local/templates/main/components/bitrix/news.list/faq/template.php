<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die;
}

$APPLICATION->setTitle($arResult['IBLOCK']['NAME']);

$this->setFrameMode(true);
?>
<div class="b-questions">
	<div class="b-questions__inner">
		<h1 class="b-questions__title b-title b-title-strip"><?=(mb_strtoupper($arResult['IBLOCK']['~NAME']))?></h1>
		<div class="b-questions__wrap b-list__wrap">
			<div class="b-questions__left b-list__item">
				<div class="b-list__item-img"><img src="<?=SITE_TEMPLATE_PATH?>/images/faq-ico.svg" alt=""></div>
			</div>
			<div class="b-questions__middle b-list__item">
				<?=$arResult['IBLOCK']['~DESCRIPTION']?>
				<div class="b-tabs">
					<?php foreach ($arResult['ITEMS'] as $arItem): ?>
						<div class="b-tab">
							<div class="b-tab__name js-click"><?=$arItem['~NAME']?></div>
							<div class="b-tab__content" style="display: none;">
								<div class="b-tab__content-inner">
									<div class="b-tab__content-ico"><img src="/f/img/letter-ico.svg" alt=""></div>
									<div class="b-tab__content-text">
										<?=$arItem['~PREVIEW_TEXT']?>
									</div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="b-questions__right b-list__item">
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
						'PAGE_TITLE' => $arResult['IBLOCK']['~NAME'],
						'CACHE_TYPE' => 'N'
					]
				);
				?>
			</div>
		</div>
	</div>
</div>
