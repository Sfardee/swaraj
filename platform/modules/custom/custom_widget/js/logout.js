/**
 * @file
 * Customization on contributor modules.
 */
(function($, Drupal, drupalSettings) {
  'use strict';
  Drupal.behaviors.logout = {
    attach: function(context, settings) {
      if (!Drupal.behaviors.logout.click_set) {
        $(document).ready(function(e) {
          // if (localStorage.swarajclickcount) {
          //   localStorage.swarajclickcount = Number(localStorage.swarajclickcount) + 1;
          // } else {
          //   localStorage.swarajclickcount = 1;
          // }
          // console.log('localStorage.swarajclickcount-' + localStorage.swarajclickcount);
          // $(window).on('beforeunload', function() {     
          //   console.log('localStorage.swarajclickcount-' + localStorage.swarajclickcount);
          //   localStorage.swarajclickcount = Number(localStorage.swarajclickcount) - 1;
          //   console.log('localStorage.swarajclickcount-' + localStorage.swarajclickcount);
          //   if (localStorage.swarajclickcount == 0) {
          //     localStorage.clear();
          //     navigator.sendBeacon('user/logout', null);
          //   }
          //   return;
          // });  
          var uid = parseInt(drupalSettings.user.uid); 
          // keep polling for logged in users
          if (uid > 0) {
            var w;

            if(typeof(Worker) !== "undefined") {
              if(typeof(w) == "undefined") {
                w = new Worker(drupalSettings.path.baseUrl + "modules/custom/custom_widget/js/workers.js");
              }
              w.onmessage = function(event) {
                $.ajax({
                  url: '/user-session-update',
                  global: false,
                  type: 'POST',
                  data: {},
                  cache: false,
                  async: false, //blocks window close
                  success: function(response) {
                    if (response['msg'] == "logout"){
                      window.location.href = drupalSettings.path.baseUrl + 'user/login';
                    }
                  }
                });
              };
            } else {
              setInterval(
                function(){ 
                  $.ajax({
                    url: '/user-session-update',
                    global: false,
                    type: 'POST',
                    data: {},
                    cache: false,
                    async: false, //blocks window close
                    success: function(response) {
                      if (response['msg'] == "logout"){
                        window.location.href = drupalSettings.path.baseUrl + 'user/login';
                      }
                    }
                  }
                );
              },
              5000);
            }
          }
        });
      }
      Drupal.behaviors.logout.click_set = true;
    }
  }
})(jQuery, Drupal, drupalSettings);