<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die;
}

if (!empty(trim($arResult['~PREVIEW_TEXT']))) {
	echo '<a href="' . trim($arResult['~PREVIEW_TEXT']) . '" target="_blank"><image src="' . $arResult['DETAIL_PICTURE']['SRC'] . '" alt=""></a>';
} else {
	echo '<image src="' . $arResult['DETAIL_PICTURE']['SRC'] . '" alt="">';
}
