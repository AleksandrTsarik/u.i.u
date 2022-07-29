<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die;
}

if (!empty($arResult['ERROR_MESSAGE'])) {
	foreach($arResult['ERROR_MESSAGE'] as $v) {
		ShowError($v);
	}
}
if ($arResult['OK_MESSAGE'] <> '') {
	?><div class="success"><?=$arResult['OK_MESSAGE']?></div><?
}
?>
<form action="<?=POST_FORM_ACTION_URI?>" method="POST" data-ajax_form_action="/local/ajax/?action=feedback">
	<div class="f-form__inner">
		<div class="f-form__title">ОБРАТНАЯ СВЯЗЬ</div>
		<div class="f-form-block">
			<label class="f-form-block__label">ФИО
				<div class="f-form-block__input">
					<input type="text" name="user_name" value="<?=$arResult['AUTHOR_NAME']?>" class="js-vall<?=(is_array($arResult['ERROR_MESSAGE']) && key_exists('user_name', $arResult['ERROR_MESSAGE']) ? ' form-error' : '')?>" required autocomplete="off">
				</div>
			</label>
		</div>
		<div class="f-form-block">
			<label class="f-form-block__label">Email
				<div class="f-form-block__input">
					<input type="email" name="user_email" value="<?=$arResult['AUTHOR_EMAIL']?>" class="js-vall js-email<?=(is_array($arResult['ERROR_MESSAGE']) && key_exists('user_email', $arResult['ERROR_MESSAGE']) ? ' form-error' : '')?>" required autocomplete="off">
				</div>
			</label>
		</div>
		<div class="f-form-block">
			<label class="f-form-block__label">Телефон
				<div class="f-form-block__input">
					<input type="tel" name="user_phone" value="<?=$arResult['AUTHOR_PHONE']?>" class="js-vall js-phone<?=(is_array($arResult['ERROR_MESSAGE']) && key_exists('user_phone', $arResult['ERROR_MESSAGE']) ? ' form-error' : '')?>" required autocomplete="off">
				</div>
			</label>
		</div>
		<div class="f-form-block">
			<label class="f-form-block__label">Ваше сообщение
				<div class="f-form-block__textarea">
					<textarea name="MESSAGE" value="<?=$arResult['MESSAGE']?>" class="js-vall<?=(is_array($arResult['ERROR_MESSAGE']) && key_exists('MESSAGE', $arResult['ERROR_MESSAGE']) ? ' form-error' : '')?> autosize" required autocomplete="off"></textarea>
				</div>
			</label>
		</div>
		<div class="f-form-block">
			<button type="submit" name="submit" value="Y" class="f-form-block__btn">Отправить сообщение</button>
		</div>
	</div>
</form>
