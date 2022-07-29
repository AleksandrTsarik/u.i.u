<?php
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');

$APPLICATION->IncludeComponent(
	'bitrix:news.list',
	'faq',
	[
		'IBLOCK_TYPE' => 'content',
		'IBLOCK_ID' => CIBlockTools::getIBlockId('faq'),
		'NEWS_COUNT' => 20,
		'SORT_BY1' => 'SORT',
		'SORT_ORDER1' => 'ASC',
	],
	$component
);

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
