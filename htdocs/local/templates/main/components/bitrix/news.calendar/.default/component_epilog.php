<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die;
}

$arIBlock = CIBlock::getById(CIBlockTools::getIBlockId('party'))->getNext();

$arSection = CIBlockSection::getList(
	[],
	[
		'IBLOCK_ID' => CIBlockTools::getIBlockId('party'),
		'CODE' => 'timetable',
		'SECTION_ID' => false
	],
	false,
	[],
	['nTopCount' => 1]
)->getNext();

$APPLICATION->AddChainItem($arIBlock['~NAME']);

$APPLICATION->AddChainItem(
	$arSection['~NAME'],
	CComponentEngine::makePathFromTemplate($arSection['SECTION_PAGE_URL'])
);

$APPLICATION->AddChainItem($arResult['TITLE']);
