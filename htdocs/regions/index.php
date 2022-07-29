<?php

use Bitrix\Main\Context;
use Bitrix\Main\Loader;

include_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

Loader::includeModule('iblock');

// для админов и редакторов контента показываем предпросмотр
if ($_REQUEST['show_preview'] == 'y') {
	if (!$USER->isAuthorized()) {
		echo 'Доступ только для авторизированных пользователей';
	}
	if ($USER->isAdmin()) {
		$iblockId = CIBlockTools::getIBlockId('regions_preview');
	} else {
		$arContentEditors = CGroup::getList($by = 'id', $order = 'asc', ['STRING_ID' => 'content_editors'])->fetch();
		if (in_array($arContentEditors['ID'], $USER->GetUserGroupArray())) {
			$iblockId = CIBlockTools::getIBlockId('regions_preview');
		}
	}
} else {
	$iblockId = CIBlockTools::getIBlockId('regions');
}

$request = Context::getCurrent()->getRequest();

if (!$request->getQuery('SECTION_CODE')) {
	MyHelpers::show404Page();
}

$arSection = CIBlockSection::getList(
	[],
	[
		'ACTIVE' => 'Y',
		'IBLOCK_ID' => $iblockId,
		'CODE' => $request->getQuery('SECTION_CODE')
	],
	false,
	['nTopCount' => 1]
)->fetch();

if (!$arSection) {
	MyHelpers::show404Page();
}

$arElement = CIBlockElement::getList(
	[],
	[
		'ACTIVE' => 'Y',
		'IBLOCK_ID' => $iblockId,
		'IBLOCK_SECTION_ID' => $arSection['ID']
	],
	false,
	['nTopCount' => 1]
)->fetch();

if (!$arElement) {
	MyHelpers::show404Page();
}

$arIBlock = CIBlock::getById($iblockId)->getNext();

$APPLICATION->AddChainItem($arIBlock['~NAME']);

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');

$APPLICATION->includeComponent(
	'bitrix:news.detail',
	'content',
	[
		'DISPLAY_DATE' => 'N',
		'DISPLAY_NAME' => 'N',
		'DISPLAY_PICTURE' => 'N',
		'DISPLAY_PREVIEW_TEXT' => 'N',
		'IBLOCK_ID' => $iblockId,
		'IBLOCK_TYPE' => 'content',
		'FIELD_CODE' => [''],
		'ELEMENT_ID' => $arElement['ID'],
		'INCLUDE_IBLOCK_INTO_CHAIN' => 'N',
		'ADD_SECTIONS_CHAIN' => 'Y',
		'ADD_ELEMENT_CHAIN' => 'N',
		'SET_TITLE' => 'Y',
				'DETAIL_PROPERTY_CODE' => [
			'FILE_ATTACH',
			'FILE_DESCRIPTION'
		],
	]
);

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
