<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die;
}
?>

<?php if (!empty($arResult)): ?>
	<nav class="header__menu">
		<ul class="header__list">
			<?php foreach ($arResult as $arMenuItem): ?>
				<li class="header__list-item <?=($arMenuItem['SELECTED'] ? ' _active' : '')?>">
					<span><?=$arMenuItem['TEXT']?></span>
					<?php if ($arMenuItem['ITEMS']): ?>
						<ul class="header__sublist">
							<?php foreach ($arMenuItem['ITEMS'] as $arSubMenuItem): ?>
								<li<?=($arSubMenuItem['SELECTED'] ? ' class="_active"' : '')?>>
									<a href="<?=$arSubMenuItem['LINK']?>"><?=$arSubMenuItem['TEXT']?></a>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</nav>
<?php endif;
