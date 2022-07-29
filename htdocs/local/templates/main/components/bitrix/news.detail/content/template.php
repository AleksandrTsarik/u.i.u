<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die;
}
?>
<h1 class="b-news__title b-title b-title-strip"><?=(mb_strtoupper($arResult['~NAME']))?></h1>
<div class="b-news-open__wrap">
	<div class="b-news-open__inner b-list__wrap">
		<div class="b-news-open__item b-list__item">
			<?php if (!empty($arResult['SECTION']['ICON'])): ?>
				<div class="b-list__item-img">
					<img src="<?=$arResult['SECTION']['ICON']?>">
				</div>
			<?php endif; ?>
		</div>
		<div class="b-news-open__item b-list__item">
			<div class="b-page-subtitle b-content b-content__content">
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
					'PAGE_TITLE' => $arResult['META_TAGS']['TITLE'],
					'CACHE_TYPE' => 'N'
				]
			);
			?>
		</div>
	</div>
</div>
