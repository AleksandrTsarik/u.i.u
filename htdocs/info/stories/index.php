<?php
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');

// для админов и редакторов контента показываем предпросмотр
if ($_REQUEST['show_preview'] == 'y') {
	if (!$USER->isAuthorized()) {
		echo 'Доступ только для авторизированных пользователей';
	}
	if ($USER->isAdmin()) {
		$iblockId = CIBlockTools::getIBlockId('info_preview');
	} else {
		$arContentEditors = CGroup::getList($by = 'id', $order = 'asc', ['STRING_ID' => 'content_editors'])->fetch();
		if (in_array($arContentEditors['ID'], $USER->GetUserGroupArray())) {
			$iblockId = CIBlockTools::getIBlockId('info_preview');
		}
	}
} else {
	$iblockId = CIBlockTools::getIBlockId('info');
}

$arIBlock = CIBlock::getById($iblockId)->getNext();

$APPLICATION->AddChainItem($arIBlock['~NAME']);

$APPLICATION->IncludeComponent(
	'bitrix:news',
	'stories',
	[
		'ADD_ELEMENT_CHAIN' => 'Y',
		'ADD_SECTIONS_CHAIN' => 'Y',
		'AJAX_MODE' => 'N',
		'AJAX_OPTION_ADDITIONAL' => '',
		'AJAX_OPTION_HISTORY' => 'N',
		'AJAX_OPTION_JUMP' => 'N',
		'AJAX_OPTION_STYLE' => 'Y',
		'BROWSER_TITLE' => '-',
		'CACHE_FILTER' => 'N',
		'CACHE_GROUPS' => 'Y',
		'CACHE_TIME' => '36000000',
		'CACHE_TYPE' => 'A',
		'CHECK_DATES' => 'Y',
		'DETAIL_ACTIVE_DATE_FORMAT' => 'd.m.Y',
		'DETAIL_DISPLAY_BOTTOM_PAGER' => 'Y',
		'DETAIL_DISPLAY_TOP_PAGER' => 'N',
		'DETAIL_FIELD_CODE' => ['',''],
		'DETAIL_PAGER_SHOW_ALL' => 'Y',
		'DETAIL_PAGER_TEMPLATE' => '',
		'DETAIL_PAGER_TITLE' => 'Страница',
		'DETAIL_PROPERTY_CODE' => [
			'AUTHOR',
			'PRODUCT_RECOM',
			'CONTENTS'
		],
		'DETAIL_SET_CANONICAL_URL' => 'N',
		'DISPLAY_BOTTOM_PAGER' => 'Y',
		'DISPLAY_DATE' => 'Y',
		'DISPLAY_NAME' => 'Y',
		'DISPLAY_PICTURE' => 'Y',
		'DISPLAY_PREVIEW_TEXT' => 'Y',
		'DISPLAY_TOP_PAGER' => 'N',
		'FILE_404' => '',
		'HIDE_LINK_WHEN_NO_DETAIL' => 'N',
		'IBLOCK_ID' => $iblockId,
		'IBLOCK_TYPE' => 'content',
		'INCLUDE_IBLOCK_INTO_CHAIN' => 'N',
		'LIST_ACTIVE_DATE_FORMAT' => 'd.m.Y',
		'LIST_FIELD_CODE' => ['',''],
		'LIST_PROPERTY_CODE' => [
			'READ_TIME'
		],
		'MESSAGE_404' => '',
		'META_DESCRIPTION' => '-',
		'META_KEYWORDS' => '-',
		'NEWS_COUNT' => '7',
		'PAGER_BASE_LINK_ENABLE' => 'N',
		'PAGER_DESC_NUMBERING' => 'N',
		'PAGER_DESC_NUMBERING_CACHE_TIME' => '36000',
		'PAGER_SHOW_ALL' => 'N',
		'PAGER_SHOW_ALWAYS' => 'N',
		'PAGER_TEMPLATE' => '.default',
		'PAGER_TITLE' => 'Новости',
		'PREVIEW_TRUNCATE_LEN' => '',
		'SEF_FOLDER' => '/info/',
		'SEF_MODE' => 'Y',
		'SEF_URL_TEMPLATES' => ['detail'=>'#SECTION_CODE_PATH#/#ELEMENT_CODE#/','news'=>'','search'=>'search/','section'=>'#SECTION_CODE_PATH#/' ],
		'SET_LAST_MODIFIED' => 'N',
		'SET_STATUS_404' => 'Y',
		'SET_TITLE' => 'Y',
		'SHOW_404' => 'Y',
		'SORT_BY1' => 'ACTIVE_FROM',
		'SORT_BY2' => 'SORT',
		'SORT_ORDER1' => 'DESC',
		'SORT_ORDER2' => 'ASC',
		'STRICT_SECTION_CHECK' => 'N',
		'USE_CATEGORIES' => 'N',
		'USE_FILTER' => 'N',
		'USE_PERMISSIONS' => 'N',
		'USE_RATING' => 'N',
		'USE_REVIEW' => 'N',
		'USE_RSS' => 'N',
		'USE_SEARCH' => 'N',
		'USE_SHARE' => 'N'
	]
);

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
