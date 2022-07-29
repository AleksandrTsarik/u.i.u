<?php
use Bitrix\Main\Context,
	Bitrix\Main\Loader;

include_once(realpath($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php'));

$request = Context::getCurrent()->getRequest();

$elementId = intval($request->getQuery('ELEMENT_ID'));
$replay = trim($request->getPost('DETAIL_TEXT'));

$arErrors = [];

if ($elementId <= 0) {
	$arErrors[] = 'Неверные параметры запроса';
} elseif (!$replay) {
	$arErrors[] = 'Введите текст ответа';
} else {
	Loader::includeModule('iblock');
	$arElement = CIBlockElement::getList(
		[],
		[
			'IBLOCK_ID' =>CIBlockTools::getIBlockId('feedback'),
			'ID' => $elementId
		],
		false,
		['nTopCount' => 1],
		['PROPERTY_EMAIL']
	)->getNext();

	if (!$arElement) {
		$arErrors[] = 'Форма не найдена';
	} else {
		$el = new CIBlockElement;

		$el->update($elementId, ['DETAIL_TEXT' => $replay]);

		$arFields = [
			'EMAIL_TO' => $arElement['PROPERTY_EMAIL_VALUE'],
			'EMAIL_FROM' => $USER->getEmail() ? $USER->getEmail() : COption::GetOptionString('main', 'email_from'),
			'TEXT' => $replay
		];

		CEvent::Send('FEEDBACK_REPLAY', SITE_ID, $arFields);

		header('Content-type: application/json');
		echo json_encode(['success' => true]);
	}
}

if (!empty($arErrors)) {
	header('Content-type: application/json');
	echo json_encode(['errors' => array_map(function($str) {
		$str = preg_replace('/<br\s+?\/?>/', "\n", $str);
		$str = preg_replace('/\n\n+/', "\n", $str);
		$str = preg_replace('/\n+$/', '', $str);
		return trim($str);
	}, $arErrors)]);
}

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_after.php');
