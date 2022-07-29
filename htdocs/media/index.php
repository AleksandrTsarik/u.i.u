<?php

use Bitrix\Main\Context;

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');

$request = Context::getCurrent()->getRequest();

$APPLICATION->IncludeComponent(
	'uiu:media.section.list',
	'',
	[
		'ADD_SECTIONS_CHAIN' => 'Y',
		'CACHE_FILTER' => 'N',
		'CACHE_GROUPS' => 'Y',
		'CACHE_TIME' => 36000,
		'CACHE_TYPE' => 'A',
		'COUNT_ELEMENTS' => 'N',
		'FILTER_NAME' => 'sectionsFilter',
		'IBLOCK_ID' => CIBlockTools::getIBlockId('media'),
		'IBLOCK_TYPE' => 'content',
		'SECTION_CODE' => $request->getQuery('SECTION_CODE'),
		'SECTION_FIELDS' => ['', ''],
		'SECTION_ID' => '',
		'SECTION_URL' => '',
		'SECTION_USER_FIELDS' => ['UF_YEAR', ''],
		'SECTIONS_COUNT' => 8,
		'SHOW_PARENT_NAME' => 'Y',
		'TOP_DEPTH' => 2,
		'SHOW_PARENT_NAME' => 'Y'
	]
);

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
