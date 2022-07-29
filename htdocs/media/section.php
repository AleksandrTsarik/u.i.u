<?php

use Bitrix\Main\Context;

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');

$request = Context::getCurrent()->getRequest();

$APPLICATION->IncludeComponent(
	'bitrix:news.list',
	'media',
	[
		'IBLOCK_ID' => CIBlockTools::getIBlockId('media'),
		'NEWS_COUNT' => 10000000,
		'SORT_BY1' => 'SORT',
		'SORT_ORDER1' => 'ASC',
		'SORT_BY2' => 'ID',
		'SORT_ORDER2' => 'ASC',
		'FIELD_CODE' => ['', ''],
		'PROPERTY_CODE' => ['', ''],
		'SET_TITLE' => 'Y',
		'INCLUDE_IBLOCK_INTO_CHAIN' => 'Y',
		'ADD_SECTIONS_CHAIN' => 'Y',
		'CACHE_TYPE' => 'A',
		'CACHE_TIME' => 3600,
		'DISPLAY_TOP_PAGER' => 'N',
		'DISPLAY_BOTTOM_PAGER' => 'Y',
		'DISPLAY_DATE' => 'N',
		'DISPLAY_NAME' => 'Y',
		'DISPLAY_PICTURE' => 'Y',
		'DISPLAY_PREVIEW_TEXT' => 'N',
		'USE_PERMISSIONS' => 'Y',
		'PARENT_SECTION_CODE' => $request->getQuery('SECTION_CODE'),
		'SEF_FOLDER' => '/media/',
		'SEF_MODE' => 'Y',
		'SEF_URL_TEMPLATES' => ['detail'=>'#SITE_DIR#/media/#SECTION_CODE_PATH#/#ELEMENT_CODE#/','news'=>'','search'=>'search/','section'=>'#SITE_DIR#/media/#SECTION_CODE_PATH#/'],
		'DETAIL_URL' => '/media/#SECTION_CODE_PATH#/#ELEMENT_CODE#/',
		'SECTION_URL' => '/media/#SECTION_CODE_PATH#/',
		'IBLOCK_URL' => '/media/',
	],
	$component
);

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
