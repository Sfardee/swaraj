/**
 * @file
 * Customization on contributor modules.
 */
(function($, Drupal, drupalSettings) {
    'use strict';
    Drupal.behaviors.validation = {
      attach: function(context, settings) {
        if (!Drupal.behaviors.validation.click_set) {
          $(document).ready(function(e) {
            var pathname = window.location.pathname;
            if ((pathname.indexOf("/admin/config") != -1) && (pathname.indexOf("/admin/structure") != -1)) {
              $("form :input").each(function(){
                  $(this).alphanum({
                    disallow: '\{}[]`~',
                    allow: "<>()'/*;=%+^!-@#&_:,.?"
                  });
              });
            }
            $('#name').alphanum({
              allow: ".'"
            });
            $('#edit-name').off('.alphanum');
            $('#edit-pass').off('.alphanum');
            $('.search-text-box').alphanum();
            $('#title1').alphanum();
            $('#title2').alphanum();
            $('#message1').alphanum();
            $('#message2').alphanum();
            $('#spare-part').alphanum();
            $('#mobile-number').numeric(); 
            $('#pin').numeric();
            $('#message1').on('keyup',function() 
            {
              var limit = parseInt($(this).attr('maxlength'));
              //get the current text inside the textarea
              var text = $(this).val();
              //count the number of characters in the text
              var chars = text.length;
              //check if there are more characters then allowed
              if(chars > limit){
                  //and if there are use substr to get the text before the limit
                  var new_text = text.substr(0, limit);
                  //and change the current text with the new text
                  $(this).val(new_text);
              }
            });
            $('#message2').on('keyup',function() 
            {
              var limit = parseInt($(this).attr('maxlength'));
              //get the current text inside the textarea
              var text = $(this).val();
              //count the number of characters in the text
              var chars = text.length;
              //check if there are more characters then allowed
              if(chars > limit){
                  //and if there are use substr to get the text before the limit
                  var new_text = text.substr(0, limit);
                  //and change the current text with the new text
                  $(this).val(new_text);
              }
            });
            $('#message').on('keyup',function() 
            {
              var limit = parseInt($(this).attr('maxlength'));
              //get the current text inside the textarea
              var text = $(this).val();
              //count the number of characters in the text
              var chars = text.length;
              //check if there are more characters then allowed
              if(chars > limit){
                  //and if there are use substr to get the text before the limit
                  var new_text = text.substr(0, limit);
                  //and change the current text with the new text
                  $(this).val(new_text);
              }
            });
          });
        }
        Drupal.behaviors.validation.click_set = true;
      }
    }
  })(jQuery, Drupal, drupalSettings);