/**
 * @file
 * Customization on contributor modules.
 */

(function ($, Drupal, drupalSettings) {
  'use strict';
  Drupal.behaviors.custom = {
    attach: function (context, settings) {
        $(document).ready(function (e) {
          $(".the-farmer-carousel").owlCarousel({
            loop: false,
            responsiveClass: true,
            nav: true,
            dots: true,
            autoplay: false,
            autoplayHoverPause: true,
            responsive: {
              0: {
                items: 1,
                nav: true
              },
              600: {
                items: 2
              },
              1000: {
                items: 3
              }
            }
          });
          $(".commitment-carousel").owlCarousel({
            loop: false,
            responsiveClass: true,
            nav: true,
            dots: true,
            autoplay: false,
            autoplayHoverPause: true,
            responsive: {
              0: {
                items: 1,
                nav: false
              },
              600: {
                items: 1,
                nav: false
              },
              1000: {
                items: 1
              }
            }
          });
          $(".milestoneBox-carousel").owlCarousel({
            loop: false,
            responsiveClass: true,
            nav: false,
            //margin:70,
            dots: true,
            responsive: {
              0: {
                items: 1,
                dots: false
              },
              600: {
                items: 1
              },
              1000: {
                items: 1
              }
            }
          });
          $(".overview-crl").owlCarousel({
            loop: false,
            responsiveClass: true,
            nav: false,
            dots: true,
            autoplay: false,
            autoplayHoverPause: true,
            responsive: {
              0: {
                items: 1,
                nav: false
              },
              600: {
                items: 2,
                nav: false,
                margin: 20
              },
              1000: {
                items: 1
              }
            }
          });

          $('.galleryModal .close').click(function(e){
            var ifr = $(this).parent().parent().find('iframe');
            var src = ifr.attr("src");
            ifr.attr("src", '/empty.html');
            ifr.attr("src", src);
          });

          $(".youtube-video-box").on("hidden.bs.modal", function () {
            var ifr = $(this).find('iframe');
            var src = ifr.attr("src");
            ifr.attr("src", '/empty.html');
            ifr.attr("src", src);
          });

          $("#implement_model").selectpicker("hide");
          $("#implement_model").selectpicker();
          $("input[type='radio']").change(function() {
            if ($(this).val() == "tractor") {
              $("#tractor_model").selectpicker("show");
              $("#implement_model").selectpicker("hide");
            } else {
              $("#implement_model").selectpicker("show");
              $("#tractor_model").selectpicker("hide");
            }
          });

          $(".map-form .refresh").on('click', function(){
            $("#state").val('');
            $("#city").val('');
            $(".selectpicker").selectpicker("refresh");
            $("#pin").val('');
          });

          $('#views-exposed-form-search-article').hide();
          $('.search-btn-js').on('click', function(){
            var key = $('.search-text-box').val();
            window.location.href = drupalSettings.path.baseUrl + 'search?search=' + key;
          });
          $(".search-page-box").keypress(function(event) {
            var keycode = event.keyCode ? event.keyCode : event.which;
            if (keycode == "13") {
              var key = $(".search-page-box").val();
              window.location.href = drupalSettings.path.baseUrl + 'search?search=' + key;
            }
          });

          if ($(".search-text-box").length){
            if($(".search-product").length == 0 && $(".search-article").length == 0){
              $(".search-result-product").removeClass('hidden');
            }
          }

          if ($(".news-contentTab").length) {
            $.ajax({
              type: "POST",
              url: drupalSettings.path.baseUrl + "check-date",
              success: function(response) {
                if (response == "show") {
                  $(".group-datePicker").removeClass("hidden");
                }
              }
            });
          }

          $('#home-product li').on('click', function(){
            var url = '';
            var id = $(this).data("value");
            var link = $('.product-link').data("url");
            url = drupalSettings.path.baseUrl + link + '#Product' + id;
            $('#product-link').attr('href', url);
          });

          var hash = window.location.hash;
          if (hash) {
            if ($('.listing-tabs-carousel').length > 0) {
              $('.listing-tabs-carousel .tabsnav a[href="' + hash + '"]').click();
              $('.listing-tabs-carousel .tabsnav a[href="' + hash + '"]').trigger('mouseenter');
            }
          }

          var current = location.pathname;
          
          $('#tabsnav div a').each(function () {
            var $this = $(this);
            if ($this.attr('href').indexOf(current) !== -1) {
              $this.parent().addClass('active');
            }
          })

          $('#nav li a').each(function () {
            var $this = $(this);
            if ($this.attr('href').indexOf(current) !== -1) {
              $this.addClass('active');
            }
          });
          $("#company-name").hide();
          $("#company-name").removeAttr("required");
          $("#spare-part").hide();
          $(".radio-btn-container").hide();
          var model = getUrlParameter('model');
          var imple = getUrlParameter("imple");
          var request = getUrlParameter("request");
          if ($("#contactForm").length > 0) {
            if (request == "call") {
              $("#request").selectpicker();
              $("#request").selectpicker("val", "call back");
              $(".test-drive-bx").hide();
              $(".test-drive-bx").addClass('xyz');
              $("#tractor_model").removeAttr("required");
              $("#spare-part").removeAttr("required");
            } else if (request == "drive") {
              $("#request").selectpicker();
              $("#request").selectpicker("val", "test drive");
            } else if (request == "service") {
              $("#request").selectpicker();
              $("#request").selectpicker("val", "service request");
              $("#spare-part").show();
              $('.test-drive-bx .about-bx h6').text('Request A Service');
              $('.test-drive-bx .para').text('Count on us when it comes to your service requirements too. Select the date and time for assisting you.');
            } else if (request == "quote") {
              $("#request").selectpicker();
              $("#request").selectpicker("val", "quote");
              $('.test-drive-bx .about-bx h6').text('Get A Quote');
              $('.test-drive-bx .para').text('Our tractors are smartly designed for every farmer. Select tractor model, date and time to get the quote.');
            } 
            if(model) {
              $("#radio-tractor").attr("checked", "checked");
              $("#implement_model").prop('required',false);
              $("#tractor_model").prop('required',true);
              $("#implement_model").selectpicker('hide');
              $("#tractor_model").selectpicker('show');
              $("#tractor_model").selectpicker();
              $("#tractor_model").selectpicker("val", model);
              $(".radio-btn-container").show();
            }
            if (imple) {
              $("#radio-implement").attr("checked", "checked");
              $("#tractor_model").prop('required',false);
              $("#implement_model").prop('required',true);
              $("#tractor_model").selectpicker('hide');
              $("#implement_model").selectpicker('show');
              $("#implement_model").selectpicker();
              $("#implement_model").selectpicker("val", imple);
              $(".radio-btn-container").show();
            }
          }
          
          var contact = {
            person: [
              {
                id: "farmer",
                name: "farmer",
                attri: ", looking for",
                data: [
                  { name: "test drive" },
                  { name: "call back" },
                  { name: "service request" },
                  { name: "spare part request" },
                  { name: "quote" }
                ]
              },
              {
                id: "organization",
                name: "organization",
                attri: ", connecting for",
                data: [
                  { name: "bulk buying" },
                  { name: "call back" },
                  { name: "service request" },
                  { name: "investment" }
                ]
              },
              {
                id: "business-person",
                name: "business person",
                attri: ", wish to become",
                data: [
                  { name: "dealer" },
                  { name: "distributor" },
                  { name: "vendor" }
                ]
              },
              {
                id: "media-person",
                name: "media person",
                attri: ", connecting for",
                data: [
                  { name: "information" },
                  { name: "call back" },
                  { name: "interview" }
                ]
              },
              {
                id: "job-seeker",
                name: "job seeker",
                attri: ", looking for",
                data: [
                  { name: "information" },
                  { name: "call back" },
                  { name: "interview" }
                ]
              }
            ]
          };

          $("#person").on("change", function() {
            var person = $(this).val();
            var type = $(".list-inline").find('li.active').find('a').text();
            $(".test-drive-bx").removeClass('xyz');
            $('.form-btn-bx').show();
            if(person == 'farmer'){
              $('.test-drive-bx .about-bx h6').text('Book A Test Drive');
              $('.test-drive-bx .para').text('It’s good to have the test ride before buying your tractor! Select the date and time convenient for you.');
              $('#company-name').hide();
              $("#company-name").removeAttr("required");
              $(".radio-btn-container").hide();
            } else {
              $("#company-name").show();
              $("#company-name").attr("required", "required");
            }
            if(person == 'media-person' || person == 'job-seeker'){
              $(".test-drive-bx").hide();
              $(".test-drive-bx").addClass('xyz');
              $('#radio-tractor').removeAttr('checked');
              $('#radio-implement').removeAttr('checked');
              $("#tractor_model").removeAttr("required");
              $("#implement_model").removeAttr("required");
              $("#spare-part").removeAttr("required");
              $(".tell-us-box.test-drive-bx").removeClass("act");
              $(".step.st2").addClass("hidden");
              $(".step.st2").removeClass("act2");
              if(type == 'Information'){
                $("#message1").attr("required", true);
              } else {
                $("#message2").attr("required", true);
              }
            } else {
              //$(".test-drive-bx").show();
              $(".tell-us-box.test-drive-bx").addClass("act");
              $(".step.st2").addClass("act2");
              $(".step.st2").removeClass("hidden");
            }
            if (person == "business-person") {
              $(".test-drive-bx .about-bx h6").text("Join As A Dealer");
              $('.test-drive-bx .para').text('Join hands with India’s successful tractor manufacturer today! Select the tractor model to take it ahead.');
              $("#datetimepicker2").hide();
              $("#time-div").hide();
              $(".radio-btn-container").show();
            } else if(person == 'organization'){
              $('.test-drive-bx .about-bx h6').text('Make Bulk Purchase');
              $('.test-drive-bx .para').text('When quality comes at a profitable price, it’s a good deal! Select the tractor model, date and time for us to connect.');
              $(".radio-btn-container").show();
            } else {
              $('.test-drive-bx .about-bx h6').text('Book A Test Drive');
              $('.test-drive-bx .para').text('It’s good to have the test ride before buying your tractor! Select the date and time convenient for you.');
              $("#datetimepicker2").show();
              $("#time-div").show();
            }
            for (var i = 0; i < contact.person.length; i++) {
              if (contact.person[i].id == $(this).val()) {
                $(".for").html(contact.person[i].attri);
                $("#request").html('');
                $.each(contact.person[i].data, function(index, value) {
                  $("#request").append('<option value="' + value.name + '">' + value.name + "</option>");
                });
                $(".selectpicker").selectpicker("refresh");
              }
            }
          });
          
          $("#request").on("change", function() {
            var request = $(this).val();
            $("#spare-part").hide();
            $('.form-btn-bx').show();
            $(".test-drive-bx").removeClass('xyz');
            switch (request) {
              case "call back":
              case "investment":
              case "interview":
                $(".test-drive-bx").hide();
                $(".test-drive-bx").addClass('xyz');
                $("#tractor_model").removeAttr("required");
                $("#spare-part").removeAttr("required");
                $(".tell-us-box.test-drive-bx").removeClass('act');
                $(".step.st2").addClass('hidden');
                $(".step.st2").removeClass('act2');
                $(".radio-btn-container").hide();
                $("#implement_model").selectpicker("hide");
                break;
              case "spare part":
                $('.test-drive-bx .about-bx h6').text('Request For A Spare Part');
                $('.test-drive-bx .para').text('Genuine spare parts make your tractor work smoothly. Select the tractor, spare part, date and time for us to connect.');
                $("#spare-part").show();
                $("#spare-part").attr("required", "required");
                //$(".test-drive-bx").show();
                $(".tell-us-box.test-drive-bx").addClass("act");
                $(".step.st2").removeClass("hidden");
                $(".step.st2").addClass("act2");
                $(".radio-btn-container").show();
                break;
              case "service request":
                 $('.test-drive-bx .about-bx h6').text('Request A Service');
                 $('.test-drive-bx .para').text('Count on us when it comes to your service requirements too. Select the date and time for assisting you.');
                //  $(".test-drive-bx").show();
                 $(".tell-us-box.test-drive-bx").addClass("act");
                 $(".step.st2").removeClass("hidden");
                 $(".step.st2").addClass("act2");
                 $("#spare-part").removeAttr("required");
                 $(".radio-btn-container").show();
                break;
              case "quote":
                $('.test-drive-bx .about-bx h6').text('Get A Quote');
                $('.test-drive-bx .para').text('Our tractors are smartly designed for every farmer. Select tractor model, date and time to get the quote.');
                $(".radio-btn-container").show();
                break;
              case "bulk buying":
                $('.test-drive-bx .about-bx h6').text('Make Bulk Purchase');
                $('.test-drive-bx .para').text('When quality comes at a profitable price, it’s a good deal! Select the tractor model, date and time for us to connect.');
                // $(".test-drive-bx").show();
                $(".tell-us-box.test-drive-bx").addClass("act");
                $(".step.st2").removeClass("hidden");
                $(".step.st2").addClass("act2");
                $(".radio-btn-container").show();
                break;
              case "dealer":
                $('.test-drive-bx .about-bx h6').text('Join As A Dealer');
                $('.test-drive-bx .para').text('Join hands with India’s successful tractor manufacturer today! Select the tractor model to take it ahead.');
                $(".radio-btn-container").show();
                break;
              case "distributor":
                $('.test-drive-bx .about-bx h6').text('Associate As A Distributor');
                $('.test-drive-bx .para').text('Partner with our vision and grow your business too. Select the tractor model to kickstart it today!');
                $(".radio-btn-container").show();
                break;
              case "vendor":
                $('.test-drive-bx .about-bx h6').text('Be Our Vendor');
                $('.test-drive-bx .para').text('Collaborate to be a trustworthy point of sale for Swaraj tractors. Select the tractor model to come onboard.');
                $(".radio-btn-container").show();
                break;
              default:
                $('.test-drive-bx .about-bx h6').text('Book A Test Drive');
                $('.test-drive-bx .para').text('It’s good to have the test ride before buying your tractor! Select the date and time convenient for you.');
                // $(".test-drive-bx").show();
                $(".tell-us-box.test-drive-bx").addClass("act");
                $(".step.st2").removeClass("hidden");
                $(".step.st2").addClass("act2");
                $("#spare-part").hide();
                $("#spare-part").removeAttr("required");
                $(".radio-btn-container").hide();
                $("#implement_model").selectpicker("hide");
                $("#tractor_model").selectpicker("show");
                break;
            }

          });

          $(document).off('click', '.tell-us-box .next-btn a');
          $(document).on("click", ".tell-us-box .next-btn a", function() {
            if ($(this).parents(".tell-us-box").next().hasClass('xyz')) {
              $(this).parents(".tell-us-box").next().next().show(); 
            } else {
              $(this).parents(".tell-us-box").next().show(); 
            }
            $(this).hide(); 
            $(".tell-us-box.step-bx .container .step").is(":hidden") && ($(".tell-us-box.step-bx .container .step.hidden").next().addClass("hidden")), $(".tell-us-box.step-bx .container .step:first-child").addClass("hidden")
          });


          $("#conSubmit").unbind().on("click", function(e) {
            if($('#radio-tractor').is(':checked')) {  
              $("#implement_model").prop('required',false);
              $("#tractor_model").prop('required',true);
            } else if($('#radio-implement').is(':checked')) { 
              $("#tractor_model").prop('required',false);
              $("#implement_model").prop('required',true);
            }
            if ($("#contactForm")[0].checkValidity()) {
              e.preventDefault();
              var person_val = $('#person').val();
              var request_val = $('#request').val();
              var type = $(".list-inline").find('li.active').find('a').text();
              $("#conSubmit").attr("disabled", "disabled");
              var data = $("#contactForm").serialize() + "&person=" + person_val + "&request=" + request_val + "&type=" + type;
              $.ajax({
                type: "POST",
                url: drupalSettings.path.baseUrl + "contact-submit",
                data: data,
                success: function(response) {
                  if (response['msg'] == "success"){
                    thankYou();
                  }
                }
              });
            }
          });

          if ($(".search-product .no-result").length > 0 && $(".search-article .no-result").length > 0) {
            $(".search-article").hide();
          }
          
          var select3 = $(".located-bx #city");
          select3.attr("disabled", false);
          var options3 = select3.find("option");
          $(".located-bx #state").on("change", function() {
            var selectedState = $(this).val();
            if (selectedState == "") {
              $(".located-bx #city").html(
                options3.filter('[rel="empty"]')
              );
            } else {
              $(".located-bx #city").html(
                options3.filter('[rel="' + selectedState + '"]')
              );
            }
            $(".selectpicker").selectpicker("refresh");
          });

          var mapOptions = {
            center: new google.maps.LatLng(20.5937, 78.9629),
            zoom: 5,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            streetViewControl: false,
            styles: styles.silver
          };

          var map = new google.maps.Map(
            document.getElementById("map"),
            mapOptions
          );

          var select2 = $('#city');
          var options = select2.find('option');
          $('#state').on('change', function() {
            var str = $(this.options[this.selectedIndex]).attr('data-value');
            var latNlong = new Array();
            latNlong = str.split(",");
            var latSt = $.trim(latNlong[0]);
            var longSt = $.trim(latNlong[1]);
            var point = new google.maps.LatLng(latSt, longSt);
            map.setCenter(point);
            map.setZoom(7);

            select2.attr("disabled", false);
            var selectedState = $(this).val();
            if (selectedState == '') {
                $('#city').html(options.filter('[rel="empty"]'));
            } else {
                $('#city').html(options.filter('[rel="' + selectedState + '"]'));
            }
            $(".selectpicker").selectpicker("refresh");
          });
          
           $('#city').on('change', function() {
              var strCity = $(this.options[this.selectedIndex]).attr('data-value');
              var city = $(this.options[this.selectedIndex]).val().toLowerCase();
              $("#city-cord").val(strCity);
              var latNlongCity = new Array();
              latNlongCity = strCity.split(",");

              var latStCity = $.trim(latNlongCity[0]);
              var longStCity = $.trim(latNlongCity[1]);
              $(".view-dealer").attr("href", "near-me?city=" + city + "&co=" + latStCity + "," + longStCity);
              var pointCity = new google.maps.LatLng(
                latStCity,
                longStCity
              );
              map.setCenter(pointCity);
              map.setZoom(11);
           });


          $("#datepicker").on('change', function(){
            refreshArticles();
          });

          function refreshArticles() {

            var date = $("#datepicker").val();
            var date1 = "";
            var date2 = "";

            if (date != "") {
              date = date.replace("-", "/");
              var dt = new Date("01/" + date);
              var month = dt.getMonth(),
                year = dt.getFullYear();
              var LastDay = new Date(year, month + 1, 0);
              date1 = dt.yyyymmdd();
              date2 = LastDay.yyyymmdd();
            }
            var biz = $("#article-business").val();

            fetchArticles(date1, date2);
          }

          function fetchArticles(startdate, enddate) {
            $("input[name='created']").val(startdate);
            $("input[name='created_1']").val(enddate);
            $("#views-exposed-form-news-news .form-submit").addClass("edit-submit-news");
            $(".edit-submit-news").trigger("click");
          }

          function getUrlParameter(sParam) {
            var sPageURL = window.location.search.substring(1),
              sURLVariables = sPageURL.split("&"),
              sParameterName,
              i;
            for (i = 0; i < sURLVariables.length; i++) {
              sParameterName = sURLVariables[i].split("=");
              if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined
                  ? true
                  : decodeURIComponent(sParameterName[1]);
              }
            }
          }

          var date = new Date();
          var today = new Date(
            date.getFullYear(),
            date.getMonth(),
            date.getDate()
          );

          $("#datepicker").datepicker({
            format: "M yyyy",
            viewMode: "months",
            minViewMode: "months",
            autoclose: true
          });
          
          // $("#datepicker").datepicker("setDate", today);

          function thankYou(){
            $("body").addClass("ohidden");
            $("#dialog").fadeIn();

            var popMargTop = $("#dialog").height() / 2;
            var popMargLeft = $("#dialog").width() / 2;

            $("#dialog").css({
              "margin-top": -popMargTop,
              "margin-left": -popMargLeft
            });

            $("body").append('<div id="fade"></div>'); //Add the fade layer to bottom of the body tag.
            $("#fade")
              .css({
                filter: "alpha(opacity=80)"
              })
              .fadeIn();
          }
        });
        Date.prototype.yyyymmdd = function() {
          var mm = this.getMonth() + 1; // getMonth() is zero-based
          var dd = this.getDate();

          return [
            this.getFullYear(),
            (mm > 9 ? "" : "0") + mm,
            (dd > 9 ? "" : "0") + dd
          ].join("-");
        };
        $(document).ajaxComplete(function(event, request, settings) {
          if($('.news-contentTab').length) {
            var tarikh = $("input[name='created']").val().split('-');
            var mydate = new Date(tarikh[0], tarikh[1] - 1);
            var jsdate = mydate.toString().split(" ");
            $("#datepicker").val(jsdate[1] + " " + jsdate[3]);
          }
        });
      $(".selectpicker").selectpicker('refresh');
      }
      }
    
  // Drupal.behaviors.viewsScrollOff = {
  //   attach: function() {
  //     /* Views Ajax Content Load Autoscroll Feature Disabled */
  //     Drupal.AjaxCommands.prototype.viewsScrollTop = null;
  //   }
  // };
  })(jQuery, Drupal, drupalSettings);
