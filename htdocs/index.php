<?php
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');

$APPLICATION->IncludeComponent(
	'bitrix:news.list',
	'broadcast',
	[
		'IBLOCK_TYPE' => 'content',
		'IBLOCK_ID' => CIBlockTools::getIBlockId('broadcast'),
		'CACHE_TYPE' => 'N',
		'NEWS_COUNT' => 4,
		'SORT_BY1' => 'SORT',
		'SORT_ORDER1' => 'DESC',
		'SORT_BY2' => 'DATE_ACTIVE',
		'SORT_ORDER2' => 'DESC',
		'PROPERTY_CODE' => ['DATE_TIME'],
		'SET_TITLE' => 'N'
	],
	$component
);

$APPLICATION->IncludeComponent(
	'bitrix:news.list',
	'color.blocks',
	[
		'IBLOCK_TYPE' => 'content',
		'IBLOCK_ID' => CIBlockTools::getIBlockId('color_blocks'),
		'NEWS_COUNT' => 3,
		'SORT_BY1' => 'SORT',
		'SORT_ORDER1' => 'ASC',
		'PROPERTY_CODE' => ['LINK_CAPTION', 'LINK_URL', 'STYLE', 'ADDITIONAL_PICTURE'],
		'SET_TITLE' => 'N'
	],
	$component
);

$APPLICATION->IncludeComponent(
	'bitrix:news.list',
	'main',
	[
		'IBLOCK_TYPE' => 'content',
		'IBLOCK_ID' => CIBlockTools::getIBlockId('news'),
		'NEWS_COUNT' => 4,
		'SORT_BY1' => 'ACTIVE_FROM',
		'SORT_ORDER1' => 'DESC',
		'SORT_BY2' => 'SORT',
		'SORT_ORDER2' => 'DESC',
		'SET_TITLE' => 'N'
	],
	$component
);

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
