<?php

use Bitrix\Main\Context,
	Bitrix\Main\Web\Cookie;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die;
}

$request = Context::getCurrent()->getRequest();
$response = Context::getCurrent()->getResponse();
$server = Context::getCurrent()->getServer();
?>
			</div>
		</div>
		<footer class="footer">
			<div class="content-wrapper">
				<div class="footer__wrap"> 
					<div class="footer__list"> 
						<div class="footer__item"> 
							<nav class="footer__menu footer__menu--top">
								<ul class="footer__menu-list">
									<?php
									$APPLICATION->includeComponent(
										'bitrix:news.detail',
										'include.iblock.html',
										[
											'DISPLAY_DATE' => 'N',
											'DISPLAY_NAME' => 'N',
											'DISPLAY_PICTURE' => 'N',
											'DISPLAY_PREVIEW_TEXT' => 'N',
											'IBLOCK_ID' => CIBlockTools::getIBlockId('page_elements'),
											'IBLOCK_TYPE' => 'content',
											'FIELD_CODE' => [''],
											'PROPERTY_CODE' => [''],
											'ELEMENT_CODE' => 'footer_menu_top',
											'INCLUDE_IBLOCK_INTO_CHAIN' => 'N',
											'ADD_SECTIONS_CHAIN' => 'N',
											'ADD_ELEMENT_CHAIN' => 'N',
											'SET_TITLE' => 'N'
										]
									);
									?>
								</ul>
							</nav>
							<nav class="footer__menu">
								<ul class="footer__menu-list">
									<?php
									$APPLICATION->includeComponent(
										'bitrix:news.detail',
										'include.iblock.html',
										[
											'DISPLAY_DATE' => 'N',
											'DISPLAY_NAME' => 'N',
											'DISPLAY_PICTURE' => 'N',
											'DISPLAY_PREVIEW_TEXT' => 'N',
											'IBLOCK_ID' => CIBlockTools::getIBlockId('page_elements'),
											'IBLOCK_TYPE' => 'content',
											'FIELD_CODE' => [''],
											'PROPERTY_CODE' => [''],
											'ELEMENT_CODE' => 'footer_menu_bottom',
											'INCLUDE_IBLOCK_INTO_CHAIN' => 'N',
											'ADD_SECTIONS_CHAIN' => 'N',
											'ADD_ELEMENT_CHAIN' => 'N',
											'SET_TITLE' => 'N'
										]
									);
									?>
								</ul>
							</nav>
							<div class="footer__item-img">
								<?php
								$APPLICATION->includeComponent(
									'bitrix:news.detail',
									'include.iblock.link',
									[
										'DISPLAY_DATE' => 'N',
										'DISPLAY_NAME' => 'N',
										'DISPLAY_PICTURE' => 'N',
										'DISPLAY_PREVIEW_TEXT' => 'N',
										'IBLOCK_ID' => CIBlockTools::getIBlockId('page_elements'),
										'IBLOCK_TYPE' => 'content',
										'FIELD_CODE' => [''],
										'PROPERTY_CODE' => [''],
										'ELEMENT_CODE' => 'footer_sponsor_logo',
										'INCLUDE_IBLOCK_INTO_CHAIN' => 'N',
										'ADD_SECTIONS_CHAIN' => 'N',
										'ADD_ELEMENT_CHAIN' => 'N',
										'SET_TITLE' => 'N'
									]
								);
								?>
							</div>
						</div>
						<div class="footer__item"> 
							<div class="footer__item-title">Мы в социальных сетях</div>
							<div class="footer__item-link">
								<?php
								$APPLICATION->includeComponent(
									'bitrix:news.detail',
									'socnet.buttons.footer',
									[
										'DISPLAY_DATE' => 'N',
										'DISPLAY_NAME' => 'N',
										'DISPLAY_PICTURE' => 'N',
										'DISPLAY_PREVIEW_TEXT' => 'N',
										'IBLOCK_ID' => CIBlockTools::getIBlockId('contacts'),
										'IBLOCK_TYPE' => 'content',
										'FIELD_CODE' => [''],
										'PROPERTY_CODE' => ['SOCIAL_LINK', ''],
										'ELEMENT_CODE' => 'socnet_buttons_footer',
										'INCLUDE_IBLOCK_INTO_CHAIN' => 'N',
										'ADD_SECTIONS_CHAIN' => 'N',
										'ADD_ELEMENT_CHAIN' => 'N',
										'SET_TITLE' => 'N'
									]
								);
								?>
							</div>
						</div>
						<div class="footer__item">
							<div class="footer__form f-form">
								<?php
									$APPLICATION->IncludeComponent(
										'uiu:feedback',
										'',
										[
											'USE_CAPTCHA' => 'N',
											'OK_TEXT' => 'Спасибо, ваше сообщение отправлено.',
											'REQUIRED_FIELDS' => ['NAME', 'EMAIL', 'MESSAGE', 'PHONE'],
											'EVENT_NAME' => 'FEEDBACK_FORM',
											// 'EVENT_MESSAGE_ID' => [7]
										]
									);
								?>
							</div>
						</div>
					</div>
					<div class="footer-row">&copy;&nbsp;&laquo;Образ-ТВ&raquo;&nbsp;<?=(date('Y'))?></div>
				</div>
			</div>
		</footer>
		<?php if ($request->getCookie('cookie_ok') === null): ?>
			<div class="cookie">
				<div class="cookie-wrap content-wrapper">
					<div class="cookie__inner">
						<div class="cookie__text">Мы используем файлы cookie. Подробная информация в политике конфиденциальности. Вы можете запретить сохранение cookie в настройках своего браузера.</div>
						<div class="cookie__btn">
							<div class="cookie__btn-inner">
								<button id="cookie_ok" class="active">Принять</button>
								<button id="cookie_close">Закрыть</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<?php if ($request->getCookie('cookie_sess_id') != session_id() && $request->getCookie('cookie_ok') != 'Y'): ?>
			<?php
				$cookie = new Cookie('cookie_sess_id', session_id(), time() + 60 * 60 * 24 * 60);
				$cookie->setDomain($server->getHttpHost());
				$cookie->setHttpOnly(true);
				$response->addCookie($cookie);

				$cookie = new Cookie('cookie_ok', null);
				$cookie->setDomain($server->getHttpHost());
				$cookie->setHttpOnly(false);
				$response->addCookie($cookie);
			?>
		<?php endif; ?>
		<?php if ($request->getCookie('cookie_ok') == 'Y'): ?>
			<?php
				// продлим согласие
				$cookie = new Cookie('cookie_ok', 'Y', time() + 60 * 60 * 24 * 60);
				$cookie->setDomain($server->getHttpHost());
				$cookie->setHttpOnly(true);
				$response->addCookie($cookie);
			?>
		<?php endif; ?>
	</body>
</html>
