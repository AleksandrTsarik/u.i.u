<?php
use Bitrix\Main\Context;

include_once(realpath($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php'));

$request = Context::getCurrent()->getRequest();
$actoin = $request->getQuery('action');

switch ($action) {
	case 'feedback':
		$APPLICATION->IncludeComponent(
			'uiu:feedback',
			'',
			[
				'USE_CAPTCHA' => 'N',
				'OK_TEXT' => 'Спасибо, ваше сообщение отправлено.',
				'REQUIRED_FIELDS' => ['NAME', 'EMAIL', 'MESSAGE', 'PHONE'],
				'EVENT_MESSAGE_ID' => [7]
			]
		);

		break;
	default:
		$APPLICATION->RestartBuffer();
		echo json_encode(false);
}