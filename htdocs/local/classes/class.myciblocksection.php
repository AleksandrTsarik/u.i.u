<?php
include_once(realpath($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php'));

class MyCIBlockSection
{
	
	function OnAfterIBlockSectionAddHandler(&$arFields)
	{
		if (!$arFields['RESULT']) {
			return $arFields;
		}

		switch ($arFields['IBLOCK_ID']) {
			case CIBlockTools::getIBlockId('party'):
			case CIBlockTools::getIBlockId('info'):
			case CIBlockTools::getIBlockId('regions'):
			case CIBlockTools::getIBlockId('more'):
			case CIBlockTools::getIBlockId('news'):
			case CIBlockTools::getIBlockId('docs'):
				// создать копию секции в блоке предпросмотра
				$iblockCode = self::getIBlockCode($arFields['IBLOCK_ID']);
				$arPreview = $arFields;
				$arPreview['IBLOCK_ID'] = CIBlockTools::getIBlockId($iblockCode . '_preview');
				$arPreview['ACTIVE'] = 'Y';
				$arPreview['UF_ORIGINAL_ID'] = $arFields['ID'];

				$obSection = new CIBlockSection();
				$obSection->add($arPreview);

				self::clearNewsCache();

				break;
		}

		return $arFields;
	}

	function OnBeforeIBlockSectionUpdateHandler(&$arFields)
	{
		switch ($arFields['IBLOCK_ID']) {
			case CIBlockTools::getIBlockId('party'):
			case CIBlockTools::getIBlockId('info'):
				// защита от изменения символьного кода для расписания сезона, формы отправки эссе и историй успеха
				$arSection = CIBlockSection::getById($arFields['ID'])->fetch();

				if ($arSection) {
					// защита от изменения символьного кода для расписания сезона и историй успеха
					if (
						(
							($arSection['CODE'] == 'timetable' && $arFields['CODE'] != 'timetable') ||
							($arSection['CODE'] == 'essay' && $arFields['CODE'] != 'essay') &&
							$arSection['IBLOCK_CODE'] == 'party')
						||
						($arSection['CODE'] == 'stories' && $arFields['CODE'] != 'stories' && $arSection['IBLOCK_CODE'] == 'info')
					) {
						$arFields['CODE'] = $arSection['CODE'];
					}
				}

				break;
		}

		return $arFields;
	}

	function OnAfterIBlockSectionUpdateHandler(&$arFields)
	{
		if (!$arFields['RESULT']) {
			return $arFields;
		}

		switch ($arFields['IBLOCK_ID']) {
			case CIBlockTools::getIBlockId('party'):
			case CIBlockTools::getIBlockId('info'):
			case CIBlockTools::getIBlockId('regions'):
			case CIBlockTools::getIBlockId('more'):
			case CIBlockTools::getIBlockId('news'):
			case CIBlockTools::getIBlockId('docs'):
				// обновить копию секции в блоке предпросмотра
				$iblockCode = self::getIBlockCode($arFields['IBLOCK_ID']);
				$arSection = CIBlockSection::getList(
					['ID' => 'ASC'],
					[
						'IBLOCK_ID' => CIBlockTools::getIBlockId($iblockCode . '_preview'),
						'UF_ORIGINAL_ID' => $arFields['ID']
					]
				)->fetch();
				if ($arSection) {
					$arPreview = $arFields;
					$arPreview['IBLOCK_ID'] = CIBlockTools::getIBlockId($iblockCode . '_preview');
					$arPreview['ACTIVE'] = 'Y';

					$obSection = new CIBlockSection();
					$obSection->update($arSection['ID'], $arPreview);
				} else {
					self::OnAfterIBlockSectionAddHandler($arFields);
				}

				self::clearNewsCache();

				break;
		}

		return $arFields;
	}

	function OnBeforeIBlockSectionDeleteHandler($ID)
	{
		global $APPLICATION;

		$arSection = CIBlockSection::getById($ID)->fetch();
		$iblockCode = $arSection['IBLOCK_CODE'];

		switch ($arSection['IBLOCK_ID']) {
			case CIBlockTools::getIBlockId('party'):
			case CIBlockTools::getIBlockId('info'):
			case CIBlockTools::getIBlockId('regions'):
			case CIBlockTools::getIBlockId('more'):
			case CIBlockTools::getIBlockId('news'):
			case CIBlockTools::getIBlockId('docs'):
				// защита от удаления секций расписания сезона, формы отправки эссе и историй успеха
				if (
					(($arSection['CODE'] == 'timetable' || $arSection['CODE'] == 'essay') && $arSection['IBLOCK_CODE'] == 'party')
					||
					($arSection['CODE'] == 'stories' && $arSection['IBLOCK_CODE'] == 'info')
				) {
					$APPLICATION->throwException('Нельзя удалить этот раздел.');
					return false;
				}

				// удалить копию секции в блоке предпросмотра
				$arSection = CIBlockSection::getList(
					['ID' => 'ASC'],
					[
						'IBLOCK_ID' => CIBlockTools::getIBlockId($iblockCode . '_preview'),
						'UF_ORIGINAL_ID' => $ID
					]
				)->fetch();

				CIBlockSection::delete($arSection['ID']);

				self::clearNewsCache();

				break;
		}
	}

	function clearNewsCache()
	{
		$cache = \Bitrix\Main\Data\Cache::createInstance();
		$cache->clean('news.list');
		$cache->clean('news.detail');
	}

	function getIBlockCode($iblockId)
	{
		$arIBlock = CIBlock::getById($iblockId)->fetch();

		return $arIBlock['CODE'];
	}
}
