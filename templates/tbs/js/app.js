!function($) {
	$(function() {
		// tooltips
		$('a.tip').tooltip();
		// popup
		$('a.pop').popover();
		//datepicker
		var startDate = new Date(2012,1,20);
			var endDate = new Date(2012,1,25);
		$('#dp').datepicker()
	});
}(window.jQuery)