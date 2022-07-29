'use strict'


//---header for input
$('.header-lens').on('click', function(){
   $(this).closest('.header-right__info-input').addClass('show').find('input').focus();
});
$('.js-show').on('click', function(e){
   e.preventDefault();
   $('.js-show').parent().addClass('show');
});


//---header for input finish


//------essay foto

const inputFile = document.getElementById("fileElem");
const preview = document.getElementById("dropbox");
const imgBox = document.querySelector(".b-essay-form__foto-block");
const controls = document.querySelector('.b-essay-form__foto-controls');
const refresFile = document.querySelector('.js-file-refresh');
const removeFile = document.querySelector('.js-file-remove');

//---drag-and-drop
if(inputFile) {
   imgBox.addEventListener("dragenter", dragenter, false);
   imgBox.addEventListener("dragover", dragover, false);
   imgBox.addEventListener("drop", drop, false);
   function dragenter(e) {
      e.stopPropagation();
      e.preventDefault();
   }
      
   function dragover(e) {
      e.stopPropagation();
      e.preventDefault();
   }
   function drop(e) {
      e.stopPropagation();
      e.preventDefault();
      
      var dt = e.dataTransfer;
      var files = dt.files;
      
      handleFiles(files);
   }

   function handleFiles(files) {
      for (var i = 0; i < files.length; i++) {
         var file = files[i];
         if (!file.type.startsWith('image/')){ continue }
         var img = document.createElement("img");
         img.classList.add("b-essay-foto");
         img.file = file;
         preview.innerHTML = '';
         preview.appendChild(img);
         preview.classList.add('is-foto');
         controls.classList.add('show');
         var reader = new FileReader();
         reader.onload = (function(aImg) { return function(e) { aImg.src = e.target.result; }; })(img);
         reader.readAsDataURL(file);
      }   
   };

   removeFile.addEventListener('click', function() {
      inputFile.value = '';
      preview.innerHTML = '';
      preview.classList.remove('is-foto');
      controls.classList.remove('show');
   });

   refresFile.addEventListener('click', function() {
      if(inputFile) inputFile.click();
   });
}
   
//------essay foto finish

//------Header submenu hover
var heightSliderBox = $('.header-slider').outerHeight();
var heightSubMenu = $('.header').outerHeight();
$(window).on('resize', function () {
   heightSubMenu = $('.header').outerHeight();
});
$('.header__menu').on('mouseover', function() { 
   var heightDropBox = 0; 
   $('.header__sublist').each(function(i) {
      if($(this).innerHeight() > heightDropBox) {
         heightDropBox = $(this).innerHeight();
      }
   }); 
   var heightTotal = heightSliderBox - heightDropBox;
   var heightAllSubMenu = heightSubMenu + heightDropBox
   $('.header-slider').css('height', heightTotal+'px');
   $('.header-slider--circle').css('bottom', heightTotal+'px').addClass('is-show');
   $('.header-page').addClass('is-drop');
   $('.is-drop').css('height', heightAllSubMenu+'px');
});

$('.header__menu').on('mouseout', function() {
  $('.header-slider').css('height', 'auto');
  $('.header').removeAttr('style');
//   $('.header').css('height', heightSubMenu+'px');  
  $('.header-page').removeClass('is-drop'); 
  $('.header-slider--circle').css('bottom', 'auto').removeClass('is-show');
});
//------Header submenu hover finish

$('.b-info__item').on('click', function(e) {
   e.preventDefault();
   var news_id = $(this).data('news');
   $('.b-header-slide').removeClass('show');
   $('.b-header-slide[data-news-content=' + news_id + ']').addClass('show');
   if(!$('.b-info__item.active').length) {
      $(this).addClass('active');
   } else if($(this).hasClass('active')) {
      return;
   } else {
      $('.b-info__item').removeClass('active');
      $(this).addClass('active');
   }
});

function stopVideo(el) {
   if (el.length) {
      Array.prototype.forEach.call(el, (item) => {
         var videos = item.querySelectorAll('iframe, video');
         Array.prototype.forEach.call(videos, (video) => {
            if (video.tagName.toLowerCase() === 'video') {
               video.pause();
            } else {
               video.contentWindow.postMessage('{"event":"command","func": "pauseVideo", "args": ""}', '*')
               // video.contentWindow.postMessage(JSON.stringify('{"event":"command","func":"' + 'stopVideo' + '","args":""}', '*'));
            }
         });
      })
   }
}

var tabContainers = $('.b-content-slider__tab-container');
tabContainers.hide().filter(':first').show();
$('.b-content-slider__tab-link').click(function (e) {
  e.preventDefault();
  stopVideo(tabContainers);
  tabContainers.hide();
  tabContainers.filter(this.hash).fadeIn(300);
  $('.b-content-slider__tab-link').removeClass('active');
  $(this).addClass('active');
  return false;
}).filter(':first').click();


if($('.b-content-slider__slide').length > 3) {
   $('.b-content-slider-owl').slick({
      dots: false,
      infinite: true,
      speed: 300,
      slidesToShow: 3,
      slidesToScroll: 1,
      adaptiveHeight: true,
      responsive: [
         {
         breakpoint: 767,
         settings: {
               slidesToShow: 2
            }
         }
   ]
   });
}


//slider на главной странице
$(window).on('load resize', function () {
   if ($(this).width() > 1023) {
      $('.owl-carousel').trigger('destroy.owl.carousel');
      $('.header-slider__inner').removeClass('owl-carousel');
   } else {
      $('.header-slider__inner').addClass('owl-carousel');
      $('.header-slider__inner').owlCarousel({
         items: 1,
         loop: true,
         animateOut: 'fadeOut',
         pagination: true,
         dots: true,
         center: true,
         autoHeight:true
      });
   }

});

// slider длля видео
$('.b-vs-carusel').owlCarousel({
   items: 1,
   loop: false,
   nav: true,
   navigation : true,
   dots: false,
   center: true,
   URLhashListener:true,
   startPosition: 'URLHash'
});

$('.open-modal').on('click', function(e){
//	e.preventDefault();
   $('.b-vs__overlay, .b-vs__wrap').addClass('_active');
   $('body').addClass('open-menu');
//   return false;
})

$('.b-vs__overlay').on('click', function(e){   
   $(this).removeClass('_active');
   $('.b-vs__wrap').removeClass('_active');
   $('body').removeClass('open-menu');
});

$('.js-active-parent').on('click', function(e){
   e.preventDefault();
   $(this).parent().toggleClass('_active')
   $('body').toggleClass('open-menu')
})

$('.b-popup__close').on('click', function(){
   $('.b-popup').removeClass('_active')
})

$('body').on('click', '.b-popup__close', function () {
  $('.b-popup').removeClass('_active');
  $('body').removeClass('open-modal');
});

$('.js-active').on('click', function(e){
//   e.preventDefault();
   $('.js-active').removeClass('_active')
   $(this).addClass('_active')
});


$('.js-prevent').on('click', function(e){
   e.preventDefault();
})

// $(window).resize(function () {
//    if($(window).width() >= 1023){
//        $('.content-wrapper--mobile').removeClass('.content-wrapper');
//    };
// })
$('a.scroll-to').on('click', function(e){
   e.preventDefault();
   var anchor = $(this).attr('href');
   $('html, body').stop().animate({
         scrollTop: $(anchor).offset().top - 60
   }, 800);
});



$('.js-vall').on('blur', function(){
   if($(this).val()) {
      $(this).removeClass('form-error').addClass('form-success');
   } else {
      $(this).removeClass('form-success').addClass('form-error');
      $(this).val('').attr('placeholder', 'Заполните поле');
   }
});

//----регулярка на e-mail
$('.js-email').on('blur', function(){
   if($(this).val() && $(this).val().search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/)>-1) {
      $(this).removeClass('form-error').addClass('form-success');
   } else {
      $(this).removeClass('form-success').addClass('form-error');
      $(this).val('').attr('placeholder', 'Введите корректный e-mail');
   }
});

//----регулярка на телефон
$('.js-phone').on('blur', function(){
   if($(this).val() && $(this).val().search(/^(\s*)?(\+)?([- _():=+]?\d[- _():=+]?){10,14}(\s*)?$/)>-1) {
      $(this).removeClass('form-error').addClass('form-success');
   } else {
      $(this).removeClass('form-success').addClass('form-error');
      $(this).val('').attr('placeholder', 'Введите корректный номер телефона');
   }
});

$('.js-phone').on('focus', function() {
   $(this).removeClass('form-error').addClass('form-success');
});


//------табы
$(function(){ 
   $('.js-click').on('click', function(){
      $(this).next().slideToggle();
      $(this).toggleClass('__active')
   })

   $.mask.definitions['9'] = false;
   $.mask.definitions['5'] = "[0-9]";

   $('.js-phone').mask('+7 555 555 55 55');
   $('.js-index').mask('555555');
   $('.js-card').mask('55 5555 5555', {placeholder:'*'});

	$('.event-day-nonempty').on('click', function() {
		$('.b-timetable-list__wrap').hide();
		$('.b-timetable-list__wrap[data-event_day=' + $(this).data('event_day') + ']').show();
		$(this).closest('tbody').find('._active').removeClass('_active');
		$(this).addClass('_active');
	});

   // создает горизонтальную прокрутка при вызове мобильного меню

	var tdiv = $('<div>').css({overflowY: 'scroll', width: 50, height: 50, marginBottom: -50, visibility: 'hidden'}).appendTo($('body'));
	var width = tdiv[0].offsetWidth - tdiv[0].clientWidth;
	$('head').append('<style>' +
			'body.open-modal{padding-right:' + width + 'px;}' +
			'body.open-modal header{padding-right:' + width + 'px;background-position-x: calc(50% - ' + (width / 2) + 'px);}' +
			'body.open-modal footer{padding-right:' + width + 'px;margin-right:-' + width + 'px;}' +
		'</style>');
	tdiv.remove();
});

function fallbackCopyTextToClipboard(text) {
	var textArea = document.createElement('textarea');
	textArea.value = text;

	textArea.style.top = 0;
	textArea.style.left = 0;
	textArea.style.position = 'fixed';

	document.body.appendChild(textArea);
	textArea.focus();
	textArea.select();

	try {
		var result = document.execCommand('copy');
	} catch (err) {
		var result = false;
	}

	document.body.removeChild(textArea);
	return result;
}

$('.b-network__item.b-sharelink__copy').on('click', function(e) {
	e.preventDefault();
	var link = $(this).data('href');
	if (!navigator.clipboard) {
		if (fallbackCopyTextToClipboard(link)) {
			showPopupMessage('success', 'Ссылка скопирована в буфер обмена');
		} else {
			showPopupMessage('error', 'Ошибка копирования в буфер обмена');
		}
	} else {
		navigator.clipboard.writeText(link).then(function() {
			showPopupMessage('success', 'Ссылка скопирована в буфер обмена');
		}, function() {
			showPopupMessage('error', 'Ошибка копирования в буфер обмена');
		});
	}
});

function showPopupMessage(type, message) {
	var popup =
		'<div class="b-popup b-poppup-{{TYPE}} _active">' +
			'<div class="b-popup__wrap">' +
				'<div class="b-popup__inner">' +
					'<div class="b-popup__close"></div>' +
					'<div class="b-popup__img"><img src="/f/img/{{TYPE}}.svg" alt=""></div>' +
					'<div class="b-popup__text">{{MESSAGE}}</div>' +
				'</div>' +
			'</div>' +
		'</div>';

	$('.b-popup__close').trigger('click');
	$('body').addClass('open-modal').append($(popup.replace(/{{TYPE}}/g, type).replace('{{MESSAGE}}', message)));
}

autosize($('textarea.autosize'));

   //document.querySelector('#photo').onchange = function() {
   //	var img = new Image();
   //	img.onload = function() {
   //		alert(img.width + 'x' + img.height);
   //	};
   //
   //	img.onerror = function() {
   //		alert('Ошибка загрузки фото');
   //	};
   //
   //	alert(this.files[0].type); // image/jpeg
   //	img.src = URL.createObjectURL(this.files[0]);
   //};
   //
   //document.querySelector('#file').onchange = function() {
   //	alert(this.files[0].type); // application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document
   //	alert(this.files[0].size);
   //};

// var heightSliderBox = $('.header-slider').outerHeight(); 
// $('.header__menu').on('mouseover', function() { 
//    var heightDropBox = $('.header__sublist').innerHeight();     
//    var heightTotal = heightSliderBox - heightDropBox; 
//    $('.header-slider').css('height', heightTotal+'px');     
// });

// $('.header__menu').on('mouseout', function() {
//   $('.header-slider').css('height', heightSliderBox+'px'); 
// });

$('form[data-ajax_form_action]')
	.on('submit', function(e) {
		e.preventDefault();
		var form = this;
		var formData = new FormData(form);
		var dndPhotoContainer = $(form).find('.is-foto');
		if (dndPhotoContainer.length) {
			var dndPhoto = dndPhotoContainer.find('img');
			console.log(dndPhoto)
			if (dndPhoto.length) {
				formData.delete(dndPhotoContainer.data('input_name'));
				formData.append(dndPhotoContainer.data('input_name'), dndPhoto.get(0).file, dndPhoto.get(0).file['name']);
			}
		}
		formData.append('submit', 'Y');
		$.ajax({
			url: $(form).data('ajax_form_action'),
			method: form.method,
			data: formData,
			dataType: 'json',
			contentType: false,
			processData: false,
			success: function(response) {
				if (response.success) {
					showPopupMessage('success', response.message);
					form.reset();
					$(form).find('.form-success').removeClass('form-success');
				} else {
					showPopupMessage('error', 'Ошибка отправки формы<br><br>' + Object.values(response.errors).join('<br>'));
					for (var i in response.errors) {
						$(form).find('[name=' + i + ']').addClass('form-error');
					}
					$(form).find('[type=submit]').blur();
				}
			},
			error: function() {
				showPopupMessage('error', 'Ошибка отправки формы');
			}
		});
	})
	.find(':input').removeAttr('required');

$('#cookie_ok').on('click', function() {
	BX.setCookie('BITRIX_SM_cookie_ok', 'Y', {expires: 365 * 24 * 60 * 60});
	$(this).closest('.cookie').hide();
});
$('#cookie_close').on('click', function() {
	BX.setCookie('BITRIX_SM_cookie_ok', 'N');
	$(this).closest('.cookie').hide();
});


$('.f-form__inner input, .f-form__inner textarea').on('input',function() {
   let activeBtn = true;
   $('.js-vall').each(function() {
      if($(this).val() == '') activeBtn = false;
   });
   if(activeBtn) $('.f-form-block__btn').addClass('active');
   else $('.f-form-block__btn').removeClass('active');
});
