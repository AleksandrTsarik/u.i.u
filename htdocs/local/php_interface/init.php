<?php

use Bitrix\Main\Context;

// автоподключение классов из папки /local/classes/
spl_autoload_register(function ($sClassName){
	$sFileName = $_SERVER['DOCUMENT_ROOT'] . '/local/classes/class.' . strtolower($sClassName) . '.php';
	if (file_exists($sFileName)) {
		require_once($sFileName);
	}
});

function d($mixed = '', $showLine = false)
{
	echo '<pre>';
	if ($showLine) {
		$trace = debug_backtrace();
		echo 'file => ' . $trace[0]['file'] . PHP_EOL;
		echo 'line => ' . $trace[0]['line'] . PHP_EOL;
	}
	print_r($mixed);
	echo '</pre>';
}

// Выводим кнопку предпросмотра
AddEventHandler('main', 'OnAdminContextMenuShow', 'adminContextMenuShow');
function adminContextMenuShow(&$items, &$additionalItems)
{
	$request = Context::getCurrent()->getRequest();

	if ($request->getRequestMethod() == 'GET' && $request->getRequestedPage() == '/bitrix/admin/iblock_element_edit.php') {
		$iblockId = $request->getQuery('IBLOCK_ID');
		$elementId = $request->getQuery('ID');

		switch ($iblockId) {
			case CIBlockTools::getIBlockId('party'):
			case CIBlockTools::getIBlockId('info'):
			case CIBlockTools::getIBlockId('regions'):
			case CIBlockTools::getIBlockId('more'):
			case CIBlockTools::getIBlockId('news'):
			case CIBlockTools::getIBlockId('docs'):
				$itemsT = [
					[
						'TEXT'  => 'Предварительный просмотр',
						'LINK'  => "javascript:newsPreview('$iblockId');",
						'TITLE' => 'Предварительный просмотр',
						'ICON'  => ''
					]
				];

				$items = array_merge($items, $itemsT);

				break;
			case CIBlockTools::getIBlockId('feedback'):
				$itemsT = [
					[
						'TEXT'  => 'Сохранить и отправить ответ',
						'LINK'  => "javascript:feedbackReplay('$elementId');",
						'TITLE' => 'Сохранить и отправить ответ',
						'ICON'  => ''
					]
				];

				$items = array_merge($items, $itemsT);

				break;
			default:
				// do nothing
		}
	}
}

function OnBuildGlobalMenuHandler(&$aGlobalMenu, &$aModuleMenu)
{
	$aModuleMenu[] = [
		'parent_menu' => 'global_menu_settings',
		'section'     => 'Общие настройки проекта',
		'sort'        => 10000,
		'url'         => '/bitrix/admin/uiu_admin.php',
		'text'        => 'Настройки проекта "Умницы и умники"',
		'items_id'    => 'uiu_admin',
		'items'       => []
	];
}

class SearchHandler
{
	function BeforeIndexHandler($arFields)
	{
		if ($arFields['MODULE_ID'] != 'iblock') {
			return $arFields;
		}

		switch ($arFields['PARAM2']) {
			case CIBlockTools::getIBlockId('faq'):
				$arElement = CIBlockElement::getList(
					['SORT' => 'ASC'],
					[
						'IBLOCK_ID' => $arFields['PARAM2'],
						'ACTIVE' => 'Y'
					],
					false,
					['nTopCount' => 1]
				)->getNext();

				if ($arElement['ID'] != $arFields['ITEM_ID']) {
					$arFields['TITLE'] = '';
					$arFields['BODY'] = '';

					// reindex $arElement
					CSearch::Index(
						'iblock',
						$arElement['ID'],
						[]
					);

					break;
				} else {
					$arFields['BODY'] = $arFields['TITLE'] . "\n" . $arFields['BODY'];

					// collect all active
					$rsElements = CIBlockElement::getList(
						['SORT' => 'ASC'],
						[
							'IBLOCK_ID' => $arFields['PARAM2'],
							'ACTIVE' => 'Y',
							'!=ID' => $arElement['ID']
						]
					);
					while ($_arElement = $rsElements->getNext()) {
						$arFields['BODY'] .= "\n" . CSearch::KillTags($_arElement['~NAME']) . "\n" . CSearch::KillTags($_arElement['~PREVIEW_TEXT']);
					}

					$arIBlock = CIBlock::getById($arFields['PARAM2'])->getNext();
					$arFields['TITLE'] = $arIBlock['~NAME'];
				}

				break;
			case CIBlockTools::getIBlockId('timetable'):
				// TODO:

				break;
			case CIBlockTools::getIBlockId('media'):
				// TODO:

				break;
			case CIBlockTools::getIBlockId('party'):
			case CIBlockTools::getIBlockId('info'):
			case CIBlockTools::getIBlockId('regions'):
			case CIBlockTools::getIBlockId('more'):
			case CIBlockTools::getIBlockId('docs'):
				// TODO:

				break;
			default:
				break;
		}

		return $arFields;
	}
}

// IBlock events
AddEventHandler('iblock', 'OnAfterIBlockAdd', ['CIBlockTools', 'Update']);
AddEventHandler('iblock', 'OnAfterIBlockUpdate', ['CIBlockTools', 'Update']);
AddEventHandler('iblock', 'OnBeforeIBlockDelete', ['CIBlockTools', 'Update']);

// IBlock property events
AddEventHandler('iblock', 'OnAfterIBlockPropertyAdd', ['CIBlockTools', 'Update']);
AddEventHandler('iblock', 'OnAfterIBlockPropertyUpdate', ['CIBlockTools', 'Update']);
AddEventHandler('iblock', 'OnBeforeIBlockPropertyDelete', ['CIBlockTools', 'Update']);

AddEventHandler('iblock', 'OnAfterIBlockSectionAdd', ['MyCIBlockSection', 'OnAfterIBlockSectionAddHandler']);
AddEventHandler('iblock', 'OnBeforeIBlockSectionUpdate', ['MyCIBlockSection', 'OnBeforeIBlockSectionUpdateHandler']);
AddEventHandler('iblock', 'OnAfterIBlockSectionUpdate', ['MyCIBlockSection', 'OnAfterIBlockSectionUpdateHandler']);
AddEventHandler('iblock', 'OnBeforeIBlockSectionDelete', ['MyCIBlockSection', 'OnBeforeIBlockSectionDeleteHandler']);

AddEventHandler('iblock', 'OnBeforeIBlockElementAdd', ['MyCIBlockElement', 'OnBeforeIBlockElementAddHandler']);
AddEventHandler('iblock', 'OnAfterIBlockElementAdd', ['MyCIBlockElement', 'OnAfterIBlockElementAddHandler']);
AddEventHandler('iblock', 'OnBeforeIBlockElementUpdate', ['MyCIBlockElement', 'OnBeforeIBlockElementUpdateHandler']);
AddEventHandler('iblock', 'OnBeforeIBlockElementDelete', ['MyCIBlockElement', 'OnBeforeIBlockElementDeleteHandler']);

// добавление пункта "настройки"
AddEventHandler('main', 'OnBuildGlobalMenu', 'OnBuildGlobalMenuHandler');

AddEventHandler('search', 'BeforeIndex', ['SearchHandler', 'BeforeIndexHandler']);
