<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die;
}

use Bitrix\Main\Context;

$request = Context::getCurrent()->getRequest();

$arSite = CSite::getByID(SITE_ID)->fetch();

$arResult['SHARE_URL'] = ($request->isHttps() ? 'https' : 'http') . '://' . SITE_SERVER_NAME . $request->getRequestUri();

foreach ($arResult['PROPERTIES']['SOCIAL_LINK']['VALUE'] as &$arValue) {
	$arSubValue = $arValue['SUB_VALUES'];
	$arImage = CFile::GetFileArray($arSubValue['LINK_IMAGE']['VALUE']);
	$arValue['SUB_VALUES']['LINK_IMAGE']['IMAGE'] = $arImage;

	$arValue['SUB_VALUES']['LINK_URL']['~VALUE'] = CComponentEngine::makePathFromTemplate(
		$arSubValue['LINK_URL']['~VALUE'],
		[
			'TITLE' => urlencode($arSite['NAME'] . '. ' . $arParams['PAGE_TITLE']),
			'URL' => urlencode($arResult['SHARE_URL'])
		]
	);
}
