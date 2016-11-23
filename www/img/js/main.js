

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

/*****/

$(function () {
	$.nette.init();

	$.nette.ext({
		before: function(response) {
			Loader.show();
		},
		success: function(response) {
			Loader.hide();
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
	})
});
