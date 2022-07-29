<?php

use Bitrix\Main\Application,
	Bitrix\Iblock\InheritedProperty\ElementValues;

include_once(realpath($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php'));

class MyHelpers
{
	function setSEOMetaData($url)
	{
		static $arSEO;

		if (empty($arSEO)) {
			$arSEO = [];

			$iblockId = CIBlockTools::getIBlockId('seo');
			$cacheTime = 604800;
			$cacheID = 'getSEOText_' . $iblockId;
			$cachePath = '/getSEOText/';
			$obCache = new CPHPCache();

			if ($obCache->InitCache($cacheTime, $cacheID, $cachePath)) {
				$arSEO = $obCache->getVars();
			} elseif ($obCache->StartDataCache()) {

				CModule::IncludeModule('iblock');

				$rsElements = CIBlockElement::GetList(
					[],
					[
						'IBLOCK_ID' => $iblockId,
						'ACTIVE' => 'Y'
					]
				);
				while ($arElement = $rsElements->fetch()) {
					$iprops = new ElementValues($iblockId, $arElement['ID']);
					$arValues = $iprops->getValues();

					$arSEO[$arElement['NAME']] = [
						'title' => $arValues['ELEMENT_META_TITLE'],
						'description' => $arValues['ELEMENT_META_DESCRIPTION'],
						'keywords' => $arValues['ELEMENT_META_KEYWORDS']
					];
				}
				$obCache->EndDataCache($arSEO);
			}

			if (key_exists($url, $arSEO)) {
				global $APPLICATION;

				$APPLICATION->setPageProperty('title', $arSEO[$url]['title']);
				$APPLICATION->setPageProperty('description', $arSEO[$url]['description']);
				$APPLICATION->setPageProperty('keywords', $arSEO[$url]['keywords']);
			}
		}
	}

	function show404Page() {
		if (!defined('ERROR_404')) {
			define('ERROR_404', 'Y');
		}

		\CHTTP::setStatus('404 Not Found');

		require(Application::getDocumentRoot() . '/404.php');
		die;
	}

	public static function getFileTypeByExtension($extension)
	{
		switch(strtolower($extension))
		{
			case 'jpe':
			case 'jpg':
			case 'jpeg':
			case 'png':
			case 'webp':
			case 'gif':
			case 'bmp':
				return 'image';

			case 'avi':
			case 'wmv':
			case 'mp4':
			case 'mov':
			case 'webm':
			case 'flv':
			case 'm4v':
			case 'mkv':
			case 'vob':
			case '3gp':
			case 'ogv':
			case 'h264':
				return 'video';

			case 'doc':
			case 'docx':
			case 'txt':
			case 'odt':
			case 'ods':
			case 'rtf':
				return 'doc';
			case 'ppt':
			case 'pptx':
				return 'ppt';
			case 'xls':
			case 'xlsx':
				return 'xls';

			case 'pdf':
				return 'pdf';

			case 'zip':
			case 'rar':
			case 'tar':
			case 'gz':
			case 'bz2':
			case 'tgz':
			case '7z':
				return 'archive';

			case 'php':
			case 'js':
			case 'css':
			case 'sql':
			case 'pl':
			case 'sh':
				return 'script';

			case 'mp3':
			case 'wav':
				return 'audio';

			case 'vsd':
			case 'vsdx':
			case 'eps':
			case 'ps':
			case 'ai':
			case 'svg':
			case 'svgz':
			case 'cdr':
			case 'swf':
			case 'sketch':
				return 'vector';

				// DOCUMENT
			case 'html':
			case 'htm':
			case 'xml':
			case 'csv':
			case 'fb2':
			case 'djvu':
			case 'epub':
			case 'msg':
			case 'eml':
				// IMAGES
			case 'tif':
			case 'tiff':
			case 'psd':
				// FONTS
			case 'ttf':
			case 'otf':
			case 'eot':
			case 'woff':
			case 'pfa':
				return 'known';
		}

		return 'unknown';
	}

	function formatBytes($bytes, $decimals = 2)
	{
		$sz = 'BKMGTP';
		$factor = floor((strlen($bytes) - 1) / 3);
		return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor] . 'b';
	}
}
