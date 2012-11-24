
$(document).ready(function() {

	$('figure.csc-textpic-image, dl.csc-textpic-image, li.csc-textpic-image, div.csc-textpic-image, div.csc-textpic-single-image').each(function() {

		var tooltipDiv = $(this).next();

		if (!tooltipDiv.hasClass('tx-imagetooltips-tooltip')) {
			return;
		}

		$(this).tooltip({

				// append tooltip to body to minimize problems with
				// other styles like overflow:hidden
			onBeforeShow: function() {

				if (this.tooltipMoved) {
					return;
				}

				this.tooltipMoved = true;

				this.getTip().appendTo($("body"));
			}
		});

	});

});