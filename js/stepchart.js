(function (document, window, undefined) {

//	window.alert('laatste versie');

  // Alert of focus on button

    jQuery('.stepchart__button').one('click focus', function() {

      var stepChartDescr = jQuery(this).parent().find('.stepchart__description');

      console.log(stepChartDescr.attr('aria-hidden'));

      if(stepChartDescr.attr('aria-hidden') === 'true'){
        // is hidden
        stepChartDescr.attr('aria-hidden', 'false');
      }

    });



})(document, window  );
