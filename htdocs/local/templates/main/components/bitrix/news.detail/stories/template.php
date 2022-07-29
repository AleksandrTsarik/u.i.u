<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die;
}

$this->SetViewTarget('top_right_image');
?>
<div class="content-wrapper">
	<div class="b-page__img b-page__img-stories"></div>
</div>
<?php
$this->endViewTarget('top_right_image');
?>
<div class="b-stories__title b-title b-title-strip b-title--h2"><?=mb_strtoupper($arResult['SECTION']['PATH'][0]['~NAME'])?></div>
<div class="b-stories-open__wrap">
	<div class="b-stories-open__inner b-list__wrap">
		<div class="b-stories-open__item b-list__item"><a class="b-back-link" href="<?=$arResult['SECTION_URL']?>"></a></div>
		<div class="b-stories-open__item b-list__item">
			<div class="b-stories-open__content">
				<h1 class="fs--h1"><?=$arResult['~NAME']?></h1>
				<div>
					<?php if ($arParams['DISPLAY_PICTURE'] != 'N' && is_array($arResult['DETAIL_PICTURE'])): ?>
						<img
							src="<?=$arResult['DETAIL_PICTURE']['SRC']?>"
							alt="<?=$arResult['DETAIL_PICTURE']['ALT']?>"
							title="<?=$arResult['DETAIL_PICTURE']['TITLE']?>"
							>
						<?php if ($arResult['DETAIL_PICTURE']['DESCRIPTION']): ?>
							<div class="b-foto-text"><?=nl2br($arResult['DETAIL_PICTURE']['DESCRIPTION'])?></div>
						<?php endif; ?>
					<?php endif; ?>
				</div>
				<div class="b-content">
					<?=$arResult['DETAIL_TEXT']?>
				</div>
			</div>
		</div>
		<div class="b-stories-open__item b-list__item">
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
					'PAGE_TITLE' => $arResult['META_TAGS']['TITLE'],
					'CACHE_TYPE' => 'N'
				]
			);
			?>
		</div>
	</div>
</div>
