<?php
use Bitrix\Main\Context,
	Bitrix\Main\Loader,
	Bitrix\Main\Page\Asset;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die;
}

$request = Context::getCurrent()->getRequest();

CUtil::InitJSCore();

$arSite = CSite::getByID(SITE_ID)->fetch();
?>
<!DOCTYPE html>
<html lang="ru">
	<head>
		<!-- Yandex.Metrika counter -->
		<script type="text/javascript" >
			(function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
			m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
			(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

			ym(89694216, "init", {
				clickmap:true,
				trackLinks:true,
				accurateTrackBounce:true,
				webvisor:true
			});
		</script>
		<!-- Google Tag Manager -->
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','GTM-5KB6RKZ');</script>
		<!-- End Google Tag Manager -->
		<noscript><div><img src="https://mc.yandex.ru/watch/89694216" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
		<!-- /Yandex.Metrika counter -->
		<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta content="yes" name="apple-mobile-web-app-capable">
		<meta name="HandheldFriendly" content="true">
		<?php
			MyHelpers::setSEOMetaData('/' . ltrim($request->getRequestedPageDirectory(), '/'));
		?>
		<meta name="title" content="<?php $APPLICATION->showProperty('title'); ?>">
		<meta name="description" content="<?php $APPLICATION->showProperty('description'); ?>">
		<meta name="keywords" content="<?php $APPLICATION->showProperty('keywords'); ?>">
		<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
		<link rel="icon" type="image/svg+xml" href="/favicon.svg">
		<link rel="manifest" href="/site.webmanifest">
		<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
		<meta name="msapplication-TileColor" content="#00a300">
		<meta name="msapplication-TileImage" content="/mstile-144x144.png">
		<meta name="theme-color" content="#ffffff">
		<?php
			Asset::getInstance()->addCss('/f/css/owl.carousel.min.css');
			Asset::getInstance()->addCss('/f/css/slick.css');
			Asset::getInstance()->addJs('/f/js/vendor/jquery.min.js');
			Asset::getInstance()->addJs('/f/js/vendor/owl.carousel.min.js');
			Asset::getInstance()->addJs('/f/js/vendor/jquery.mask.min.js');
			Asset::getInstance()->addJs('/f/js/vendor/handlebars.min.js');
			Asset::getInstance()->addJs('/f/js/vendor/jquery-ui.min.js');
			Asset::getInstance()->addJs('/f/js/vendor/jquery.maskedinput.js');
			Asset::getInstance()->addJs('/f/js/vendor/autosize.min.js');
			Asset::getInstance()->addJs('/f/js/vendor/slick.min.js');
			Asset::getInstance()->addJs('/f/js/main.js');
		?>
		<title><?=$arSite['NAME']?>. <?php $APPLICATION->showTitle() ?></title>
		<?php $APPLICATION->ShowHead(); ?>
	</head>
	<body>
		<!-- Google Tag Manager (noscript) -->
		<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5KB6RKZ"
		height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		<!-- End Google Tag Manager (noscript) -->
		<?php if ($USER->isAdmin() && $_REQUEST['show_preview'] != 'y') {
			$APPLICATION->ShowPanel();
		} ?>
		<header class="header header-<?=($request->getRequestedPageDirectory() == '' ? 'news' : 'page')?>">
			<div class="content-wrapper">
				<div class="header__inner">
					<div class="header-left">
						<div class="header-left__inner">
							<div class="header__logo"><a href="<?=SITE_DIR?>"><img src="/f/img/header/logo.png" alt="logo"></a></div>
							<div class="header-left__text">Всероссийская <br> открытая <br> телевизионная <br> гуманитарная <br> олимпиада <br> «Умницы <br> и Умники»</div>
						</div>
					</div>
					<div class="header-right">
						<div class="header-right__inner">
							<div class="header-right__info">
								<?php
								Loader::includeModule('iblock');

								$rsIBlocks = GetIBlockList(
									'content',
									[
										CIBlockTools::getIBlockId('media'),
										CIBlockTools::getIBlockId('faq'),
										CIBlockTools::getIBlockId('contacts')
									],
									[],
									['sort' => 'asc']
								);
								while ($arIBlock = $rsIBlocks->getNext()) {
									echo '<div><a href="' . ($arIBlock['~LIST_PAGE_URL'] ? $arIBlock['~LIST_PAGE_URL'] : '/' . $arIBlock['CODE'] . '/') . '">' . mb_strtoupper($arIBlock['~NAME']) . '</a></div>';
								}
								?>
								<div class="header-right__info-input">
									<form action="/search/" method="GET">
										<span class="header-lens"><img src="<?=SITE_TEMPLATE_PATH?>/images/lens.svg" alt="lens"></span>
										<label class="header-right__label">
											<input type="text" name="q" class="header-right__input" placeholder="введите запрос">
											<button type="submit"><img src="<?=SITE_TEMPLATE_PATH?>/images/lens-ico.svg" alt=""></button>
										</label>
									</form>
								</div>
							</div>
							<?php
								$APPLICATION->IncludeComponent(
									'bitrix:menu',
									'horizontal_multilevel',
									[
										'ROOT_MENU_TYPE' => 'top',
										'MENU_CACHE_TYPE' => 'N',
										'MAX_LEVEL' => '3',
										'CHILD_MENU_TYPE' => 'left',
										'USE_EXT' => 'Y',
										'DELAY' => 'N',
										'ALLOW_MULTI_SELECT' => 'N',
										'COMPONENT_TEMPLATE' => 'horizontal_multilevel',
										'MENU_CACHE_TIME' => '3600',
										'MENU_CACHE_USE_GROUPS' => 'Y',
										'MENU_CACHE_GET_VARS' => []
									],
									false
								);
							?>
						</div>
						<div class="burger js-active-parent">
							<div></div>
							<div></div>
							<div></div>
						</div>
					</div>
				</div>
			</div>
			<?php
				$APPLICATION->showViewContent('news_broadcast');
			?>
		</header>
		<div class="page-container<?=($request->getRequestedPageDirectory() != '' ? ' page-container--fix-header' : '')?>">
			<?php
				$APPLICATION->showViewContent('top_right_image');
			?>
			<?php
			if ($request->getRequestedPageDirectory() != '') {
				$APPLICATION->IncludeComponent(
					'bitrix:breadcrumb',
					'',
					[
						'START_FROM' => 0,
						'PATH' => '',
						'SITE_ID' => SITE_ID
					]
				);
			}
			?>
			<div class="content-wrapper">
