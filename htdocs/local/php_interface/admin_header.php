<?php

use Bitrix\Main\Context,
	Bitrix\Main\UI\Extension;

$request = Context::getCurrent()->getRequest();
$elementId = $request->getQuery('ID');

if ($elementId) {
	$arElement = CIBlockElement::getById($elementId)->getNext();

	if ($arElement['DETAIL_PICTURE']) {
		$arDetailPicture = CFile::getById($arElement['DETAIL_PICTURE'])->getNext();
	}
}

CJSCore::Init(['jquery']);
Extension::load('ui.dialogs.messagebox');
?>
<script>
	BX.addCustomEvent('OnEditorInitedBefore', function() {
		var _this = this;
		// отучаем резать тэги в визуальном редакторе, чтобы можно было вставлять svg
		BX.addCustomEvent(this, 'OnGetParseRules', BX.proxy(function() {
			_this.rules.tags.span = {};
			_this.rules.tags.svg = {};
			_this.rules.tags.use = {};
		}, this));
	});
	BX.addCustomEvent('OnEditorInitedAfter', function() {
		this.config.cleanEmptySpans = false;
		this.config.cssIframePath = '/local/templates/main/template_styles.css?';
	});

	function newsPreview(iblockId) {
		var form = $('#form_element_' + iblockId + '_form');

		$.ajax({
			type: 'post',
			url: '/local/scripts/element_preview.php?IBLOCK_ID=' + iblockId,
			data: form.serialize(),
			dataType: 'json',
			success: function(data) {
				if (data.errors) {
					const messageBox = new BX.UI.Dialogs.MessageBox({
						title: 'Ошибка',
						message: data.errors.join('<br>'),
						modal: true,
						buttons: BX.UI.Dialogs.MessageBoxButtons.OK
					});
					messageBox.show();
				} else {
					var CheatSheetDialog = new BX.CDialog({
						title: 'Предварительный просмотр',
						content: '<iframe src="' + data.url + '?show_preview=y" class="dialog_news_preview"><iframe>',
						resizable: true
					});

					CheatSheetDialog.SetButtons([
						{
							title: 'Открыть в отдельном окне',
							id: 'new_tab_preview',
							name: 'new_tab_preview',
							action: function() {
								window.open(data.url + '?show_preview=y', '_blank').focus();
							}
						},
						BX.CDialog.btnClose
					]);

					CheatSheetDialog.Show();

					$(CheatSheetDialog.Get()).find('.bx-core-adm-dialog-content').css({overflowY: 'hidden', position: 'relative'});
					$(CheatSheetDialog.Get()).find('.bx-core-adm-icon-expand').trigger('click');
				}
			},
			error: function() {
				const messageBox = new BX.UI.Dialogs.MessageBox({
					title: 'Ошибка',
					message: 'Неизвестная ошибка',
					modal: true,
					buttons: BX.UI.Dialogs.MessageBoxButtons.OK
				});
				messageBox.show();
			}
		});
	}

	$(function() {
		var dpd = $('[name="DETAIL_PICTURE_descr"]');
		if (!dpd.length) {
			return;
		}

		var descriptor = $('<textarea>' + '<?=preg_replace('/[\r\n]+/', '\n', $arDetailPicture['DESCRIPTION'])?>' + '</textarea>');

		$.each(dpd.get(0).attributes, function() {
			if (this.specified) {
				descriptor.attr(this.name, this.value);
			}
		});
		descriptor.css({width: 'calc(100% - 12px)', resize: 'none', marginTop: '8px'});
		dpd.replaceWith(descriptor);

		$('#bx_file_detail_picture_block .adm-fileinput-item-panel-btn.adm-btn-setting').on('click', function() {
			setTimeout(function() {
				var fmd = $('#FMeditorDescription');
				descriptor = descriptor.clone();
				$.each(fmd.get(0).attributes, function() {
					if (this.specified) {
						descriptor.attr(this.name, this.value);
					}
				});
				descriptor.css({width: 'calc(100% - 10px)', marginTop: '-5px'});
				fmd.replaceWith(descriptor);

				$('.popup-window-button-accept').on('click', function() {
					$('[name="DETAIL_PICTURE_descr"]').val(descriptor.val());
				});
			}, 16);
		});
	});

	function feedbackReplay(elementId) {
		var form = $('#form_element_<?=CIBlockTools::getIBlockId('feedback')?>_form');

		$.ajax({
			type: 'post',
			url: '/local/scripts/feedback_replay.php?ELEMENT_ID=' + elementId,
			data: form.serialize(),
			dataType: 'json',
			success: function(data) {
				if (data.errors) {
					const messageBox = new BX.UI.Dialogs.MessageBox({
						title: 'Ошибка отправки',
						message: data.errors.join('<br>'),
						modal: true,
						buttons: BX.UI.Dialogs.MessageBoxButtons.OK
					});
					messageBox.show();
				} else {
					const messageBox = new BX.UI.Dialogs.MessageBox({
						title: 'Успешно отправлено',
						message: 'Сообщение отправлено на почту, указанную в форме',
						modal: true,
						buttons: BX.UI.Dialogs.MessageBoxButtons.OK
					});
					messageBox.show();
				}
			},
			error: function() {
				const messageBox = new BX.UI.Dialogs.MessageBox({
					title: 'Ошибка отправки',
					message: 'Неизвестная ошибка',
					modal: true,
					buttons: BX.UI.Dialogs.MessageBoxButtons.OK
				});
				messageBox.show();
			}
		});
	}
</script>
<style>
.dialog_news_preview {
	border: none;
	width: calc(100% - 24px);
	height: calc(100% - 24px);
	outline: 4px solid #ffa500;
	position: absolute;
	right: 12px;
	top: 12px;
}
</style>
