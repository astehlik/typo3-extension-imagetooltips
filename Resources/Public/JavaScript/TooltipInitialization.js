
$(document).ready(function() {

	$('figure.csc-textpic-image, dl.csc-textpic-image, li.csc-textpic-image, div.csc-textpic-image, div.csc-textpic-single-image').each(function() {

		var tooltipDiv = $(this).next();

		if (!tooltipDiv.hasClass('tx-imagetooltips-tooltip')) {
			return;
		}

		var positionX = 'center';
		var positionY = 'top';
		var opacity = 1;
		var offsetX = 0;
		var offsetY = 0;

		if (tooltipDiv.is('[data-tx-imagetooltips-position-x]')) {
			positionX = tooltipDiv.attr('data-tx-imagetooltips-position-x');
		}
		if (tooltipDiv.is('[data-tx-imagetooltips-position-y]')) {
			positionY = tooltipDiv.attr('data-tx-imagetooltips-position-y');
		}
		if (tooltipDiv.is('[data-tx-imagetooltips-offset-x]')) {
			offsetX = new Number(tooltipDiv.attr('data-tx-imagetooltips-offset-x'));
		}
		if (tooltipDiv.is('[data-tx-imagetooltips-offset-y]')) {
			offsetY = new Number(tooltipDiv.attr('data-tx-imagetooltips-offset-y'));
		}
		if (tooltipDiv.is('[data-tx-imagetooltips-opacity]')) {
			opacity = tooltipDiv.attr('data-tx-imagetooltips-opacity') / 100;
		}

		$(this).tooltip({

			offset: [offsetY, offsetX],
			opacity: opacity,
			position: positionY + ' ' + positionX,

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