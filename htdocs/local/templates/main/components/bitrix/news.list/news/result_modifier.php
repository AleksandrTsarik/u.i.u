<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die;
}

$arResult['IBLOCK'] = CIBlock::getById($arParams['IBLOCK_ID'])->getNext();
