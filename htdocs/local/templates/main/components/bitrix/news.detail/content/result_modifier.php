<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die;
}

$arSection = CIBlockSection::getList(
	[],
	[
		'IBLOCK_ID' => $arParams['IBLOCK_ID'],
		'ID' => $arResult['IBLOCK_SECTION_ID']
	],
	false,
	['ID', 'UF_ICON']
)->fetch();

if ($arSection['UF_ICON']) {
	$arResult['SECTION']['ICON'] = CFile::GetPath($arSection['UF_ICON']);
}
