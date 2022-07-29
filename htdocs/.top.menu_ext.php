<?php

use Bitrix\Main\Loader;

Loader::includeModule('iblock');

global $APPLICATION;

$rsIBlocks = GetIBlockList(
	'content',
	['party', 'info', 'regions', 'more'],
	[],
	['SORT' => 'ASC']
);

while ($arIBlock = $rsIBlocks->getNext()) {
	$aMenuLinksExt = [];

	$aMenuLinksExt[] = [
		$arIBlock['DESCRIPTION'],
		$arIBlock['LIST_PAGE_URL'],
		[
			$arIBlock['LIST_PAGE_URL']
		],
		[
			'FROM_IBLOCK' => true,
			'IS_PARENT' => true,
			'DEPTH_LEVEL' => 0
		]
	];

	$aMenuLinksExt = array_merge(
			$aMenuLinksExt,
			$APPLICATION->IncludeComponent(
			'bitrix:menu.sections',
			'',
			[
				'IBLOCK_TYPE' => 'content',
				'IBLOCK_ID' => $arIBlock['ID'],
				'DEPTH_LEVEL' => 1
			]
		)
	);

	$aMenuLinks = array_merge($aMenuLinks, $aMenuLinksExt);
}
