<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die;
}
?>
<div class="b-contact">
	<div class="b-contact__inner">
		<h1 class="b-__title b-title b-title-strip">КОНТАКТЫ</h1>
		<div class="b-contact__wrap b-list__wrap">
			<div class="b-contact__item b-list__item">
				<div class="b-list__item-img"><img src="<?=SITE_TEMPLATE_PATH?>/images/contacts-ico.svg" alt=""></div>
			</div>
			<div class="b-contact__item b-list__item">
				<?php
					$address = nl2br(preg_replace('/[\r\n]+/', "\n", strip_tags($arResult['PROPERTIES']['ADRESS']['~VALUE']['TEXT'])));
				?>
				<div class="b-contact__item-title"><?=$address?></div>
				<div class="b-contact__item-subtitle">С пометкой «Умницы и Умники»</div>
				<div class="b-contact__item-map">
					<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7538.365383704761!2d37.61505922802109!3d55.82501829672184!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46b5361368955d5f%3A0x1b52bef3081630c6!2z0YPQuy4g0JDQutCw0LTQtdC80LjQutCwINCa0L7RgNC-0LvQtdCy0LAsIDEyLCDQnNC-0YHQutCy0LAsIDEyNzQyNw!5e0!3m2!1sru!2sru!4v1655224895034!5m2!1sru!2sru" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" width="100%" height="100%"></iframe>
				</div>
				<div class="b-contact__item-block b-contact-block__list">
					<div class="b-contact-block__item">
						<div class="b-contact-block__item-img"><img src="/f/img/phone-ico.svg" alt=""></div>
						<div class="b-contact-block__item-text">
							<div class="b-contact-block__item-title">Телефон </div>
							<?php foreach ($arResult['PROPERTIES']['PHONE']['VALUE'] as $phone): ?>
								<div class="b-contact-block__item-subtitle"><?=$phone?></div>
							<?php endforeach; ?>
						</div>
					</div>
					<div class="b-contact-block__item">
						<div class="b-contact-block__item-img"><img src="/f/img/mail-ico.svg" alt=""></div>
						<div class="b-contact-block__item-text">
							<div class="b-contact-block__item-title">E-mail</div>
							<div class="b-contact-block__item-subtitle">
								<?php foreach ($arResult['PROPERTIES']['EMAIL']['VALUE'] as $email): ?>
									<p><a href="mailto:<?=$email?>"><?=$email?></a></p>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
					<div class="b-contact-block__item">
						<div class="b-contact-block__item-text">
							<div class="b-contact-block__item-title">Социальные сети </div>
							<div class="b-contact-block__item-link">
								<?php foreach ($arResult['PROPERTIES']['SOCIAL_LINK']['VALUE'] as $arValue): ?>
									<?php
										$arSubValue = $arValue['SUB_VALUES'];
									?>
									<a href="<?=$arSubValue['LINK_URL']['VALUE']?>" target="_blank">
										<img src="<?=$arSubValue['LINK_IMAGE']['IMAGE']['SRC']?>" alt="<?=$arSubValue['LINK_TEXT']['~VALUE']?>">
									</a>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="b-contact__item b-list__item">
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
						'PAGE_TITLE' => $arResult['META_TAGS']['TITLE'],
						'CACHE_TYPE' => 'N'
					]
				);
				?>
			</div>
		</div>
	</div>
</div>
