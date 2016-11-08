$(function () {
	$.nette.init();

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
	})
});
