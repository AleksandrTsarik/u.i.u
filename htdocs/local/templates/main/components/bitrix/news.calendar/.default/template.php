<?php
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

$this->setFrameMode(true);
?>
<div class="b-timetable">
	<div class="b-timetable__inner">
		<h1 class="b-title b-title-strip"><?=(mb_strtoupper($arResult['SECTION_TITLE']))?></h1>
		<div class="b-timetable__wrap b-list__wrap">
			<div class="b-timetable__item b-list__item">
				<div class="b-list__item-img"><img src="<?=SITE_TEMPLATE_PATH?>/images/timetable-ico.svg" alt=""></div>
			</div>
			<div class="b-timetable__item b-list__item">
				<div class="b-timetable__title"><?=$arResult['SEASON_HEADER']?></div>
				<div class="b-timetable__text"><?=$arResult['SEASON_TEXT']?></div>
				<div class="b-timetable-calendar">
					<div class="b-timetable-calendar__mounth">
						<a 
							href="/party/timetable/<?=(
									$arResult['currentMonth'] == 1 ? $arResult['currentYear'] - 1 : $arResult['currentYear']
								)?>/<?=(
									$arResult['currentMonth'] == 1 ? 12 : $arResult['currentMonth'] - 1
								)?>/"
							class="b-timetable-calendar__mounth-btn--left b-timetable-calendar__mounth-btn">
						</a>
						<span><?=$arResult['TITLE']?></span>
						<a 
							href="/party/timetable/<?=(
									$arResult['currentMonth'] == 12 ? $arResult['currentYear'] + 1 : $arResult['currentYear']
								)?>/<?=(
									$arResult['currentMonth'] == 12 ? 1 : $arResult['currentMonth'] + 1
								)?>/"
							class="b-timetable-calendar__mounth-btn--right b-timetable-calendar__mounth-btn">
						</a>
					</div>
				</div>
				<div class="b-timetable-table">
					<table>
						<thead>
							<tr class="b-timetable--desktop">
								<?php foreach ($arResult['WEEK_DAYS'] as $arDay): ?>
									<th><?=$arDay['FULL']?></th>
								<?php endforeach; ?>
							</tr>
							<tr class="b-timetable--mobile">
								<?php foreach ($arResult['WEEK_DAYS'] as $arDay): ?>
									<th><?=$arDay['SHORT']?></th>
								<?php endforeach; ?>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($arResult['MONTH'] as $arWeek): ?>
								<tr>
									<?php foreach ($arWeek as $arDay): ?>
										<?php if ($arDay['td_class'] == 'NewsCalOtherMonth'): ?>
											<td></td>
										<?php else: ?>
											<?php
												$today = $arDay['td_class'] == 'NewsCalToday';
												$active = key_exists('EVENTS', $arResult) && key_exists($arDay['day'], $arResult['EVENTS']);
												$class = '';
												if ($today) {
													$class = '_active-bg';
												} elseif ($active) {
													$class = '_active-c';
												}
												if ($active) {
													$class .= ' event-day-nonempty';
												}
											?>
											<td<?=($class ? ' class="' . $class . '"' :  '')?><?=($active ? ' data-event_day="' . $arDay['day'] . '"' : '')?>>
												<?=$arDay['day']?>
												<?php if ($active): ?>
													<span class="table-color">
														<?php foreach (array_reverse($arResult['EVENTS'][$arDay['day']]) as $arEvent): ?>
															<span style="background-color:<?=$arEvent['COLOR']?>;"></span>
														<?php endforeach; ?>
													</span>
												<?php endif; ?>
											</td>
										<?php endif; ?>
									<?php endforeach; ?>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				<?php foreach($arResult['MONTH'] as $arWeek):?>
					<?php foreach($arWeek as $arDay): ?>
						<?php
							if ($arDay['td_class'] == 'NewsCalOtherMonth') {
								continue;
							}
						?>
						<?php if (key_exists('EVENTS', $arResult) && key_exists($arDay['day'], $arResult['EVENTS'])): ?>
							<div class="b-timetable-list__wrap"<?=(date('j') != $arDay['day'] ? ' style="display:none;"' : '')?> data-event_day="<?=$arDay['day']?>">
								<div class="b-timetable__date"><?=(
									FormatDate(
										'j F Y',
										MakeTimeStamp($arResult['currentYear'] . '-' . str_pad($arResult['currentMonth'], 2, '0', STR_PAD_LEFT) .  '-' . str_pad($arDay['day'], 2, '0', STR_PAD_LEFT) . ' 00:00:00', 'YYYY-MM-DD HH:MI:SS')
									)
								)?></div>
								<?php foreach ($arResult['EVENTS'][$arDay['day']] as $arEvent): ?>
									<div class="b-timetable-list__item b-timetable-list-item">
										<div class="b-timetable-list-item__timetable b-timetable-list-item__block">
											<div class="b-timetable-list-item__color" style="background-color:<?=$arEvent['COLOR']?>;"></div>
											<div class="b-timetable-list-item__text"><?=$arEvent['NAME']?></div>
										</div>
										<div class="b-timetable-list-item__location b-timetable-list-item__block">
											<div class="b-timetable-list-item__img"><img src="/f/img/location-ico.svg" alt="..."></div>
											<div class="b-timetable-list-item__text"><?=$arEvent['ADRESS']?></div>
										</div>
										<div class="b-timetable-list-item__time b-timetable-list-item__block">
											<div class="b-timetable-list-item__img"><img src="/f/img/clock-ico.svg" alt="..."></div>
											<div class="b-timetable-list-item__text"><?=$arEvent['TIME']?></div>
										</div>
										<div class="b-timetable-list-item__info">
											<p><strong><?=$arEvent['TYPE']?></strong></p>
											<?=$arEvent['PREVIEW_TEXT']?>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endforeach; ?>
			</div>
			<div class="b-timetable__ritem b-list__item">
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
<?php
