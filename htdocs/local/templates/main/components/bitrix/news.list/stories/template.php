<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die;
}

$this->setFrameMode(true);

$this->setViewTarget('top_right_image');
?>
<div class="content-wrapper">
	<div class="b-page__img b-page__img-stories"></div>
</div>
<?php
$this->endViewTarget('top_right_image');
?>
<div class="b-stories__top">
	<h1 class="b-stories__title b-title"><?=mb_strtoupper($arResult['SECTION']['PATH'][0]['~NAME'])?></h1>
</div>

<div class="b-stories__wrap">
	<div class="b-stories__list">
		<?php
			$blockIndex = 0;
		?>
		<?php foreach ($arResult['ITEMS'] as $index => $arItem): ?>
			<a class="b-stories__item" href="<?=$arItem['DETAIL_PAGE_URL']?>">
				<div class="b-stories__inner">
					<div class="b-stories-top">
						<div class="b-stories__img">
							<img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="<?=$arItem['PREVIEW_PICTURE']['ALT']?>">
						</div>
					</div>
					<div class="b-stories-middle">
						<div class="b-stories-item__title"><?=$arItem['~NAME']?></div>
						<div class="b-stories-item__text"><?=$arItem['~PREVIEW_TEXT']?></div>
					</div>
					<div class="b-stories-bottom">
						<div class="b-stories-item__date"><?=FormatDate('j F Y', MakeTimeStamp($arItem['ACTIVE_FROM']))?></div>
					</div>
				</div>
			</a>
			<?php if (!($index % 4)): ?>
				<?php
					$arBlock = $arResult['BLOCKS'][$blockIndex];
					$blockIndex = $blockIndex < count($arResult['BLOCKS']) - 1 ? $blockIndex + 1 : 0;
				?>
				<div class="b-stories__item">
					<div class="b-offer__bg <?=$arBlock['STYLE']?>">
						<?php if ($arBlock['ADDITIONAL_PICTURE']): ?>
							<div class="b-offer__item-foto"><img src="<?=$arBlock['ADDITIONAL_PICTURE']?>" alt=""></div>
						<?php endif; ?>
						<div class="b-offer__item-top">
							<div class="b-offer__item-title"><?=mb_strtoupper($arBlock['NAME'])?></div>
						</div>
						<div class="b-offer__item-middle"> 
							<div class="b-offer__item-text"><?=$arBlock['PREVIEW_TEXT']?></div>
							<div class="b-offer__item-img"><img src="<?=$arBlock['PREVIEW_PICTURE']?>" alt=""></div>
						</div>
						<div class="b-offer__item-bottom">
							<div class="b-offer__item-link">
								<a href="<?=$arBlock['LINK_URL']?>"><?=$arBlock['LINK_CAPTION']?><img src="/f/img/arrow.svg" alt=""></a>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
</div>
