<?php
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');

$APPLICATION->IncludeComponent(
	'bitrix:news.detail',
	'contacts',
	[
		'DISPLAY_DATE' => 'N',
		'DISPLAY_NAME' => 'N',
		'DISPLAY_PICTURE' => 'N',
		'DISPLAY_PREVIEW_TEXT' => 'N',
		'IBLOCK_TYPE' => 'content',
		'IBLOCK_ID' => CIBlockTools::getIBlockId('contacts'),
		'ELEMENT_CODE' => 'contacts',
		'FIELD_CODE' => [''],
		'PROPERTY_CODE' => ['SOCIAL_LINK', 'PHONE', 'EMAIL', 'ADRESS', '']
	],
	$component
);

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
