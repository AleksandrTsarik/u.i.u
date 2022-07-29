<?php
use Bitrix\Main\Context;
use Bitrix\Main\Loader;

include_once(realpath($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php'));

$request = Context::getCurrent()->getRequest();
// TODO: чистим отсутствующие, и привязаные файлы!

$arRequest = $request->getPostList()->getValues();
$iblockId = $request->getQuery('IBLOCK_ID');

$arRequest['CODE'] = trim($arRequest['CODE']);

$arErrors = [];
if (!$arRequest['IBLOCK_ELEMENT_SECTION_ID'] && $arRequest['CODE']) {
	$arErrors[] = 'Для предварительного просмотра выберите секцию';
}
if ($arRequest['IBLOCK_ELEMENT_SECTION_ID'] && !$arRequest['CODE']) {
	$arErrors[] = 'Для предварительного просмотра введите уникальный символьный код';
}
if (!$arRequest['IBLOCK_ELEMENT_SECTION_ID'] && !$arRequest['CODE']) {
	$arErrors[] = 'Для предварительного просмотра выберите секцию и введите уникальный символьный код';
}

if (empty($arErrors)) {
	Loader::includeModule('iblock');

	$iblockCode = MyCIBlockSection::getIBlockCode($iblockId);

	// проверка уникальности кода
	$arFilter = [
		'IBLOCK_ID' => CIBlockTools::getIBlockId($iblockCode),
		'CODE' => $arRequest['CODE']
	];
	if ($arRequest['ID']) {
		$arFilter['!=ID'] = $arRequest['ID'];
	}
	$arOriginalElement = CIBlockElement::getList([], $arFilter)->fetch();
	if ($arOriginalElement) {
		$arErrors[] = 'Для предварительного просмотра введите уникальный символьный код';
	}

	$arFilter = [
		'IBLOCK_ID' => CIBlockTools::getIBlockId($iblockCode . '_preview')
	];
	if ($arRequest['ID']) {
		$arFilter['PROPERTY_ORIGINAL_ID'] = $arRequest['ID'];
	} else {
		$arFilter['CODE'] = $arRequest['CODE'];
	}
	$arPreviewElement = CIBlockElement::getList([], $arFilter)->fetch();

	$arPreviewSection = CIBlockSection::getList(
		[],
		[
			'IBLOCK_ID' => CIBlockTools::getIBlockId($iblockCode . '_preview'),
			'UF_ORIGINAL_ID' => $arRequest['IBLOCK_ELEMENT_SECTION_ID']
		]
	)->fetch();

	$el = new CIBlockElement;

	$arPREVIEW_PICTURE = CIBlock::makeFileArray(
		array_key_exists('PREVIEW_PICTURE', $arRequest)? $arRequest['PREVIEW_PICTURE'] : $request->getFile('PREVIEW_PICTURE'),
		$arRequest['PREVIEW_PICTURE_del'] === 'Y',
		$arRequest['PREVIEW_PICTURE_descr'],
		['allow_file_id' => true]
	);
	if ($arPREVIEW_PICTURE['error'] == 0) {
		$arPREVIEW_PICTURE['COPY_FILE'] = 'Y';
	}

	$arDETAIL_PICTURE = CIBlock::makeFileArray(
		array_key_exists('DETAIL_PICTURE', $arRequest) ? $arRequest['DETAIL_PICTURE'] : $request->getFile('DETAIL_PICTURE'),
		$arRequest['DETAIL_PICTURE_del'] === 'Y',
		$arRequest['DETAIL_PICTURE_descr'],
		['allow_file_id' => true]
	);
	if ($arDETAIL_PICTURE['error'] == 0) {
		$arDETAIL_PICTURE['COPY_FILE'] = 'Y';
	}

	$arFields = [
		'IBLOCK_ID' => CIBlockTools::getIBlockId($iblockCode . '_preview'),
		'ACTIVE' => 'Y',
		'NAME' => $arRequest['NAME'],
		'CODE' => $arRequest['CODE'],
		'IBLOCK_SECTION' => $arPreviewSection['ID'],
		'SORT' => $arRequest['SORT'],
		'PREVIEW_TEXT_TYPE' => $arRequest['PREVIEW_TEXT_TYPE'],
		'PREVIEW_TEXT' => $arRequest['PREVIEW_TEXT'],
		'PREVIEW_PICTURE' => $arPREVIEW_PICTURE,
		'DETAIL_TEXT_TYPE' => $arRequest['DETAIL_TEXT_TYPE'],
		'DETAIL_TEXT' => $arRequest['DETAIL_TEXT'],
		'DETAIL_PICTURE' => $arDETAIL_PICTURE
	];

	if (!$arPreviewElement) {
		$arFields['PROPERTY_VALUES'] = [
			'ORIGINAL_ID' => $arRequest['ID']
		];

		$ID = $el->add($arFields, false, true, true);
		if (!$ID) {
			$arErrors[] = $el->LAST_ERROR;
		}
	} else {
		$ID = $arPreviewElement['ID'];
		if (!$el->update($ID, $arFields, false, false, true)) {
			$arErrors[] = $el->LAST_ERROR;
		}
	}

	if (empty($arErrors)) {
		MyCIBlockSection::clearNewsCache();

		$arPreview = CIBlockElement::getById($ID)->getNext();
		header('Content-type: application/json');
		echo json_encode(['url' => $arPreview['~DETAIL_PAGE_URL']]);
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