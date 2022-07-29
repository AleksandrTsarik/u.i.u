<?php
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');

CHTTP::SetStatus('404 Not Found');
@define('ERROR_404', 'Y');

$APPLICATION->SetTitle('404 Not Found');
$APPLICATION->addChainItem('404 - страница не найдена');
?>
<div class="b-error b-page">
	<div class="b-title-strip"></div>
	<div class="b-error-list b-list__wrap">
		<div class="b-error__item b-list__item">
			<div class="b-list__item-img"><img src="<?=SITE_TEMPLATE_PATH?>/images/404-ico.svg" alt=""></div>
		</div>
		<div class="b-error__item b-list__item">
			<div class="b-error__img"><img src="/f/img/404.svg" alt=""></div>
			<div class="b-error__text">К сожалению, запрашиваемая страница не найдена. <br> Возможно, вы перешли по ссылке, в которой была допущена ошибка, или ресурс был удален. <br> Попробуйте перейти на <a href="/"> главную страницу.</a></div>
		</div>
	</div>
</div>
<?php
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
