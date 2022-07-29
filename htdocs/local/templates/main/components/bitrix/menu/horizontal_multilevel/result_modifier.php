<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die;
}

if (!empty($arResult)) {
	$topKey = -1;
	$arFormatted = [];

	foreach($arResult as $key => $arItem) {
		if ($arItem['DEPTH_LEVEL'] == 0) {
			$topKey++;
			$arFormatted[$topKey] = $arItem;
		} elseif ($arItem['PERMISSION'] > 'D') {
			$arFormatted[$topKey]['ITEMS'][] = $arItem;
		}
	}

	$arResult = $arFormatted;

	foreach($arResult as &$arItem) {
		foreach($arItem['ITEMS'] as $item) {
			if ($item['SELECTED']) {
				$arItem['SELECTED'] = true;
			}
		}
	}
}
