(function (document, window, undefined) {

  var setFocus = false;
  var bp = 650;

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

    if (popover.attr('aria-hidden') === 'true') {
      // If bigger then desktop remove focus from other popovers
      if (windowWidth >= bp) {
        jQuery('.stepchart__description[aria-hidden=false]').attr('aria-hidden', 'true');
        jQuery('.show-popover').removeClass('show-popover')
      }
      popover.attr('aria-hidden', 'false');
      popover.parent().addClass('show-popover');

    } else {
      popover.attr('aria-hidden', 'true');
      popover.parent().removeClass('show-popover');
    }
  }

  jQuery(window).resize(function(){
    var windowWidth = jQuery(window).width();

    // Remove all popups when we are on desktop
    if (windowWidth >= bp) {
      jQuery('.stepchart__description[aria-hidden=false]').attr('aria-hidden', 'true');
      jQuery('.show-popover').removeClass('show-popover')
    }
  });

})(document, window);
