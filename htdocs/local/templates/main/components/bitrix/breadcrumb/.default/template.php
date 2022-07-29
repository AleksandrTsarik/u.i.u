<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die;
}

/**
 * @global CMain $APPLICATION
 */

global $APPLICATION;

// delayed function must return a string
if (empty($arResult)) {
	return '';
}

$strReturn = '';

$strReturn .= '<section class="b-breadcrumbs"><div class="content-wrapper"><div class="b-breadcrumbs__wrapper">';

$itemSize = count($arResult);
for ($index = 0; $index < $itemSize; $index++) {
	$title = htmlspecialcharsex($arResult[$index]['TITLE']);

	if ($arResult[$index]['LINK'] != '' && $index != $itemSize - 1) {
		$strReturn .= '
			<span class="b-breadcrumbs__item" itemprop="itemListElement" itemscope="" itemtype="https://schema.org/ListItem">
				<a href="' . $arResult[$index]['LINK'] . '" itemprop="item">' . $title . '<meta itemprop="position" content="' . $index . '"></a>
			</span>';
	} else {
		$strReturn .= '
			<span class="b-breadcrumbs__item" itemprop="itemListElement" itemscope="" itemtype="https://schema.org/ListItem">'
				. $title . '<meta itemprop="position" content="' . $index . '">
			</span>';
	}
}

$strReturn .= '</div></div></section>';

return $strReturn;
