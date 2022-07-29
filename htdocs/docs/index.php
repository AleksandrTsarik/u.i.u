<?php
use Bitrix\Main\Context;

include_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

$request = Context::getCurrent()->getRequest();

if (!$request->getQuery('ELEMENT_CODE')) {
	MyHelpers::show404Page();
}

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');

// для админов и редакторов контента показываем предпросмотр
if ($request->getQuery('show_preview') == 'y') {
	if (!$USER->isAuthorized()) {
		echo 'Доступ только для авторизированных пользователей';
	}
	if ($USER->isAdmin()) {
		$iblockId = CIBlockTools::getIBlockId('docs_preview');
	} else {
		$arContentEditors = CGroup::getList($by = 'id', $order = 'asc', ['STRING_ID' => 'content_editors'])->fetch();
		if (in_array($arContentEditors['ID'], $USER->GetUserGroupArray())) {
			$iblockId = CIBlockTools::getIBlockId('docs_preview');
		}
	}
} else {
	$iblockId = CIBlockTools::getIBlockId('docs');
}

$arIBlock = CIBlock::getById($iblockId)->getNext();

$APPLICATION->AddChainItem($arIBlock['~NAME']);

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
		'ELEMENT_CODE' => $request->getQuery('ELEMENT_CODE'),
		'INCLUDE_IBLOCK_INTO_CHAIN' => 'N',
		'ADD_SECTIONS_CHAIN' => 'N',
		'ADD_ELEMENT_CHAIN' => 'Y',
		'SET_TITLE' => 'Y',
		'DETAIL_PROPERTY_CODE' => [
			'FILE_ATTACH',
			'FILE_DESCRIPTION'
		],
	]
);
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
