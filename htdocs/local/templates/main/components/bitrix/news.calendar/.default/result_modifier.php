<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die;
}

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

$arResult['SECTION_TITLE'] = $arSection['~NAME'];

$time = MakeTimeStamp(
	$arResult['currentYear'] . '-' . str_pad($arResult['currentMonth'], 2, '0', STR_PAD_LEFT) .  '-01 00:00:00',
	'YYYY-MM-DD HH:MI:SS'
);

$rsTypes = CIBlockPropertyEnum::GetList([], ['IBLOCK_ID' => $arParams['IBLOCK_ID'], 'CODE' => 'EVENT_TYPE']);
while ($arType = $rsTypes->fetch()) {
	$arTypes[$arType['ID']] = $arType['XML_ID'];
}

$rsEvents = CIBlockElement::getList(
	[$arParams['DATE_FIELD'] => 'ASC'],
	[
		'ACTIVE' => 'Y',
		'IBLOCK_ID' => $arParams['IBLOCK_ID'],
		'>=' . $arParams['DATE_FIELD'] => date('d.m.Y', $time),
		'<=' . $arParams['DATE_FIELD'] => date('t.m.Y 23:59:59', $time)
	],
	false,
	false,
	[
		'NAME',
		'PREVIEW_TEXT',
		$arParams['DATE_FIELD'],
		'PROPERTY_EVENT_TYPE',
		'PROPERTY_EVENT_ADRESS',
		'IBLOCK_SECTION_ID'
	]
);
while ($arEvent = $rsEvents->getNext()) {
	$eventTime = MakeTimeStamp($arEvent[$arParams['DATE_FIELD']]);
	$arResult['EVENTS'][date('j', $eventTime)][] = [
		'NAME' => $arEvent['~NAME'],
		'TIME' => date('H:i', $eventTime),
		'PREVIEW_TEXT' => $arEvent['PREVIEW_TEXT'],
		'ADRESS' => $arEvent['~PROPERTY_EVENT_ADRESS_VALUE']['TEXT'],
		'COLOR' => $arTypes[$arEvent['PROPERTY_EVENT_TYPE_ENUM_ID']],
		'TYPE' => $arEvent['~PROPERTY_EVENT_TYPE_VALUE'],
		'SECTION_ID' => $arEvent['IBLOCK_SECTION_ID']
	];
}

if (!empty($arResult['EVENTS'])) {
	// берем описание сезона из первой найденой секции
	$arSection = CIBlockSection::getById(reset($arResult['EVENTS'])[0]['SECTION_ID'])->getNext();
	$arResult['SEASON_HEADER'] = $arSection['~NAME'];
	$arResult['SEASON_TEXT'] = $arSection['~DESCRIPTION'];
}
