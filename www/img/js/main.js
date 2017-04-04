

/************/

var Loader = new function() {

	var reference = this;

	var $container = $('.page-loader');

	this.show = function() {
		$container.show();
	}

	this.hide = function() {
		$container.hide();
	}

	this.init = function() {
		$container = $('.page-loader');

		$container.show();

		$(window).on('beforeunload', function() {
			reference.show();
		});
	}
}

var tinyloader = new function () {

	this.load = function() {
		if (typeof(tinymce) == 'undefined') {
			return;
		}

		tinymce.remove();

		tinymce.init({
			selector: "textarea.mceEditor",
			entity_encoding : "raw",
			menubar: false,
			plugins: [
				'advlist autolink lists link image charmap print preview anchor',
				'searchreplace visualblocks code fullscreen',
				'insertdatetime media table contextmenu paste code'
			],
			toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | removeformat',
		});
	}
}

/*****/

$(function () {
	$.nette.init();

	$.nette.ext({
		before: function(response) {
			Loader.show();
		},
		success: function(response) {
			Loader.hide();

			tinyloader.load();
		}
	});

	$('#button-nav-open-sidebar').click(function(E) {
		if (!$(this).hasClass('is-open')) {
			$(this).addClass('is-open');
			$(this).removeClass('is-closed');
			$('.sidebar').addClass('nav-open');
			$('.topmenu').addClass('nav-open');
		}
		else {
			$(this).removeClass('is-open');
			$(this).addClass('is-closed');
			$('.sidebar').removeClass('nav-open');
			$('.topmenu').removeClass('nav-open');
		}
	});

	$(document).ready(function(){
		Loader.hide();

		tinyloader.load();
	})
});
