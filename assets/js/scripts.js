(function ($, root, undefined) {
	$(function () {
		$(document).ready(function(){
			//s
			$("#nav-icon").click(function(){
				$(this).toggleClass('open');
				$(".header-mobile").slideToggle('fast');
			});
		
			// header nav dropdown
			$(".nav-menu li ul").before("<span class='arrow-down'><i class='fas fa-angle-down'></i></span>");
			$(".arrow-down").click(function() {
				var ul = $(this).next('ul');
				$(ul).slideToggle();
			});
		});
	});
})(jQuery, this);

