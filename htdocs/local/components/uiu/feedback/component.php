<?php
use Bitrix\Main\Context,
	Bitrix\Main\Loader;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die;
}

$request = Context::getCurrent()->getRequest();

$arParams['USE_CAPTCHA'] = (($arParams['USE_CAPTCHA'] != 'N' && !$USER->IsAuthorized()) ? 'Y' : 'N');
$arParams['EVENT_NAME'] = trim($arParams['EVENT_NAME']);
if ($arParams['EVENT_NAME'] == '') {
	$arParams['EVENT_NAME'] = 'FEEDBACK_FORM';
}
$arParams['EMAIL_TO'] = trim($arParams['EMAIL_TO']);
if ($arParams['EMAIL_TO'] == '') {
	$arParams['EMAIL_TO'] = COption::GetOptionString('main', 'email_from');
}
$arParams['OK_TEXT'] = trim($arParams['OK_TEXT']);
if ($arParams['OK_TEXT'] == '') {
	$arParams['OK_TEXT'] = GetMessage('MF_OK_MESSAGE');
}

if ($request->isPost() && $request->getPost('submit') != '') {
	$arResult['ERROR_MESSAGE'] = [];
	if (empty($arParams['REQUIRED_FIELDS']) || !in_array('NONE', $arParams['REQUIRED_FIELDS'])) {
		if ((empty($arParams['REQUIRED_FIELDS']) || in_array('NAME', $arParams['REQUIRED_FIELDS'])) && mb_strlen($request->getPost('user_name')) <= 1) {
			$arResult['ERROR_MESSAGE']['user_name'] = GetMessage('MF_REQ_NAME');
		}
		if ((empty($arParams['REQUIRED_FIELDS']) || in_array('EMAIL', $arParams['REQUIRED_FIELDS'])) && mb_strlen($request->getPost('user_email')) <= 1) {
			$arResult['ERROR_MESSAGE']['user_email'] = GetMessage('MF_REQ_EMAIL');
		}
		if ((empty($arParams['REQUIRED_FIELDS']) || in_array('PHONE', $arParams['REQUIRED_FIELDS'])) && strlen(preg_replace('/\D/', '', $request->getPost('user_phone'))) < 11) {
			$arResult['ERROR_MESSAGE']['user_phone'] = GetMessage('MF_REQ_PHONE');
		}
		if ((empty($arParams['REQUIRED_FIELDS']) || in_array('MESSAGE', $arParams['REQUIRED_FIELDS'])) && mb_strlen($request->getPost('MESSAGE')) <= 3) {
			$arResult['ERROR_MESSAGE']['MESSAGE'] = GetMessage('MF_REQ_MESSAGE');
		}
	}
	if (mb_strlen($request->getPost('user_email')) > 1 && !check_email($request->getPost('user_email'))) {
		$arResult['ERROR_MESSAGE']['user_email'] = GetMessage('MF_EMAIL_NOT_VALID');
	}
	if ($arParams['USE_CAPTCHA'] == 'Y') {
		$captcha_code = $request->getPost('captcha_sid');
		$captcha_word = $request->getPost('captcha_word');
		$cpt = new CCaptcha();
		$captchaPass = COption::GetOptionString('main', 'captcha_password', '');
		if ($captcha_word != '' && $captcha_code != '') {
			if (!$cpt->CheckCodeCrypt($captcha_word, $captcha_code, $captchaPass)) {
				$arResult['ERROR_MESSAGE']['captcha'] = GetMessage('MF_CAPTCHA_WRONG');
			}
		} else {
			$arResult['ERROR_MESSAGE']['captcha'] = GetMessage('MF_CAPTHCA_EMPTY');
		}
	}
	if (empty($arResult['ERROR_MESSAGE'])) {
		// сохраняем форму в инфоблок
		Loader::includeModule('iblock');
		$el = new CIBLockElement;
		$arFields = [
			'IBLOCK_ID' => CIBlockTools::getIBlockId('feedback'),
			'NAME' => $request->getPost('user_name'),
			'PREVIEW_TEXT' => $request->getPost('MESSAGE'),
			'PROPERTY_VALUES' => [
				'EMAIL' => $request->getPost('user_email'),
				'PHONE' => $request->getPost('user_phone')
			]
		];
		$elementId = $el->Add($arFields);

		$_arUsers = array_filter(unserialize(COption::getOptionString('uiu_custom_settings', 'feedback_users', '[]')), function($a) {
			return !empty($a) && is_array($a) && key_exists('VALUE', $a) && intval($a['VALUE']) > 0;
		});
		$arTo = [];
		if (!empty($_arUsers)) {
			$arToIds = [];
			foreach ($_arUsers as $value) {
				$arToIds[] = $value['VALUE'];
			}
			$rsUsers = CUser::getList(
				$by = 'id',
				$order = 'asc',
				['ID' => implode('|', $arToIds), 'ACTIVE' => 'Y', '!=EMAIL' => false],
				['FIELDS' => 'EMAIL']
			);
			while ($arUser = $rsUsers->getNext()) {
				$arTo[] = $arUser['EMAIL'];
			}
		}
		if (empty($arTo)) {
			$arTo[] = $arParams['EMAIL_TO'];
		}

		$arFields = [
			'AUTHOR' => $request->getPost('user_name'),
			'AUTHOR_EMAIL' => $request->getPost('user_email'),
			'AUTHOR_PHONE' => $request->getPost('user_phone'),
			'EMAIL_TO' => implode(', ', $arTo),
			'TEXT' => $request->getPost('MESSAGE'),
			'LINK' => ($request->isHttps() ? 'https://' : 'http://') . SITE_SERVER_NAME . '/bitrix/admin/' . CIBlock::GetAdminElementEditLink(CIBlockTools::getIBlockId('feedback'), $elementId)
		];

		if (!empty($arParams['EVENT_MESSAGE_ID'])) {
			foreach ($arParams['EVENT_MESSAGE_ID'] as $v) {
				if (intval($v) > 0) {
					CEvent::Send($arParams['EVENT_NAME'], SITE_ID, $arFields, 'N', intval($v));
				}
			}
		} else {
			CEvent::Send($arParams['EVENT_NAME'], SITE_ID, $arFields);
		}

		if ($request->isAjaxRequest()) {
			$APPLICATION->RestartBuffer();
			echo json_encode(['success' => true, 'message' => $arParams['OK_TEXT']]);
			die;
		}
	} else {
		if ($request->isAjaxRequest()) {
			$APPLICATION->RestartBuffer();
			echo json_encode(['success' => false, 'errors' => $arResult['ERROR_MESSAGE']]);
			die;
		}
	}

	$arResult['MESSAGE'] = htmlspecialcharsbx($request->getPost('MESSAGE'));
	$arResult['AUTHOR_NAME'] = htmlspecialcharsbx($request->getPost('user_name'));
	$arResult['AUTHOR_EMAIL'] = htmlspecialcharsbx($request->getPost('user_email'));
	$arResult['AUTHOR_PHONE'] = htmlspecialcharsbx($request->getPost('user_phone'));
}

if (empty($arResult['ERROR_MESSAGE']) && $USER->IsAuthorized()) {
	$arResult['AUTHOR_NAME'] = $USER->GetFormattedName(false);
	$arResult['AUTHOR_EMAIL'] = htmlspecialcharsbx($USER->GetEmail());
}

if ($arParams['USE_CAPTCHA'] == 'Y') {
	$arResult['capCode'] = htmlspecialcharsbx($APPLICATION->CaptchaGetCode());
}

$arResult['IS_AJAX_REQUEST'] = $request->isAjaxRequest();

$this->IncludeComponentTemplate();
