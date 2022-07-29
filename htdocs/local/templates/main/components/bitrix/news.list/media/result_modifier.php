<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die;
}

$arResult['IBLOCK'] = CIBlock::getById($arParams['IBLOCK_ID'])->getNext();

$rsItems = CIBlockElement::getList(
	[
		$arParams['SORT_BY1'] => $arParams['SORT_ORDER1'],
		$arParams['SORT_BY2'] => $arParams['SORT_ORDER2']
	],
	[
		'IBLOCK_ID' => $arParams['IBLOCK_ID'],
		'ID' => array_column($arResult['ITEMS'], 'ID')
	],
	false,
	false,
	[
		'ID',
		'IBLOCK_ID',
		'NAME',
		'DETAIL_TEXT',
		'DETAIL_PICTURE',
		'PROPERTY_YOUTUBE_CODE',
		'PROPERTY_VIDEO_FILE',
	]
);

while ($arItem = $rsItems->getNext()) {
	$arResult['DETAILS'][$arItem['ID']]['NAME'] = $arItem['NAME'];
	$arResult['DETAILS'][$arItem['ID']]['TEXT'] = $arItem['DETAIL_TEXT'];

	if ($arItem['DETAIL_PICTURE']) {
		$arResult['DETAILS'][$arItem['ID']]['PICTURE'] = CFile::getPath($arItem['DETAIL_PICTURE']);
	} elseif ($arItem['PROPERTY_YOUTUBE_CODE_VALUE']) {
		$arResult['DETAILS'][$arItem['ID']]['CONTENT'] = $arItem['~PROPERTY_YOUTUBE_CODE_VALUE'];
	} elseif ($arItem['PROPERTY_VIDEO_FILE_VALUE']) {
		$arResult['DETAILS'][$arItem['ID']]['VIDEO'] = CFile::getPath($arItem['PROPERTY_VIDEO_FILE_VALUE']);
	}
}
