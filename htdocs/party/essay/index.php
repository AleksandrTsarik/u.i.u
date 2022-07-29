<?php

use Bitrix\Main\Context,
	Bitrix\Main\Loader;

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');

$arResult = [];
$arErrors = [];

$request = Context::getCurrent()->getRequest();

if ($request->isPost() && $request->getPost('submit') == 'Y') {
	$image = $request->getFile('image');
	if (!$image['size']) {
		$arErrors['image'] = 'Добавьте фото';
	} else {
		$info = getimagesize($image['tmp_name']);
		if ($info['mime'] != 'image/jpeg') {
			$arErrors['image'] = 'Неверный формат изображеня. Добавьте фото в формате jpeg';
		}
		if ($info[0] < 800 || $info[1] < 800) {
			$arErrors['image'] = 'Минимальное разрешение изображения 800x800px';
		}
	}
	$lastName = trim($request->getPost('last_name'));
	if (mb_strlen($lastName) < 3) {
		$arErrors['last_name'] = 'Введите фамилию';
	}
	$firstName = trim($request->getPost('first_name'));
	if (mb_strlen($firstName) < 3) {
		$arErrors['first_name'] = 'Введите имя';
	}
	$secondName = trim($request->getPost('second_name'));
	if (mb_strlen($secondName) < 3) {
		$arErrors['second_name'] = 'Введите отчество';
	}
	$email = trim($request->getPost('email'));
	if (!check_email($email)) {
		$arErrors['email'] = 'Введите корректный email';
	}
	$phone = preg_replace('/\D/', '', $request->getPost('phone'));
	if (strlen($phone) != 11) {
		$arErrors['phone'] = 'Номер телефона должен состоять из 11 цифр';
	}
	$zipCode = preg_replace('/\D/', '', $request->getPost('zip_code'));
	if (strlen($zipCode) != 6) {
		$arErrors['zip_code'] = 'Почтовый индекс должен состоять из 6 цифр';
	}
	$city = trim($request->getPost('city'));
	if (mb_strlen($city) < 3) {
		$arErrors['city'] = 'Введите город';
	}
	$street = trim($request->getPost('street'));
	if (mb_strlen($street) < 3) {
		$arErrors['street'] = 'Введите название улицы';
	}
	$appartment = trim($request->getPost('appartment'));
	if (!mb_strlen($appartment)) {
		$arErrors['appartment'] = 'Введите номер квартиры';
	}
	$subject = trim($request->getPost('subject'));
	if (mb_strlen($subject) < 3) {
		$arErrors['subject'] = 'Введите тему эссе';
	}
	$text = trim($request->getPost('text'));
	if (mb_strlen($text) < 3) {
		$arErrors['text'] = 'Введите текст эссе';
	}
	$file = $request->getFile('file');
	if (!$file['size']) {
		$arErrors['file'] = 'Прикрепите файл эссе';
	} elseif ($file['size'] > 30 * 1024 * 1024) {
		$arErrors['file'] = 'Превышен максимальный размер файла эссе';
	} elseif (strpos($file['type'], 'word') == false) {
		$arErrors['file'] = 'Загрузите эссе в формате Word';
	}
	$agreement = $request->getPost('agreement');
	if ($agreement != 'Y') {
		$arErrors['agreement'] = 'Вы должны согласиться с обработкой персональных данных';
	}
}

if ($request->isAjaxRequest()) {
	$APPLICATION->RestartBuffer();
	if (!empty($arErrors)) {
		echo json_encode(['success' => false, 'errors' => $arErrors]);
	} else {
		// сохраняем форму в инфоблок
		Loader::includeModule('iblock');
		$el = new CIBLockElement;

		$arFields = [
			'IBLOCK_ID' => CIBlockTools::getIBlockId('essay'),
			'NAME' => implode(' ', [$lastName, $firstName, $secondName]),
			'DETAIL_TEXT' => $text,
			'PREVIEW_PICTURE' => $image,
			'PROPERTY_VALUES' => [
				'LAST_NAME' => $lastName,
				'FIRST_NAME' => $firstName,
				'SECOND_NAME' => $secondName,
				'EMAIL' => $email,
				'PHONE' => $phone,
				'ZIP' => $zipCode,
				'CITY' => $city,
				'STREET' => $street,
				'BUILDING' => $building,
				'APPARTMENT' => $appartment,
				'SUBJECT' => $subject,
				'FILE' => $file
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
			$arTo[] = COption::GetOptionString('main', 'email_from');
		}

		$arFields = [
			'EMAIL_TO' => implode(', ', $arTo),
			'LINK' => ($request->isHttps() ? 'https://' : 'http://') . SITE_SERVER_NAME . '/bitrix/admin/' . CIBlock::GetAdminElementEditLink(CIBlockTools::getIBlockId('essay'), $elementId)
		];

		CEvent::Send('ESSAY_FORM', SITE_ID, $arFields);

		echo json_encode(['success' => true, 'message' => 'Спасибо, ваша заявка принята']);
	}
	die;
}

$arIBlock = CIBlock::getById(CIBlockTools::getIblockId('party'))->getNext();
$APPLICATION->AddChainItem($arIBlock['~NAME']);
$arSection = CIBlockSection::getList(
	[],
	[
		'IBLOCK_ID' => $arIBlock['ID'],
		'CODE' => 'essay',
		'DEPTH_LEVEL' => 1
	],
	false,
	[],
	['nTopCount' => 1]
)->getNext();
$APPLICATION->addChainItem($arSection['~NAME'], '/party/essay/');
$APPLICATION->setTitle($arSection['~NAME']);

$rightImage = <<<IMAGE
<div class="content-wrapper">
	<div class="b-page__img b-page__img-essay"></div>
</div>
IMAGE;

$APPLICATION->addViewContent('top_right_image', $rightImage, 0);
?>
<div class="b-essay__inner">
	<h1 class="b-essay__title b-title b-title-strip b-title__img"><?=(mb_strtoupper($arSection['NAME']))?></h1>
	<div class="b-essay__wrap b-list__wrap">
		<div class="b-essay__item b-list__item">
			<div class="b-list__item-img"><img src="/f/img/essay-ico.svg" alt=""></div>
		</div>
		<div class="b-essay__item b-list__item">
			<div class="b-essay-form">
				<form action="/party/essay/" method="POST" enctype="multipart/form-data" data-ajax_form_action="/party/essay/">
					<div class="b-essay-form__inner">
						<div class="b-essay-form__top">
							<div class="b-essay-form__item">
								<div class="b-essay-form__foto">
									<div class="b-essay-form__foto-name">Фотография</div>
									<div class="b-essay-form__foto-block">
										<div class="b-essay-form__foto-preview" id="dropbox" data-input_name="image"></div>
										<div class="b-essay-form__foto-controls">
											<div class="b-essay-form__foto-controls--refresh js-file-refresh"></div>
											<div class="b-essay-form__foto-controls--remove js-file-remove"></div>
										</div>
										<div class="b-essay-form__foto-block-inner">
											<div class="b-essay-form__foto-img"><img src="/f/img/img-ico.svg" alt=""></div>
											<div class="b-essay-form__foto-text">Перетащите фото сюда или нажмите кнопку ниже, чтобы выбрать их на компьютере</div>
											<div class="b-essay-form__foto-load">
												<input type="file" name="image" id="fileElem" multiple accept="image/*" style="display:none" onchange="handleFiles(this.files);console.log(this.files)">
												<label for="fileElem">Добавить фото</label>
											</div>
										</div>
									</div>
									<div class="b-essay-form__foto-info">Минимальное разрешение изображения 800х800px Формат — jpeg</div>
								</div>
							</div>
							<div class="b-essay-form__item">
								<div class="b-essay-form-block">
									<div class="b-essay-form-block__name">Фамилия</div>
									<div class="b-essay-form-block__label">
										<input class="b-essay-form-block__input js-vall" type="text" name="last_name" value="<?= htmlspecialchars($lastName)?>" required="" autocomplete="off" placeholder="Ваша фамилия">
									</div>
								</div>
								<div class="b-essay-form-block">
									<div class="b-essay-form-block__name">Имя</div>
									<div class="b-essay-form-block__label">
										<input class="b-essay-form-block__input js-vall" type="text" name="first_name" value="<?= htmlspecialchars($firstName)?>" required="" autocomplete="off" placeholder="Ваше имя">
									</div>
								</div>
								<div class="b-essay-form-block">
									<div class="b-essay-form-block__name">Отчество</div>
									<div class="b-essay-form-block__label">
										<input class="b-essay-form-block__input js-vall" type="text" name="second_name" value="<?= htmlspecialchars($secondName)?>" required="" autocomplete="off" placeholder="Ваше отчество">
									</div>
								</div>
								<div class="b-essay-form-block">
									<div class="b-essay-form-block__name">Email</div>
									<div class="b-essay-form-block__label">
										<input class="b-essay-form-block__input js-email" type="email" name="email" value="<?= htmlspecialchars($email)?>" required="" autocomplete="off" placeholder="Ваш E-mail">
									</div>
								</div>
								<div class="b-essay-form-block">
									<div class="b-essay-form-block__name">Номер телефона</div>
									<div class="b-essay-form-block__label">
										<input class="b-essay-form-block__input js-phone" type="tel" name="phone" value="<?= htmlspecialchars($phone)?>" required="" autocomplete="off" placeholder="+7 (999) 999 99 99">
									</div>
								</div>
							</div>
						</div>
						<div class="b-essay-form__bottom">
							<div class="b-essay-form-bottom-block">
								<div class="b-essay-form-bottom-block__title b-essay-form-bottom__title">Почтовый адрес</div>
								<div class="b-essay-form-bottom-block-input">
									<label class="b-essay-form-bottom-block__label">Индекс
										<input class="b-essay-form-bottom-block__input b-essay-form-block__input js-index " type="text" name="zip_code" value="<?= htmlspecialchars($zipCode)?>" required>
									</label>
									<label class="b-essay-form-bottom-block__label">Город
										<input class="b-essay-form-bottom-block__input b-essay-form-block__input " type="text" name="city" value="<?= htmlspecialchars($city)?>" required>
									</label>
									<label class="b-essay-form-bottom-block__label">Улица
										<input class="b-essay-form-bottom-block__input b-essay-form-block__input " type="text" name="street" value="<?= htmlspecialchars($street)?>" required>
									</label>
									<label class="b-essay-form-bottom-block__label">Дом
										<input class="b-essay-form-bottom-block__input b-essay-form-block__input" type="text" name="building" value="<?= htmlspecialchars($building)?>" required>
									</label>
									<label class="b-essay-form-bottom-block__label">Квартира
										<input class="b-essay-form-bottom-block__input b-essay-form-block__input" type="text" name="appartment"value="<?= htmlspecialchars($appartment)?>" >
									</label>
								</div>
							</div>
							<div class="b-essay-form-bottom-textarea">
								<div class="b-essay-form-bottom-textarea__title b-essay-form-bottom__title">Эссе</div>
								<div class="b-essay-form__textarea-block">
									<div class="b-essay-form__textarea-title">Тема эссе</div>
									<div class="b-essay-form__textarea">
										<textarea name="subject"><?= htmlspecialchars($subject)?></textarea>
									</div>
								</div>
								<div class="b-essay-form__textarea-block">
									<div class="b-essay-form__textarea-title">Текст эссе</div>
									<div class="b-essay-form__textarea">
										<textarea name="text" class="autosize"><?= htmlspecialchars($text)?></textarea>
									</div>
								</div>
							</div>
							<div class="b-essay-form__bottom-row">Загрузите эссе в формате Word. Максимальный размер файла — 30Mb</div>
							<div class="b-essay-form__bottom-btn b-essay-form__btn">
								<input type="file" name="file" id="file" accept="application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document">
								<button type="button">
									<label for="file">Загрузить&nbsp;эссе</label>
								</button>
							</div>
						</div>
						<div class="b-essay-form__checkbox">
							<label class="b-essay-form__checkbox-label">Настоящим согласием подтверждаю, что проинформирован о том, что под обработкой персональных данных понимаются действия, определённые в Федеральном законе № 152-ФЗ от 27.07.2006 «О персональных данных», а именно: сбор, запись, систематизация, накопление, хранение, уточнение (обновление, изменение), извлечение, использование, передача (предоставление, доступ), блокирование, удаление персональных данных, совершаемые с использованием средств автоматической, а также неавтоматической обработки данных.
								<input class="b-essay-form__checkbox-input" type="checkbox" required="" name="agreement" value="Y"<?php $agreement == 'Y' ? ' checked' : ''?>>
								<span class="b-essay-form__checkbox-checked"></span>
							</label>
						</div>
					</div>
					<div class="b-essay-form__btn b-essay-form__btn--send">
						<button type="submit" name="submit" value="Y">Отправить заявку</button>
					</div>
				</form>
			</div>
		</div>
		<div class="b-essay__ritem b-list__item">
			<?php
			$APPLICATION->includeComponent(
				'bitrix:news.detail',
				'share.buttons',
				[
					'DISPLAY_DATE' => 'N',
					'DISPLAY_NAME' => 'N',
					'DISPLAY_PICTURE' => 'N',
					'DISPLAY_PREVIEW_TEXT' => 'N',
					'IBLOCK_ID' => CIBlockTools::getIBlockId('contacts'),
					'IBLOCK_TYPE' => 'content',
					'FIELD_CODE' => [''],
					'PROPERTY_CODE' => ['SOCIAL_LINK', ''],
					'ELEMENT_CODE' => 'share_buttons',
					'INCLUDE_IBLOCK_INTO_CHAIN' => 'N',
					'ADD_SECTIONS_CHAIN' => 'N',
					'ADD_ELEMENT_CHAIN' => 'N',
					'SET_TITLE' => 'N',
					'PAGE_TITLE' => $arSection['~NAME']
				]
			);
			?>
		</div>
	</div>
</div>
<?php
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
