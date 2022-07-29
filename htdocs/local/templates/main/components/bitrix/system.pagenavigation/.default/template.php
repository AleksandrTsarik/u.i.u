<?php

use Bitrix\Main\Context,
	Bitrix\Main\Web\Uri;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die;
}

if (!$arResult['NavShowAlways']) {
	if ($arResult['NavRecordCount'] == 0 || ($arResult['NavPageCount'] == 1 && $arResult['NavShowAll'] == false)) {
		return;
	}
}

$pages = 4;

$request = Context::getCurrent()->getRequest();
$_start = intval($request->getQuery('start'));

if ($_start <= 0) {
	$start = $arResult['NavPageNomer'];
} elseif ($arResult['NavPageNomer'] == $arResult['NavPageCount']) {
	$start = $arResult['NavPageCount'] - $pages + 1;
} elseif ($arResult['NavPageNomer'] == $_start + $pages - 1) {
	$start = $_start + 1;
} elseif ($arResult['NavPageNomer'] == $_start) {
	$start = $_start - 1;
} elseif ($arResult['NavPageNomer'] > $_start) {
	$start = $_start;
}
if ($start <= 0) {
	$start = 1;
}

$end = $start + $pages - 1 > $arResult['NavPageCount'] ? $arResult['NavPageCount'] : $start + $pages - 1;

$uri = new Uri($arResult['sUrlPath'] . (!empty($arResult['NavQueryString']) ? '?' . htmlspecialchars_decode($arResult['NavQueryString']) : ''));
?>
<div class="b-pagination">
	<div class="b-pagination__list">
		<?php for ($pageNum = $start; $pageNum <= $end; $pageNum++): ?>
			<?php if ($arResult['NavPageNomer'] == $pageNum): ?>
				<div class="b-pagination__item _active"><a><?=$pageNum?></a></div>
			<?php else: ?>
				<div class="b-pagination__item">
					<a href="<?=($uri->addParams(['PAGEN_' . $arResult['NavNum'] => $pageNum, 'start' => $start])->getUri())?>"><?=$pageNum?></a>
				</div>
			<?php endif; ?>
		<?php endfor; ?>
		<?php if ($arResult['NavPageNomer'] < $arResult['NavPageCount']): ?>
			<div class="b-pagination__item">
				<a href="<?=($uri->addParams(['PAGEN_' . $arResult['NavNum'] => ($arResult['NavPageNomer'] + 1), 'start' => $start])->getUri())?>">Дальше</a>
			</div>
		<?php endif; ?>
	</div>
</div>
