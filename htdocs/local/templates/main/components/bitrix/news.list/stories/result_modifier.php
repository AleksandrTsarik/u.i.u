<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die;
}

$rsBlocks = CIBlockElement::getList(
	['SORT' => 'ASC'],
	[
		'IBLOCK_ID' => CIBlockTools::getIBlockId('color_blocks'),
		'ACTIVE' => 'Y',
		'!CODE' => 'stories'
	],
	false,
	false,
	[
		'ID',
		'IBLOCK_ID',
		'NAME',
		'PREVIEW_TEXT',
		'PREVIEW_PICTURE',
		'PROPERTY_LINK_URL',
		'PROPERTY_LINK_CAPTION',
		'PROPERTY_STYLE',
		'PROPERTY_ADDITIONAL_PICTURE'
	]
);

$arResult['BLOCKS'] = [];
while ($arBlock = $rsBlocks->getNextElement()) {
	$arFields = $arBlock->getFields();
	$arProperties = $arBlock->getProperties();
	$arResult['BLOCKS'][] = [
		'NAME' => $arFields['~NAME'],
		'PREVIEW_TEXT' => $arFields['~PREVIEW_TEXT'],
		'PREVIEW_PICTURE' => CFile::getPath($arFields['PREVIEW_PICTURE']),
		'LINK_URL' => $arFields['~PROPERTY_LINK_URL_VALUE'],
		'LINK_CAPTION' => $arFields['~PROPERTY_LINK_CAPTION_VALUE'],
		'STYLE' => $arProperties['STYLE']['VALUE_XML_ID'],
		'ADDITIONAL_PICTURE' => CFile::getPath($arFields['PROPERTY_ADDITIONAL_PICTURE_VALUE'])
	];
}
