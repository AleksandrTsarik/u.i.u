<?php
use Bitrix\Main\Context;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die;
}
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

$request = Context::getCurrent()->getRequest();
?>
<div class="b-search b-page">
	<div class="b-title-strip"></div>
	<div class="b-search-list b-list__wrap">
		<div class="b-search__item b-list__item">
			<div class="b-list__item-img"><img src="<?=SITE_TEMPLATE_PATH?>/images/search-ico.svg" alt=""></div>
		</div>
		<div class="b-search__item b-list__item">
			<h1 class="b-search__title">Результаты поиска &laquo;<?=$arResult['REQUEST']['~QUERY']?>&raquo;</h1>
			<nav class="b-search__list">
				<form action="" method="GET">
					<div class="b-search__label">
						<input type="text" name="q" value="<?=$arResult['REQUEST']['QUERY']?>" class="b-search__input">
						<button type="submit" class="b-search__ico"><img src="/f/img/lens-ico.svg" alt=""></button>
					</div>
				</form>
				<?php if (count($arResult['SEARCH']) == 0): ?>
					<div class="b-search__text">
						По вашему запросу, к сожалению, ничего не найдено.<br>Пожалуйста, попробуйте изменить ваш запрос.
					</div>
				<?php endif; ?>
				<?php if (count($arResult['SEARCH']) > 0): ?>
					<?php
						$start = ($arResult['NAV_RESULT']->NavPageNomer - 1) * $arResult['NAV_RESULT']->NavPageSize + 1;
					?>
					<ol>
						<?php foreach($arResult['SEARCH'] as $key => $arItem): ?>
							<li class="b-search-item">
								<div class="b-search-item__inner">
									<div class="b-search-item__num"><?=($start + $key)?>.</div>
									<div class="b-search-item__title"><?=$arItem['TITLE_FORMATED']?></div>
									<a class="b-search-item__link" href="<?=$arItem['URL']?>"><?=(preg_replace(
										'/^(' . preg_quote(SITE_SERVER_NAME) . '\/)(.{20}).{3,}(.{30})$/',
										'$1$2&hellip;&shy$3',
										SITE_SERVER_NAME . $arItem['URL_WO_PARAMS']
									))?></a>
									<div class="b-search-item__text"><?=$arItem['BODY_FORMATED']?></div>
								</div>
							</li>
						<?php endforeach; ?>
					</ol>
				<?php endif; ?>
				<?php if (count($arResult['SEARCH']) > 1 && $arParams['DISPLAY_BOTTOM_PAGER'] != 'N'): ?>
					<?=$arResult['NAV_STRING']?>
				<?php endif; ?>
			</nav>
		</div>
		<div class="b-search__item b-list__item">
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
					'PAGE_TITLE' => "Результаты поиска &laquo;{$arResult['REQUEST']['~QUERY']}&raquo;",
					'CACHE_TYPE' => 'N'
				]
			);
			?>
		</div>
	</div>
</div>
