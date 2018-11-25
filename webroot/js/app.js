$(document).ready(function(){
	
/**
 * Sweep the page for bindings indicated by HTML attribute hooks
 * 
 * Class any DOM element with event handlers.
 * Place a 'bind' attribute in the element in need of binding.
 * bind="focus.revealPic blur.hidePic" would bind two methods
 * to the object; the method named revealPic would be the focus handler
 * and hidePic would be the blur handler. All bound handlers
 * receive the event object as an argument
 * 
 * Version 2
 * 
 * @param {string} target a selector to limit the scope of action
 * @returns The specified elements will be bound to handlers
 */
function bindHandlers(target) {
    if (typeof(target) == 'undefined') {
        var targets = $('*[bind*="."]');
    } else {
		var targets = $(target).find('*[bind*="."]')
	}
	targets.each(function(){
		var bindings = $(this).attr('bind').split(' ');
		for (i = 0; i < bindings.length; i++) {
			var handler = bindings[i].split('.');
			if (typeof(window[handler[1]]) === 'function') {
				// handler[0] is the event type
				// handler[1] is the handler name
				$(this).off(handler[0]).on(handler[0], window[handler[1]]);
			}
		}
	});
}

/**
 * new jquery function to center something in the scrolled window
 * 
 * Sets the css left and top of the chained element
 */
jQuery.fn.center = function() {
//    this.css("position", "fixed");
    this.css("top", Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) +
            $(window).scrollTop()) + "px");
    this.css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) +
            $(window).scrollLeft()) + "px");
    return this;
}

/**
 * Toggle an element
 * 
 * @param string element selector of the element to toggle
 * @returns {undefined}
 */
//function toggleThis(element) {
//    $(element).toggle(50, function() {
//        // animation complete.
//    });
//}

/**
 * Set up the click on a node to control the display-toggle of another node
 * 
 * Any <item class=toggle id=unique_name> will toggle <item class=unique_name> on click
 */
function initToggles() {
    $('.toggle').unbind('click').bind('click', function(e) {
		var id = e.currentTarget.id;
        $('.' + $(this).attr('id')).toggle(50, function() {
            // animation complete.
			if (typeof(statusMemory) == 'function') {
				statusMemory(id, e);
			}
        });
    })
}

function initToggleHits() {
    $('.hit').trigger('click');
}

    initToggles();
    initToggleHits();
	bindHandlers();

});

//Foundation JavaScript
// Documentation can be found at: http://foundation.zurb.com/docs
$(document).foundation();

//**********************

function maximize() {
	var zone_tool = $('a#maximize');
	var edit_zone = $('.edit-zone');
	var preview_zone = $('.preview-zone');
	
	if (zone_tool.html() == 'Expand') {
		edit_zone.data('w', edit_zone.css('width'));
		preview_zone.data('w', preview_zone.css('width'));
		edit_zone.css('width', '100%');
		preview_zone.css('width', '100%');
		zone_tool.html('Reduce');
		
	} else {
		edit_zone.css('width', edit_zone.data('w'));
		preview_zone.css('width', preview_zone.data('w'));
		zone_tool.html('Expand');
	}
}