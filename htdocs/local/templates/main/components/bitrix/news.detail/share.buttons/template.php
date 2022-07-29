<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die;
}
?>
<div class="b-network">
	<div class="b-network__left">
		<a class="b-network__item b-sharelink__copy" data-href="<?=$arResult['SHARE_URL']?>">
			<img src="/f/img/network/link.svg" alt="">
		</a>
	</div>
	<div class="b-network__right">
	<?php foreach ($arResult['PROPERTIES']['SOCIAL_LINK']['VALUE'] as $arValue): ?>
		<?php
			$arSubValue = $arValue['SUB_VALUES'];
		?>
		<a class="b-network__item" href="<?=$arSubValue['LINK_URL']['~VALUE']?>" target="_blank">
			<img src="<?=$arSubValue['LINK_IMAGE']['IMAGE']['SRC']?>" alt="<?=$arSubValue['LINK_TEXT']['~VALUE']?>">
		</a>
	<?php endforeach; ?>
	</div>
</div>
