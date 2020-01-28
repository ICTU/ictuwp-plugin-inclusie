/*
 * Create behaviours for menu
 */


(function ($, document, window) {

  var trigger = $('.l-content-menu-wrapper');
  var nav = $('.content-menu');
  var cmHeight = $('.content-menu > .l-inner').height() + 110;
  var triggerBtnText = $('.btn--trigger-open span');

  function initWaypoints() {
    Waypoint.refreshAll();

    cmHeight = $('.content-menu > .l-inner').height() + 110;

    trigger.next().css('margin-top', cmHeight + 'px');
    trigger.next().addClass('l-cm-next');

    trigger.waypoint(function (direction) {
      if (direction === 'down') {
        nav.addClass('fixed');
        nav.removeClass('open');
        trigger.next().css('margin-top', '50px');
        triggerBtnText.text(contentmenu.open);
      }
      else if (direction === 'up') {
        nav.removeClass('fixed');
        nav.addClass('open');
        trigger.next().css('margin-top', cmHeight + 'px');
        triggerBtnText.text(contentmenu.close);
      }
    });

    return cmHeight;
  }

  $(window).on('resize', function () {
    initWaypoints();
    var cmHeight = $('.content-menu > .l-inner').height() + 110;
  });

  $(window).on('load', function () {
    initWaypoints();
  });

// Open / Close button

  $('.btn--trigger-open').click(function () {
    if (nav.hasClass('open')) {
      // Is open, so close
      triggerBtnText.text(contentmenu.open);

    } else if (!nav.hasClass('open')) {
      // Id closed, so open
      triggerBtnText.text(contentmenu.close);
    }
    nav.toggleClass('open');
  });


// Content menu active link, window animation

  $('.content-menu__link').click(function (e) {
    e.preventDefault();


    var goTo = $($(this).attr('href')).offset().top - 80;

    if (!$(this).hasClass('active')) {
      // Remove active classes
      $('.has-focus').removeClass('has-focus');
      $('.content-menu__link.active').removeClass('active');


      $('html, body').stop().animate({
        scrollTop: goTo
      }, 800, 'easeInOutExpo');

      nav.removeClass('open');
      triggerBtnText.text(contentmenu.open);

      // Add focus class to target, add active
      $(this).addClass('active');
      $($(this).attr('href')).addClass('has-focus');
    }
  });

  $('.content-menu__link').focus(function () {
    nav.addClass('open');
  });

  $(window).on('mousewheel', function () {
    $('html, body').stop();
  });

})(jQuery, document, window);
