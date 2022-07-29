<?php
include_once(realpath($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php'));

class MyCIBlockElement
{
	function OnBeforeIBlockElementAddHandler(&$arFields)
	{
		switch ($arFields['IBLOCK_ID']) {
			case CIBlockTools::getIBlockId('media'):
				if (!$arFields['DETAIL_PICTURE']) {
					$youtubeCode = reset($arFields['PROPERTY_VALUES'][CIBlockTools::getPropertyId('media', 'YOUTUBE_CODE')])['VALUE'];
					if (!empty($youtubeCode)) {
						// получаем превью с ютуба
						preg_match('/"https:\/\/www\.youtube.com\/embed\/([^"]*)"/', $youtubeCode, $matches);
						$preview = file_get_contents('https://img.youtube.com/vi/' . $matches[1] . '/hqdefault.jpg');
						if ($preview) {
							$tempName = CTempFile::GetAbsoluteRoot() . '/hqdefault.jpg';
							@unlink($tempName);
							file_put_contents($tempName, $preview);
							$arFields['PREVIEW_PICTURE'] = CFile::MakeFileArray($tempName);
						}
					}
				}

				break;
			default:
				break;
		}
	}

	function OnAfterIBlockElementAddHandler($arFields)
	{
		// при первом сохранении записываем в элемент для предпросмотра id оригинала
		switch ($arFields['IBLOCK_ID']) {
			case CIBlockTools::getIBlockId('party'):
			case CIBlockTools::getIBlockId('info'):
			case CIBlockTools::getIBlockId('regions'):
			case CIBlockTools::getIBlockId('more'):
			case CIBlockTools::getIBlockId('news'):
			case CIBlockTools::getIBlockId('docs'):
				$arSection = CIBlockSection::getById($arFields['IBLOCK_SECTION_ID'])->fetch();
				$iblockCode = MyCIBlockSection::getIBlockCode($arSection['IBLOCK_ID']);

				$arPreviewElement = CIBlockElement::getList(
					[],
					[
						'IBLOCK_ID' => CIBlockTools::getIBlockId($iblockCode . '_preview'),
						'CODE' => $arFields['CODE']
					]
				)->fetch();

				if ($arPreviewElement) {
					CIBlockElement::SetPropertyValuesEx($arPreviewElement['ID'], false, ['ORIGINAL_ID' => $arFields['ID']]);

					MyCIBlockSection::clearNewsCache();
				}
				// TODO: при удалении переиндексировать расписание и ЧАВО
			default:
				break;
		}
	}

	function OnBeforeIBlockElementUpdateHandler(&$arFields)
	{
		switch ($arFields['IBLOCK_ID']) {
			case CIBlockTools::getIBlockId('media'):
				if (!$arFields['DETAIL_PICTURE']) {
					// получить предыдущее значение, если изменилось - получаем новую картинку превью
					$arElement = CIBlockElement::getList(
						['ID' => 'ASC'],
						[
							'IBLOCK_ID' => $arFields['IBLOCK_ID'],
							'ID' => $arFields['ID']
						],
						false,
						['nPageSize' => 1],
						['ID', 'IBLOCK_ID', 'PROPERTY_YOUTUBE_CODE']
					)->fetch();

					$youtubeCode = reset($arFields['PROPERTY_VALUES'][CIBlockTools::getPropertyId('media', 'YOUTUBE_CODE')])['VALUE'];
					if ($youtubeCode && $youtubeCode != $arElement['PROPERTY_YOUTUBE_CODE_VALUE']) {
						self::OnBeforeIBlockElementAddHandler($arFields);
					}
				}

				break;
			default;
				break;
		}
	}

	function OnBeforeIBlockElementDeleteHandler($ID)
	{
		$arElement = CIBlockElement::GetByID($ID)->fetch();

		switch ($arElement['IBLOCK_ID']) {
			case CIBlockTools::getIBlockId('party'):
			case CIBlockTools::getIBlockId('info'):
			case CIBlockTools::getIBlockId('regions'):
			case CIBlockTools::getIBlockId('more'):
			case CIBlockTools::getIBlockId('news'):
			case CIBlockTools::getIBlockId('docs'):
				// удаляем предпросмотр
				$arPreviewElement = CIBlockElement::getList(
					[],
					[
						'IBLOCK_ID' => CIBlockTools::getIBlockId($arElement['IBLOCK_CODE'] . '_preview'),
						'PROPERTY_ORIGINAL_ID' => $ID
					]
				)->fetch();

				if ($arPreviewElement) {
					CIBlockElement::delete($arPreviewElement['ID']);
				}

				MyCIBlockSection::clearNewsCache();
			default:
				break;
		}
	}
}
