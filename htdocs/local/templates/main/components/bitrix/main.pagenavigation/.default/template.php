<?php

use Bitrix\Main\Context,
	Bitrix\Main\Web\Uri;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die;
}

if (!$arResult['ALL_RECORDS']) {
	if ($arResult['RECORD_COUNT'] == 0 || ($arResult['PAGE_COUNT'] == 1 && $arResult['SHOW_ALL'] == false)) {
		return;
	}
}

$pages = 4;

$request = Context::getCurrent()->getRequest();
$_start = intval($request->getQuery('start'));

if ($_start <= 0) {
	$start = $arResult['CURRENT_PAGE'];
} elseif ($arResult['CURRENT_PAGE'] == $arResult['PAGE_COUNT']) {
	$start = $arResult['PAGE_COUNT'] - $pages + 1;
} elseif ($arResult['CURRENT_PAGE'] == $_start + $pages - 1) {
	$start = $_start + 1;
} elseif ($arResult['CURRENT_PAGE'] == $_start) {
	$start = $_start - 1;
} elseif ($arResult['CURRENT_PAGE'] > $_start) {
	$start = $_start;
}
if ($start <= 0) {
	$start = 1;
}

$end = $start + $pages - 1 > $arResult['PAGE_COUNT'] ? $arResult['PAGE_COUNT'] : $start + $pages - 1;

$uri = new Uri($request->getRequestUri());
?>
<div class="b-pagination">
	<div class="b-pagination__list">
		<?php for ($pageNum = $start; $pageNum <= $end; $pageNum++): ?>
			<?php if ($arResult['CURRENT_PAGE'] == $pageNum): ?>
				<div class="b-pagination__item _active"><a><?=$pageNum?></a></div>
			<?php else: ?>
				<div class="b-pagination__item">
					<a href="<?=($uri->addParams([$arResult['ID'] => 'page-' . $pageNum, 'start' => $start])->getUri())?>"><?=$pageNum?></a>
				</div>
			<?php endif; ?>
		<?php endfor; ?>
		<?php if ($arResult['CURRENT_PAGE'] < $arResult['PAGE_COUNT']): ?>
			<div class="b-pagination__item">
				<a href="<?=($uri->addParams([$arResult['ID'] => 'page-' . ($arResult['CURRENT_PAGE'] + 1), 'start' => $start])->getUri())?>">Дальше</a>
			</div>
		<?php endif; ?>
	</div>
</div>
