<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die;
}

foreach ($arResult['PROPERTIES']['SOCIAL_LINK']['VALUE'] as &$arValue) {
	$arSubValue = $arValue['SUB_VALUES'];
	$arImage = CFile::GetFileArray($arSubValue['LINK_IMAGE']['VALUE']);
	$arValue['SUB_VALUES']['LINK_IMAGE']['IMAGE'] = $arImage;
}
