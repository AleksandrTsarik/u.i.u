<?php
use Bitrix\Main\Context;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die;
}

$server = Context::getCurrent()->getRequest()->getServer();
?>

<div class="b-news__title b-title b-title-strip">НОВОСТИ</div>
<div class="b-news-open__wrap">
	<div class="b-news-open__inner b-list__wrap">
		<div class="b-news-open__item b-list__item">
			<a class="b-back-link" href="<?=preg_replace('/^\/\/+/', '/', CComponentEngine::makePathFromTemplate($arResult['LIST_PAGE_URL']))?>"></a>
		</div>
		<div class="b-news-open__item b-list__item">
			<div class="b-news-open__content b-page-subtitle">
				<h1><?=$arResult['~NAME']?></h1>
				<div>
					<?php if ($arParams['DISPLAY_PICTURE'] != 'N' && is_array($arResult['DETAIL_PICTURE'])): ?>
						<img
							src="<?=$arResult['DETAIL_PICTURE']['SRC']?>"
							alt="<?=$arResult['DETAIL_PICTURE']['ALT']?>"
							title="<?=$arResult['DETAIL_PICTURE']['TITLE']?>"
							>
						<?php if ($arResult['DETAIL_PICTURE']['DESCRIPTION']): ?>
							<div class="b-foto-text"><?=nl2br($arResult['DETAIL_PICTURE']['DESCRIPTION'])?></div>
						<?php endif; ?>
					<?php endif; ?>
				</div>
				<?=$arResult['~DETAIL_TEXT']?>
				<?php if (!empty($arResult['DISPLAY_PROPERTIES']['FILE_ATTACH'])): ?>
					<h3>Приложенные файлы</h3>
					<div class="b-content-file">
						<div class="b-content-file__list b-content-file__list--mw">
							<?php foreach ($arResult['DISPLAY_PROPERTIES']['FILE_ATTACH']['FILE_VALUE'] as $arFile): ?>
								<?php
									$extension = strtolower(pathinfo($arFile['ORIGINAL_NAME'], PATHINFO_EXTENSION));
									$format = MyHelpers::getFileTypeByExtension($extension);
									if (file_exists($server->getDocumentRoot() . '/' .SITE_TEMPLATE_PATH . '/images/file-ico_' . $format . '.svg')) {
										$icon = SITE_TEMPLATE_PATH . '/images/file-ico_' . $format . '.svg';
									} else {
										$icon = SITE_TEMPLATE_PATH . '/images/file-ico_unknown.svg';
									}
									$size = MyHelpers::formatBytes($arFile['FILE_SIZE']);
								?>
								<div class="b-content-file__item">
									<div class="b-content-file__img"><img src="<?=$icon?>" alt=""></div>
									<div class="b-content-file__info">
										<a href="<?=htmlspecialchars($arFile['SRC'])?>"><?=htmlspecialchars($arFile['ORIGINAL_NAME'])?></a>
										<?php if ($format != 'known' && $format != 'unknown'): ?><p>Формат <?=$format?></p><?php endif; ?>
										<p>Размер <?=$size?></p>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<div class="b-news-open__item b-list__item">
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
					'PAGE_TITLE' => $arResult['META_TAGS']['TITLE'] . $arResult['IBLOCK']['~NAME'],
					'CACHE_TYPE' => 'N'
				]
			);
			?>
		</div>
	</div>
</div>
