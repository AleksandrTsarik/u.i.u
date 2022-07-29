<?php
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');

$APPLICATION->IncludeComponent(
	'bitrix:news.calendar',
	'',
	[
		'AJAX_MODE' => 'N',
		'IBLOCK_TYPE' => 'content',
		'IBLOCK_ID' => CIBlockTools::getIBlockId('timetable'),
		'MONTH_VAR_NAME' => 'month',
		'YEAR_VAR_NAME' => 'year',
		'WEEK_START' => '1',
		'DATE_FIELD' => 'DATE_ACTIVE_FROM',
		'TYPE' => 'EVENTS',
		'SHOW_YEAR' => 'N',
		'SHOW_TIME' => 'Y',
		'TITLE_LEN' => '0',
		'SET_TITLE' => 'Y',
		'SHOW_CURRENT_DATE' => 'Y',
		'SHOW_MONTH_LIST' => 'N',
		'NEWS_COUNT' => '0',
		'DETAIL_URL' => '',
		'CACHE_TYPE' => 'A',
		'CACHE_TIME' => '3600',
		'AJAX_OPTION_JUMP' => 'N',
		'AJAX_OPTION_STYLE' => 'N',
		'AJAX_OPTION_HISTORY' => 'N',
		'AJAX_OPTION_ADDITIONAL' => ''
	]
);

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
