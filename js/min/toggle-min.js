/*
// Gebruiker Centraal - toggle.js
// ----------------------------------------------------------------------------------
// JS voor accordions op homepage
// ----------------------------------------------------------------------------------
// @package   	ictu-gc-posttypes-inclusie
// @author    	Paul van Buuren
// @license		GPL-2.0+
// @version		0.0.11a
// @desc.		JS homepage voor openen en sluiten modal windows verbeterd.
// @credits		Scott Vinkle - see: https://codepen.io/svinkle/pen/mKfru
//				via https://a11yproject.com/patterns.html
// @link      	https://github.com/ICTU/ictuwp-plugin-inclusie
 */
var console,isSmallerScreenSize=!0,showOneAnswerAtATime=!1;function checkWidthChange(e){e.matches?(isSmallerScreenSize=!1,showOneAnswerAtATime=!0):(isSmallerScreenSize=!0,showOneAnswerAtATime=!1)}if(function(e,a,t){"use strict";var i,r=jQuery("#home-chart"),n=jQuery(".js-openclosebutton"),s=jQuery(".js-descriptionbox"),c=!1;jQuery(":focusable");r.addClass("jsloaded"),r.attr("tabindex","0"),r.focus();var o=function(){i=null,c=!1,s.each(function(){var e=jQuery(this);e.hasClass("active")&&(e.removeClass("active"),e.find(".btn--close").remove(),e.attr("aria-hidden","true"))})},l=function(e,a){a.each(function(){jQuery(this).attr("aria-selected","false")}),e.attr({"aria-selected":"true"})};jQuery(":focusable").bind("focus",function(e){c&&showOneAnswerAtATime&&!isSmallerScreenSize&&(1==jQuery(e.target).parents("#"+i.attr("id")).length||(e.stopPropagation(),i.focus()))});n.each(function(e){jQuery(this).attr({id:"faq-question-"+e,role:"tab","aria-controls":"faq-answer-"+e,"aria-expanded":"false","aria-selected":"false"})}),s.each(function(e){jQuery(this).attr({id:"faq-answer-"+e,role:"tabpanel","aria-labelledby":"faq-question-"+e,"aria-hidden":"true"})}),r.each(function(){var e=jQuery(this),a=e.find(".js-openclosebutton");e.attr({role:"tablist","aria-multiselectable":"true"}),a.each(function(e){var t=jQuery(this);0===e&&t.attr("tabindex","0"),t.on("click",function(){!function(e,a){var t=e.next();if(l(e,a),t.hasClass("active"))t.removeClass("active"),e.attr("aria-expanded","false"),t.attr("aria-hidden","true"),t.attr("style","");else{if(showOneAnswerAtATime&&(s.removeClass("active").attr("aria-hidden","true"),n.attr("aria-expanded","false")),t.addClass("active"),e.attr("aria-expanded","true"),t.attr("aria-hidden","false"),!isSmallerScreenSize){var r=-1*(t.height()+210);if(t.attr("style","transform: translateY("+r+"px)"),t.find("btn--close").length);else{t.append('<button class="btn--close">×</button>');var u=t.find("button.btn--close");u.attr("aria-label","Sluit pop-up"),u.on("click",o)}}c=!0,(i=t).attr("tabindex","0"),i.focus()}}(jQuery(this),a)}),t.on("focus",function(){l(jQuery(this),a)})})})}(document,window),matchMedia){var mq=window.matchMedia("(min-width: 800px)");mq.addListener(checkWidthChange),checkWidthChange(mq)}