<?php
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');

$APPLICATION->setTitle('Поиск');
$APPLICATION->addChainItem('Поиск');

$APPLICATION->IncludeComponent(
	'bitrix:search.page', 
	'',
	[
		'AJAX_MODE' => 'N',
		'AJAX_OPTION_ADDITIONAL' => '',
		'AJAX_OPTION_HISTORY' => 'N',
		'AJAX_OPTION_JUMP' => 'N',
		'AJAX_OPTION_SHADOW' => 'Y',
		'AJAX_OPTION_STYLE' => 'Y',
		'CACHE_TIME' => 3600,
		'CACHE_TYPE' => 'A',
		'CHECK_DATES' => 'Y',
		'COLOR_NEW' => '000000',
		'COLOR_OLD' => 'C8C8C8',
		'COLOR_TYPE' => 'Y',
		'DEFAULT_SORT' => 'rank',
		'DISPLAY_BOTTOM_PAGER' => 'Y',
		'DISPLAY_TOP_PAGER' => 'N',
		'FILTER_NAME' => '',
		'FONT_MAX' => 50,
		'FONT_MIN' => 10,
		'NO_WORD_LOGIC' => 'N',
		'PAGER_SHOW_ALWAYS' => 'Y',
		'PAGER_TEMPLATE' => '',
		'PAGER_TITLE' => 'Результаты поиска',
		'PAGE_RESULT_COUNT' => 10,
		'PATH_TO_USER_PROFILE' => '',
		'PERIOD_NEW_TAGS' => '',
		'RESTART' => 'Y',
		'SHOW_CHAIN' => 'Y',
		'SHOW_RATING' => 'N',
		'SHOW_WHEN' => 'Y',
		'SHOW_WHERE' => 'N',
		'TAGS_INHERIT' => 'Y',
		'TAGS_PAGE_ELEMENTS' => 150,
		'TAGS_PERIOD' => 30,
		'TAGS_SORT' => 'NAME',
		'TAGS_URL_SEARCH' => '/search/index.php',
		'USE_LANGUAGE_GUESS' => 'N',
		'USE_SUGGEST' => 'N',
		'USE_TITLE_RANK' => 'Y',
		'WIDTH' => '100%',
		'arrFILTER' => [
			'iblock_content',
		],
		'arrFILTER_iblock_content' => [
			CIBlockTools::getIblockId('party'),
			CIBlockTools::getIblockId('info'),
			CIBlockTools::getIblockId('regions'),
			CIBlockTools::getIblockId('more'),
			CIBlockTools::getIblockId('news'),
			CIBlockTools::getIblockId('broadcast'),
			CIBlockTools::getIblockId('faq'),
			CIBlockTools::getIblockId('media'),
			CIBlockTools::getIblockId('contacts'),
			CIBlockTools::getIblockId('docs')
		],
		'arrWHERE' => [
			
		],
		'COMPONENT_TEMPLATE' => '.default'
		],
	false
);

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
