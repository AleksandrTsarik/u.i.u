<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die;
}
?>
<?php foreach ($arResult['PROPERTIES']['SOCIAL_LINK']['VALUE'] as $arValue): ?>
	<?php
		$arSubValue = $arValue['SUB_VALUES'];
	?>
	<div>
		<a href="<?=$arSubValue['LINK_URL']['~VALUE']?>" target="_blank">
			<img src="<?=$arSubValue['LINK_IMAGE']['IMAGE']['SRC']?>" alt="<?=$arSubValue['LINK_TEXT']['~VALUE']?>">
		</a>
	</div>
<?php endforeach; ?>
