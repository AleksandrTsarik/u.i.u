<?php
use Bitrix\Main\Loader,
	Bitrix\Main\Context;

include_once(realpath($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php'));

$request = Context::getCurrent()->getRequest();

Loader::includeModule('iblock');

global $APPLICATION;

$arSiteMap = [];

$rsIBlocks = GetIBlockList(
	'content',
	[
		CIBlockTools::getIBlockId('party'),
		CIBlockTools::getIBlockId('info'),
		CIBlockTools::getIBlockId('regions'),
		CIBlockTools::getIBlockId('more'),
	],
	[],
	['sort' => 'asc']
);

while ($arIBlock = $rsIBlocks->getNext()) {
	// first level sections
	$arSubMenu = [];
	$rsSections = CIBlockSection::getList(
		['SORT' => 'ASC'],
		[
			'IBLOCK_ID' => $arIBlock['ID'],
			'ACTIVE' => 'Y',
			'DEPTH_LEVEL' => 1
		]
	);
	while ($arSection = $rsSections->getNext()) {
		$arSubMenu[] = [
			'NAME' => $arSection['~NAME'],
			'LINK' => $arSection['SECTION_PAGE_URL']
		];
	}

	$arSiteMap[] = [
		'NAME' => mb_strtoupper($arIBlock['~NAME']),
		'SUBMENU' => $arSubMenu
	];
}

// additional links
$rsIBlocks = GetIBlockList(
	'content',
	[
		CIBlockTools::getIBlockId('media'),
		CIBlockTools::getIBlockId('faq'),
		CIBlockTools::getIBlockId('contacts')
	],
	[],
	['sort' => 'asc']
);

while ($arIBlock = $rsIBlocks->getNext()) {
	$arSiteMap[] = [
		'NAME' => mb_strtoupper($arIBlock['~NAME']),
		'LINK' => $arIBlock['~LIST_PAGE_URL'] ? $arIBlock['~LIST_PAGE_URL'] : '/' . $arIBlock['CODE'] . '/'
	];
}

if ($request->getQuery('mode') == 'xml') {
	
} else {
	require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');
	$APPLICATION->addChainItem('Карта сайта', '/sitemap/');
	?>
	<ul>
		<?php foreach ($arSiteMap as $arMenuItem): ?>
			<li>
				<?php if ($arMenuItem['LINK']): ?>
					<a href="<?=$arMenuItem['LINK']?>"><?=$arMenuItem['NAME']?></a>
				<?php else: ?>
					<span><?=$arMenuItem['NAME']?></a></span>
				<?php endif; ?>
				<?php if (!empty($arMenuItem['SUBMENU'])): ?>
					<ul>
						<?php foreach ($arMenuItem['SUBMENU'] as $arSubMenuItem): ?>
							<li><a href="<?=$arSubMenuItem['LINK']?>"><?=$arSubMenuItem['NAME']?></a></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php
	require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
}
