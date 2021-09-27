(function ($, document, window) {

  var setFocus = false;
  var breakpoint_desktop = 650; // breakpoint for desktop styling, in pixels

  // On hover or click set popover.
  jQuery('.stepchart__button').focus(function (e) {

    setFocus = true;
    var chartDescr = jQuery(this).parent().find('.stepchart__description');
    setPopover(chartDescr);

  }).click(function (e) {
    // Only set if element has no focus to prevent triggering twice (focus / click)
    if (setFocus === false) {
      var chartDescr = jQuery(this).parent().find('.stepchart__description');
      setPopover(chartDescr);
    }

    setFocus = false;
  });

  var setPopover = function (popover) {

    var windowWidth = jQuery(window).width();

    if (popover.attr('hidden')) {
      // If bigger then desktop remove focus from other popovers
      if (windowWidth >= breakpoint_desktop) {
        jQuery('.stepchart__description').attr('hidden', '');
        jQuery('.show-popover').removeClass('show-popover')
      }
      popover.removeAttr('hidden');
      popover.parent().addClass('show-popover');
    } else {
      popover.attr('hidden', '');
      popover.parent().removeClass('show-popover');
    }
  }


  // Remove all popups when we are on desktop
  jQuery(window).resize(function () {
    var windowWidth = jQuery(window).width();

    if (windowWidth >= breakpoint_desktop) {
      jQuery('.stepchart__description[data-visually-hidden=false]').attr('hidden', '');
      jQuery('.show-popover').removeClass('show-popover')
    }
  });

  // CLose when clicking outside of popover
  $(document).mouseup(function (e) {

    var container = $('.stepchart__item.show-popover');

    // if the target of the click isn't the container nor a descendant of the container
    if (!container.is(e.target) && container.has(e.target).length === 0) {
      container.removeClass('show-popover');
      container.find('.stepchart__description').attr('hidden', '');
    }

  });

  jQuery(document).on('keyup', function (evt) {
    if (evt.keyCode == 27) {

      // hide all popovers on ESC key press
      var windowWidth = jQuery(window).width();

      // If bigger then desktop close / hide popovers
      if (windowWidth >= breakpoint_desktop) {
        jQuery('.stepchart__description').attr('hidden', '');
        jQuery('.show-popover').removeClass('show-popover')
      }

    }
  });


})(jQuery, document, window);

