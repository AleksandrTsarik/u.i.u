<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die;
}

$this->setFrameMode(true);
?>
<div class="b-offer">
	<div class="b-offer__list">
		<?php foreach ($arResult['ITEMS'] as $arItem): ?>
			<?php
				$props = $arItem['DISPLAY_PROPERTIES'];
			?>
			<div class="b-offer__item">
				<div class="b-offer__bg <?=$props['STYLE']['VALUE_XML_ID']?>">
					<?php if (key_exists('ADDITIONAL_PICTURE', $props)): ?>
						<div class="b-offer__item-foto"><img src="<?=$props['ADDITIONAL_PICTURE']['FILE_VALUE']['SRC']?>" alt=""></div>
					<?php endif; ?>
					<div class="b-offer__item-top">
						<div class="b-offer__item-title"><?=mb_strtoupper($arItem['~NAME'])?></div>
					</div>
					<div class="b-offer__item-middle"> 
						<div class="b-offer__item-text"><?=$arItem['~PREVIEW_TEXT']?></div>
						<div class="b-offer__item-img"><img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt=""></div>
					</div>
					<div class="b-offer__item-bottom">
						<div class="b-offer__item-link">
							<a href="<?=$props['LINK_URL']['VALUE']?>"><?=$props['LINK_CAPTION']['VALUE']?><img src="/f/img/arrow.svg" alt=""></a>
						</div>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>
