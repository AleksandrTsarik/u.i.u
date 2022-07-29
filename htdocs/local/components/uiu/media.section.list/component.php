<?php
use Bitrix\Main\Loader,
	Bitrix\Main\Context,
	Bitrix\Iblock\Model\Section;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die;
}

$arResult = [
	'SECTIONS' => []
];

Loader::includeModule('iblock');

$request = Context::getCurrent()->getRequest();

$nav = new \Bitrix\Main\UI\PageNavigation('nav');
$nav->allowAllRecords(false)
	->setPageSize($arParams['SECTIONS_COUNT'])
	->initFromUri();

$arSelect = [
	'ID',
	'CODE',
	'NAME',
	'CODE',
	'PICTURE',
	'IBLOCK_SECTION_ID',
	'SECTION_PAGE_URL_RAW' => 'IBLOCK.SECTION_PAGE_URL',
	'UF_YEAR',
];
$sectionEntity = Section::compileEntityByIblock($arParams['IBLOCK_ID']);
$rsSections = $sectionEntity::getList([
	'order' => ['UF_YEAR' => 'ASC', 'SORT' => 'ASC'],
	'filter' => [
		'IBLOCK_ID' => $arParams['IBLOCK_ID'],
		'DEPTH_LEVEL' => 1,
		'ACTIVE' => 'Y'
	], 
	'select' => array_unique(array_filter(array_merge($arSelect, $arParams['SECTION_USER_FIELDS']))),
	'offset' => $nav->getOffset(),
	'limit' => $nav->getLimit(),
	'count_total' => true
]);

$nav->setRecordCount($rsSections->getCount());
$arResult['NAV_OBJECT'] = $nav;

while ($arSection = $rsSections->fetch()) {
	$arSection['SECTION_CODE_PATH'] = '';
	$arNavChain = CIBlockSection::getNavChain(
		$arParams['IBLOCK_ID'],
		$arSection['ID'],
		['ID', 'IBLOCK_SECTION_ID', 'CODE'],
		true
	);
	if (!empty($arNavChain)) {
		foreach ($arNavChain as $_arNavChain) {
			$arSection['SECTION_CODE_PATH'] .= rawurlencode($_arNavChain['CODE']) . '/';
		}
	}

	$arResult['SECTIONS'][] = [
		'NAME' => $arSection['NAME'],
		'PICTURE' => $arSection['PICTURE'] ? CFile::GetPath($arSection['PICTURE']) : null,
		'SECTION_PAGE_URL' => \CIBlock::replaceDetailUrl($arSection['SECTION_PAGE_URL_RAW'], $arSection, false, 'S'),
		'ALBUM_YEAR' => $arSection['UF_YEAR']
	];
}

$arResult['IBLOCK'] = CIBlock::getById($arParams['IBLOCK_ID'])->getNext();

$this->IncludeComponentTemplate();
