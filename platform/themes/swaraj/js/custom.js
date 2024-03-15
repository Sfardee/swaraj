/**
 * @file
 * Customization on contributor modules.
 */
(function ($, Drupal, drupalSettings) {
  'use strict';
  Drupal.behaviors.custom = {
    attach: function (context, settings) {
      if (!Drupal.behaviors.custom.click_set) {
        $(document).ready(function (e) {
          // instantiate lazy load  
          var observer = lozad(); // lazy loads elements with default selector as '.lozad'
          observer.observe();

          var date = new Date();
          var tomorrow = (date.getDate() + 1) + '-' + (date.getMonth() + 1) + '-' + date.getFullYear();
          $("#datetimepicker2").datepicker('setStartDate', tomorrow);

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

          $('.galleryModal .close').click(function (e) {
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

          $('#views-exposed-form-search-article').hide();

          $('.search-btn-js').on('click', function () {
            var key = $('.search-text-box').val();
            window.location.href = drupalSettings.path.baseUrl + 'search?search=' + key;
          });
          $(".search-page-box").keypress(function (event) {
            var keycode = event.keyCode ? event.keyCode : event.which;
            if (keycode == "13") {
              var key = $(".search-page-box").val();
              window.location.href = drupalSettings.path.baseUrl + 'search?search=' + key;
            }
          });

          if ($(".search-text-box").length) {
            if ($(".search-product").length == 0 && $(".search-article").length == 0) {
              $(".search-result-product").removeClass('hidden');
            }
          }

          if ($(".news-contentTab").length) {
            $.ajax({
              type: "POST",
              url: drupalSettings.path.baseUrl + "check-date",
              success: function (response) {
                if (response == "show") {
                  $(".group-datePicker").removeClass("hidden");
                }
              }
            });
          }

          $('#home-product li').on('click', function () {
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

          if ($("#contactForm").length > 0) {
            $('#person').val('individual');
            $(".selectpicker").selectpicker('refresh');
            $("#request").selectpicker();
            $("#request").selectpicker("val", "tractor demo");
            $("#implement_model").selectpicker("hide");
            $("#implement_model").selectpicker();
            $("input[type='radio']").change(function () {
              var request = $("#request").val();
              if ($(this).val() == "tractor") {
                if (request == 'purchase/buy') {
                  $("#tractor_model").prop('required', true);
                  $("#implement_model").removeAttr("required");
                  $('#tractor_model').selectpicker({ title: 'Tractor Model*' });
                  $('#implement_model').selectpicker({ title: 'Farm Machinery Model' });
                  $(".selectpicker").selectpicker("refresh");

                } else {
                  $("#tractor_model").removeAttr("required");
                  $('#tractor_model').selectpicker({ title: 'Tractor Model' });
                  $(".selectpicker").selectpicker("refresh");
                }
                $("#tractor_model").selectpicker("show");
                $("#implement_model").selectpicker("hide");
              } else {
                if (request == 'purchase/buy') {
                  $("#tractor_model").removeAttr("required");
                  $("#implement_model").prop('required', true);
                  $('#tractor_model').selectpicker({ title: 'Tractor Model' });
                  $('#implement_model').selectpicker({ title: 'Farm Machinery Model*' });
                  $(".selectpicker").selectpicker("refresh");
                } else {
                  $("#implement_model").removeAttr("required");
                  $('#implement_model').selectpicker({ title: 'Farm Machinery Model' });
                  $(".selectpicker").selectpicker("refresh");
                }
                $("#implement_model").selectpicker("show");
                $("#tractor_model").selectpicker("hide");
              }
            });

            $("#spare-part").hide();
            $(".radio-btn-container").hide();
            var model = getUrlParameter('model');
            var imple = getUrlParameter("imple");
            var request = getUrlParameter("request");         
            if (request == "call") {
              $("#request").selectpicker();
              // $("#request").selectpicker("val", "call back");
              $("#request").selectpicker("val", "tractor demo");
              // $(".test-drive-bx").hide();
              // $(".test-drive-bx").addClass('xyz');
            } else if (request == "drive") {
              $("#request").selectpicker();
              $("#request").selectpicker("val", "tractor demo");
              $("#tractor_model").prop('required', true);
              $('#tractor_model').selectpicker({ title: 'Tractor Model*' });
              $(".selectpicker").selectpicker("refresh");
            } else if (request == "service") {
              $("#tractor_model").removeAttr("required");
              $('#tractor_model').selectpicker({ title: 'Tractor Model' });
              $(".selectpicker").selectpicker("refresh");
              $("#request").selectpicker();
              $("#request").selectpicker("val", "service/parts feedback");
              $("#spare-part").hide();
              $(".radio-btn-container").show();
              $('.test-drive-bx .about-bx h6').text('Request A Service');
              $('.test-drive-bx .para').text('Count on us when it comes to your service requirements too. Select the date and time for assisting you.');
            } else if (request == "purchase/buy") {
              $("#request").selectpicker();
              $("#request").selectpicker("val", "purchase/buy");
              if ($(this).val() == "tractor") {
                $("#tractor_model").prop('required', true);
                $("#implement_model").removeAttr("required");
                $('#tractor_model').selectpicker({ title: 'Tractor Model*' });
                $('#implement_model').selectpicker({ title: 'Farm Machinery Model' });
                $(".selectpicker").selectpicker("refresh");
              } else {
                $("#implement_model").prop('required', true);
                $("#tractor_model").removeAttr("required");
                $('#tractor_model').selectpicker({ title: 'Tractor Model' });
                $('#implement_model').selectpicker({ title: 'Farm Machinery Model*' });
                $(".selectpicker").selectpicker("refresh");
              }
              $('.test-drive-bx .about-bx h6').text('Get A Quote');
              $('.test-drive-bx .para').text('Our tractors are smartly designed for every farmer. Select tractor model, date and time to get the quote.');
              $("#datetimepicker2").hide();
              $("#time-div").hide();
            } else if (request == "price") {
              $("#tractor_model").removeAttr("required");
              $('#tractor_model').selectpicker({ title: 'Tractor Model' });
              $(".selectpicker").selectpicker("refresh");
              $("#request").selectpicker();
              $("#request").selectpicker("val", "price");
              $('.test-drive-bx .about-bx h6').text('Get A Quote');
              $('.test-drive-bx .para').text('Our tractors are smartly designed for every farmer. Select tractor model, date and time to get the quote.');
              $(".test-drive-bx").hide();
              $("#datetimepicker2").hide();
              $("#time-div").hide();
            } else if (request == "others") {
              $("#tractor_model").removeAttr("required");
              $('#tractor_model').selectpicker({ title: 'Tractor Model' });
              $(".selectpicker").selectpicker("refresh");
              $("#request").selectpicker();
              $("#request").selectpicker("val", "others");
              $(".test-drive-bx").hide();
              $("#datetimepicker2").hide();
              $("#time-div").hide();
            } else if (request == "become a dealer") {
              $("#tractor_model").removeAttr("required");
              $('#tractor_model').selectpicker({ title: 'Tractor Model' });
              $(".selectpicker").selectpicker("refresh");
              $("#request").selectpicker();
              $("#request").selectpicker("val", "become a dealer");
              $(".test-drive-bx").hide();
              $("#datetimepicker2").hide();
              $("#time-div").hide();
            }
            if (model) {
              $("#radio-tractor").attr("checked", "checked");
              $("#implement_model").selectpicker('hide');
              $("#tractor_model").selectpicker('show');
              $("#tractor_model").selectpicker();
              $('#tractor_model option').each(function () {
                if ($(this).text() == model) {
                  $(this).prop("selected", true);
                }
              });
              $(".selectpicker").selectpicker("refresh");
              $(".radio-btn-container").show();
            }
            if (imple) {
              $("#radio-implement").attr("checked", "checked");
              $("#tractor_model").selectpicker('hide');
              $("#implement_model").selectpicker('show');
              $("#implement_model").selectpicker();
              $('#implement_model option').each(function () {
                if ($(this).text() == imple) {
                  $(this).prop("selected", true);
                }
              });
              $(".selectpicker").selectpicker("refresh");
              $(".radio-btn-container").show();
            }

            var contact = {
              person: [{
                id: "individual",
                name: "individual",
                attri: ", looking for",
                data: [{
                  name: "tractor demo"
                },
                {
                  name: "service/parts feedback"
                },
                {
                  name: "spare part request"
                },
                {
                  name: "purchase/buy"
                },
                {
                  name: "price"
                },
                {
                  name: "become a dealer"
                },
                {
                  name: "others"
                }
                ]
              },
              {
                id: "business organization",
                name: "business organization",
                attri: ", connecting for",
                data: [{
                  name: "bulk buying"
                },
                {
                  name: "service/parts feedback"
                },
                {
                  name: "export"
                },
                {
                  name: "others"
                }
                ]
              },
              ]
            };
  
            $("#person").on("change", function () {
              var person = $(this).val();
              var type = $(".list-inline").find('li.active').find('a').text();
              $(".test-drive-bx").show();
              $(".test-drive-bx").removeClass('xyz');
              $('.form-btn-bx').show();
              $('#name').attr('placeholder', 'Name*');
              $('.st3 span').html('3');
              $('.st4 span').html('4');
              if (person == 'individual') {
                $('.test-drive-bx .about-bx h6').text('Book A Test Drive');
                $('.test-drive-bx .para').text('It’s good to have the test ride before buying your tractor! Select the date and time convenient for you.');
                $(".radio-btn-container").hide();
                $("#tractor_model").prop('required', true);
                $("#implement_model").removeAttr("required");
                $('#tractor_model').selectpicker({ title: 'Tractor Model*' });
                $('#implement_model').selectpicker({ title: 'Farm Machinery Model' });
                $(".selectpicker").selectpicker("refresh");
              } else {
                $("#tractor_model").removeAttr("required");
                $("#implement_model").removeAttr("required");
                $('#tractor_model').selectpicker({ title: 'Tractor Model' });
                $('#implement_model').selectpicker({ title: 'Farm Machinery Model' });
                $(".selectpicker").selectpicker("refresh");
              }
              if (person == 'media-person' || person == 'job-seeker') {
                $(".test-drive-bx").hide();
                $(".test-drive-bx").addClass('xyz');
                $('#radio-tractor').removeAttr('checked');
                $('#radio-implement').removeAttr('checked');
                $(".tell-us-box.test-drive-bx").removeClass("act");
                $(".step.st2").addClass("hidden");
                $(".step.st2").removeClass("act2");
                $('.st3 span').html('2');
                $('.st4 span').html('3');
              } else {
                //$(".test-drive-bx").show();
                $(".tell-us-box.test-drive-bx").addClass("act");
                $(".step.st2").addClass("act2");
                $(".step.st2").removeClass("hidden");
              }
              if (person == "business-person") {
                $(".test-drive-bx .about-bx h6").text("Join As A Dealer");
                $('.test-drive-bx .para').text('Join hands with India’s successful tractor manufacturer today!');
                $("#datetimepicker2").hide();
                $("#time-div").hide();
                $(".radio-btn-container").show();
              } else if (person == 'business organization') {
                $('#name').attr('placeholder', 'Name / Company Name*');
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
                  $.each(contact.person[i].data, function (index, value) {
                    $("#request").append('<option value="' + value.name + '">' + value.name + "</option>");
                  });
                  $(".selectpicker").selectpicker("refresh");
                }
              }
            });
  
            $("#request").on("change", function () {
              var request = $(this).val();
              $('#radio-tractor').click();
              $("#tractor_model").removeAttr("required");
              $("#implement_model").removeAttr("required");
              $('#tractor_model').selectpicker({ title: 'Tractor Model' });
              $('#implement_model').selectpicker({ title: 'Farm Machinery Model' });
              $(".selectpicker").selectpicker("refresh");
              $("#spare-part").hide();
              $('.form-btn-bx').show();
              $(".tell-us-box.test-drive-bx").addClass("act");
              $(".test-drive-bx").show();
              $(".test-drive-bx").removeClass('xyz');
              $(".radio-btn-container").show();
              $("#datetimepicker2").show();
              $("#time-div").show();
              $("#radio-tractor").attr("checked", "checked");
              $('.st3 span').html('3');
              $('.st4 span').html('4');
              switch (request) {
                case "call back":
                case "export":
                case "interview":
                  $(".test-drive-bx").hide();
                  $(".test-drive-bx").addClass('xyz');
                  $('#radio-tractor').removeAttr('checked');
                  $('#radio-implement').removeAttr('checked');
                  $(".tell-us-box.test-drive-bx").removeClass('act');
                  $(".step.st2").addClass('hidden');
                  $(".step.st2").removeClass('act2');
                  $('.st3 span').html('2');
                  $('.st4 span').html('3');
                  $(".radio-btn-container").hide();
                  $("#implement_model").selectpicker("hide");
                  break;
                case "spare part":
                  $('.test-drive-bx .about-bx h6').text('Request For A Spare Part');
                  $('.test-drive-bx .para').text('Genuine spare parts make your tractor work smoothly. Select the tractor, spare part, date and time for us to connect.');
                  $("#spare-part").show();
                  //$(".test-drive-bx").show();
                  $(".tell-us-box.test-drive-bx").addClass("act");
                  $(".step.st2").removeClass("hidden");
                  $(".step.st2").addClass("act2");
                  $(".radio-btn-container").show();
                  break;
                case "service/parts feedback":
                  $('.test-drive-bx .about-bx h6').text('Request A Service');
                  $('.test-drive-bx .para').text('Count on us when it comes to your service requirements too. Select the date and time for assisting you.');
                  //  $(".test-drive-bx").show();
                  $(".tell-us-box.test-drive-bx").addClass("act");
                  $(".step.st2").removeClass("hidden");
                  $(".step.st2").addClass("act2");
                  $(".radio-btn-container").show();
                  break;
                case "purchase/buy":
                  $('.test-drive-bx .about-bx h6').text('Get A Quote');
                  $('.test-drive-bx .para').text('Our tractors are smartly designed for every farmer. Select tractor model, date and time to get the quote.');
                  $(".radio-btn-container").show();
                  $("#datetimepicker2").hide();
                  $("#time-div").hide();
                  $("#radio-tractor").attr('checked', 'checked');
                  $('#radio-implement').removeAttr('checked');
                  $("#tractor_model").prop('required', true);
                  $("#implement_model").selectpicker('hide');
                  $("#tractor_model").selectpicker('show');
                  $('#tractor_model').selectpicker({ title: 'Tractor Model*' });
                  $(".selectpicker").selectpicker("refresh");
                  break;
                case "price":
                  $('.test-drive-bx .about-bx h6').text('Get A Quote');
                  $('.test-drive-bx .para').text('Our tractors are smartly designed for every farmer. Select tractor model, date and time to get the quote.');
                  $("#datetimepicker2").hide();
                  $("#time-div").hide();
                  break;
                case "others":
                  $("#datetimepicker2").hide();
                  $("#time-div").hide();
                  $(".test-drive-bx").hide();
                  $(".test-drive-bx").addClass('xyz');
                  $('#radio-tractor').removeAttr('checked');
                  $('#radio-implement').removeAttr('checked');
                  $(".tell-us-box.test-drive-bx").removeClass('act');
                  $(".step.st2").addClass('hidden');
                  $(".step.st2").removeClass('act2');
                  $('.st3 span').html('2');
                  $('.st4 span').html('3');
                  $(".radio-btn-container").hide();
                  $("#implement_model").selectpicker("hide");
                  break;
                case "become a dealer":
                  $('.test-drive-bx .about-bx h6').text('Join As A Dealer');
                  $('.test-drive-bx .para').text('Join hands with India’s successful tractor manufacturer today!');
                  $(".tell-us-box.test-drive-bx").addClass("act");
                  $(".step.st2").removeClass("hidden");
                  $(".tell-us-box.test-drive-bx").addClass("act");
                  $(".step.st2").removeClass("hidden");
                  $(".step.st2").addClass("act2");
                  $(".radio-btn-container").show();
                  $("#datetimepicker2").hide();
                  $("#time-div").hide();
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
                  $('.test-drive-bx .para').text('Join hands with India’s successful tractor manufacturer today!');
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
                  $(".radio-btn-container").hide();
                  $("#implement_model").selectpicker("hide");
                  $("#tractor_model").selectpicker("show");
                  // $("#tractor_model").prop('required', true);
                  // $('#tractor_model').selectpicker({title: 'Tractor Model*'});
                  // $(".selectpicker").selectpicker("refresh");
                  break;
              }
  
            });

            var select3 = $(".located-bx #district");
            var options3 = select3.find("option");
            var select4 = $(".located-bx #tehsil");
            var options4 = select4.find("option");
            var select5 = $(".located-bx #city");
            var options5 = select5.find("option");
            $(".located-bx #state").on("change", function () {
              var selectedState = $(this).val();
              if (selectedState == "") {
                options3.remove();
                select3.attr("disabled", true);
                options4.remove();
                select4.attr("disabled", true);
                options5.remove();
                select5.attr("disabled", true);
              } else {
                $.ajax({
                  type: "GET",
                  url: drupalSettings.path.baseUrl + "get-district",
                  dataType: 'json',
                  data: "state=" + encodeURIComponent(selectedState),
                  success: function (response) {
                    select3.attr("disabled", false);
                    select3.find("option").remove();
                    $.each(response, function (key, value) {
                      select3.append('<option value="' + value + '">' + value + '</option>');
                    });
                    select4.find("option").remove();
                    select4.attr("disabled", true);
                    select5.find("option").remove();
                    select5.attr("disabled", true);
                    $(".selectpicker").selectpicker("refresh");
                  }
                });
              }
              $(".selectpicker").selectpicker("refresh");
            });
            $(".located-bx #district").on("change", function () {
              var selectedState = $(".located-bx #state").val();
              var selectedDistrict = $(this).val();
              if (selectedDistrict == "") {
                options4.remove();
                select4.attr("disabled", true);
                options5.remove();
                select5.attr("disabled", true);
              } else {
                $.ajax({
                  type: "GET",
                  url: drupalSettings.path.baseUrl + "get-tehsil",
                  dataType: 'json',
                  data: "state=" + encodeURIComponent(selectedState) + "&district=" + encodeURIComponent(selectedDistrict),
                  success: function (response) {
                    select4.attr("disabled", false);
                    select4.find("option").remove();
                    $.each(response, function (key, value) {
                      select4.append('<option value="' + value + '">' + value + '</option>');
                    });
                    select5.find("option").remove();
                    select5.attr("disabled", true);
                    $(".selectpicker").selectpicker("refresh");
                  }
                });
              }
              $(".selectpicker").selectpicker("refresh");
            });
            $(".located-bx #tehsil").on("change", function () {
              var selectedState = $(".located-bx #state").val();
              var selectedDistrict = $(".located-bx #district").val();
              var selectedTehsil = $(this).val();
              if (selectedTehsil == "") {
                options5.remove();
                select5.attr("disabled", true);
              } else {
                $.ajax({
                  type: "GET",
                  url: drupalSettings.path.baseUrl + "get-village",
                  dataType: 'json',
                  data: "state=" + encodeURIComponent(selectedState) + "&district=" + (selectedDistrict) + "&tehsil=" + (selectedTehsil),
                  success: function (response) {
                    select5.attr("disabled", false);
                    select5.find("option").remove();
                    $.each(response, function (key, value) {
                      select5.append('<option value="' + value + '">' + value + '</option>');
                    });
                    $(".selectpicker").selectpicker("refresh");
                  }
                });
              }
              $(".selectpicker").selectpicker("refresh");
            });
  
            $(document).off('click', '.tell-us-box .next-btn a');
            $(document).on("click", ".tell-us-box .next-btn a", function () {
              if ($(this).parents(".tell-us-box").next().hasClass('xyz')) {
                $(this).parents(".tell-us-box").next().next().show();
              } else {
                $(this).parents(".tell-us-box").next().show();
              }
              $(this).hide();
              $(".tell-us-box.step-bx .container .step").is(":hidden") && ($(".tell-us-box.step-bx .container .step.hidden").next().addClass("hidden")), $(".tell-us-box.step-bx .container .step:first-child").addClass("hidden")
            });
  
            $("#conSubmit").unbind().on("click", function (e) {
              var mobile = $("#mobile-number").val();
              var count = 0;
              for (var i = 0; i < mobile.length; i++) {
                var cur_count = 1;
                for (var j = i + 1; j < mobile.length; j++) {
                  if (mobile.charAt(i) != mobile.charAt(j))
                    break;
                  cur_count++;
                }
                if (cur_count > count) {
                  count = cur_count;
                }
              }
              if (count > 7) {
                $("#mobile-number")[0].setCustomValidity('Please enter valid 10 digit mobile number.');
                return;
              } else {
                $("#mobile-number")[0].setCustomValidity('');
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
                  cache: false,
                  success: function (response) {
                    if (response['msg'] == "success") {
                      thankYou();
                      $('.close-btn').click(function (e) {
                        location.reload();
                      });
                    }
                  }
                });
              }
            });
          }

          if ($("#newContactForm").length > 0) {
            if ( window.history.replaceState ) {
              window.history.replaceState( null, null, window.location.href );
            }
            $(".selectpicker").selectpicker('refresh');
         
            $("input[name^='selectService']").click(function () {
              var vl = $(this).val();
              $("div.selectService").hide();

              if(vl === 'tractor-field' || vl === 'harvesters-field'){
                $("div.selectService").show();
              }
              $('.selectService .form-group').hide();
              $('.selectService ' + "#" + vl).show();
            });
            var tractor = getUrlParameter('model');
            var implement = getUrlParameter("imple");
            var request = getUrlParameter("request");         
            if (request == "call" || request == "drive" || request == "purchase/buy" || request == "price" || request == "others" || request == "become a dealer" || request == "quote") {
              $("input[name=selectService][value='tractor-field']").prop("checked",true);
            } else if (request == "service") {
              $("input[name=selectService][value='service-field']").prop("checked",true);
              $('.selectService .form-group').hide();
            }
            if (tractor) {
              $("input[name=selectService][value='tractor-field']").prop("checked",true);
              $("#tractor_model").selectpicker();
              $('#tractor_model option').each(function () {
                if ($(this).text() == tractor) {
                  $(this).prop("selected", true);
                }
              });
              $(".selectpicker").selectpicker("refresh");
            }
            if (implement) {
              $("input[name=selectService][value='harvesters-field']").prop("checked",true);
              $('.selectService .form-group').hide();
              $('.selectService ' + "#harvesters-field").show();
              $("#harvestor_model").selectpicker();
              $('#harvestor_model option').each(function () {
                if ($(this).text() == implement) {
                  $(this).prop("selected", true);
                }
              });
              $(".selectpicker").selectpicker("refresh");
            }
            var select3 = $("#newContactForm #district");
            var options3 = select3.find("option");
            var select4 = $("#newContactForm #tehsil");
            var options4 = select4.find("option");
            $("#newContactForm #state").on("change", function () {
              var selectedState = $(this).val();
              if (selectedState == "") {
                options3.remove();
                select3.attr("disabled", true);
                options4.remove();
                select4.attr("disabled", true);
              } else {
                $.ajax({
                  type: "GET",
                  url: drupalSettings.path.baseUrl + "get-district",
                  dataType: 'json',
                  data: "state=" + encodeURIComponent(selectedState),
                  success: function (response) {
                    select3.attr("disabled", false);
                    select3.find("option").remove();
                    $.each(response, function (key, value) {
                      select3.append('<option value="' + value + '">' + value + '</option>');
                    });
                    select4.find("option").remove();
                    select4.attr("disabled", true);
                    $(".selectpicker").selectpicker("refresh");
                  }
                });
              }
              $(".selectpicker").selectpicker("refresh");
            });
            $("#newContactForm #district").on("change", function () {
              var selectedState = $("#newContactForm #state").val();
              var selectedDistrict = $(this).val();
              if (selectedDistrict == "") {
                options4.remove();
                select4.attr("disabled", true);
              } else {
                $.ajax({
                  type: "GET",
                  url: drupalSettings.path.baseUrl + "get-tehsil",
                  dataType: 'json',
                  data: "state=" + encodeURIComponent(selectedState) + "&district=" + encodeURIComponent(selectedDistrict),
                  success: function (response) {
                    select4.attr("disabled", false);
                    select4.find("option").remove();
                    $.each(response, function (key, value) {
                      select4.append('<option value="' + value + '">' + value + '</option>');
                    });
                    $(".selectpicker").selectpicker("refresh");
                  }
                });
              }
              $(".selectpicker").selectpicker("refresh");
            });
  
            $("#newContactUsSubmit").unbind().on("click", function (e) {
              var mobile = $("#mobile-number").val();
              var count = 0;
              for (var i = 0; i < mobile.length; i++) {
                var cur_count = 1;
                for (var j = i + 1; j < mobile.length; j++) {
                  if (mobile.charAt(i) != mobile.charAt(j))
                    break;
                  cur_count++;
                }
                if (cur_count > count) {
                  count = cur_count;
                }
              }
              if (count > 7) {
                $("#mobile-number")[0].setCustomValidity('Please enter valid 10 digit mobile number.');
                return;
              } else {
                $("#mobile-number")[0].setCustomValidity('');
              }
              if ($("#newContactForm")[0].checkValidity()) {
                e.preventDefault();
                $("#newContactUsSubmit").attr("disabled", "disabled");
                var form_data = $("#newContactForm").serialize();
                $.ajax({
                  type: "POST",
                  url: drupalSettings.path.baseUrl + "new-contact-submit",
                  data: form_data,
                  cache: false,
                  success: function (response) {
                    if (response['msg'] == "success") {
                      window.location = response['thank_you'];
                    } else {
                      alert('Something went wrong! Please refresh the page and try again!');
                    }
                  },
                  error: function (response) {
                    if (response['msg'] == "token error") {
                      alert('Something went wrong! Please refresh the page and try again!');
                    } else {
                      alert('Something went wrong! Please refresh the page and try again!');
                    }
                  }
                });
              }
            });
          }

          if ($('#dealerContactForm').length) {
            var select6 = $("#dealerContactForm #district");
            var options6 = select6.find("option");
            $("#dealerContactForm #state").on("change", function () {
              var selectedState = $(this).val();
              if (selectedState == "") {
                options6.remove();
                select6.attr("disabled", true);
              } else {
                $.ajax({
                  type: "GET",
                  url: drupalSettings.path.baseUrl + "get-district",
                  dataType: 'json',
                  data: "state=" + encodeURIComponent(selectedState),
                  success: function (response) {
                    select6.attr("disabled", false);
                    select6.find("option").remove();
                    $.each(response, function (key, value) {
                      select6.append('<option value="' + value + '">' + value + '</option>');
                    });
                    $(".selectpicker").selectpicker("refresh");
                  }
                });
              }
              $(".selectpicker").selectpicker("refresh");
            });

            $("#dealerContactSubmit").unbind().on("click", function (e) {
              var mobile = $("#mobile_number").val();
              var count = 0;
              for (var i = 0; i < mobile.length; i++) {
                var cur_count = 1;
                for (var j = i + 1; j < mobile.length; j++) {
                  if (mobile.charAt(i) != mobile.charAt(j))
                    break;
                  cur_count++;
                }
                if (cur_count > count) {
                  count = cur_count;
                }
              }
              if (count > 7) {
                $("#mobile_number")[0].setCustomValidity('Please enter valid 10 digit mobile number.');
                return;
              } else {
                $("#mobile_number")[0].setCustomValidity('');
              }
              if ($("#dealerContactForm")[0].checkValidity()) {
                e.preventDefault();
                $("#dealerContactSubmit").attr("disabled", "disabled");
                var form_data = $("#dealerContactForm").serialize();
                $.ajax({
                  type: "POST",
                  url: drupalSettings.path.baseUrl + "dealer-contact-submit",
                  data: form_data,
                  cache: false,
                  success: function (response) {
                    if (response['msg'] == "success") {
                      thankYou();
                      $('.close-btn').click(function (e) {
                        location.reload();
                      });
                    }
                  }
                });
              }
            });

          }

          if ($('#enquiryForm').length) {
            if ( window.history.replaceState ) {
              window.history.replaceState( null, null, window.location.href );
            }
            var select7 = $("#enquiryForm #district");
            var options7 = select7.find("option");
            var select8 = $("#enquiryForm #city_village");
            var options8 = select8.find("option");
            $("#enquiryForm #state").on("change", function () {
              var selectedState = $(this).val();
              if (selectedState == "") {
                options7.remove();
                select7.attr("disabled", true);
                options8.remove();
                select8.attr("disabled", true);
              } else {
                $.ajax({
                  type: "GET",
                  url: drupalSettings.path.baseUrl + "get-district",
                  dataType: 'json',
                  data: "state=" + encodeURIComponent(selectedState),
                  success: function (response) {
                    select7.attr("disabled", false);
                    select7.find("option").remove();
                    $.each(response, function (key, value) {
                      select7.append('<option value="' + value + '">' + value + '</option>');
                    });
                    $(".selectpicker#district").selectpicker("refresh");
                  }
                });
              }
              $(".selectpicker#district").selectpicker("refresh");
            });
            $("#enquiryForm #district").on("change", function () {
              var selectedState = $("#enquiryForm #state").val();
              var selectedDistrict = $(this).val();
              if (selectedDistrict == "") {
                options8.remove();
                select8.attr("disabled", true);
              } else {
                $.ajax({
                  type: "GET",
                  url: drupalSettings.path.baseUrl + "get-city-village",
                  dataType: 'json',
                  data: "state=" + encodeURIComponent(selectedState) + "&district=" + encodeURIComponent(selectedDistrict),
                  success: function (response) {
                    select8.attr("disabled", false);
                    select8.find("option").remove();
                    $.each(response, function (key, value) {
                      select8.append('<option value="' + value + '">' + value + '</option>');
                    });
                    $(".selectpicker#city_village").selectpicker("refresh");
                  }
                });
              }
              $(".selectpicker#city_village").selectpicker("refresh");
            });
            
            $("#enquirySubmit").unbind().on("click", function (e) {
              var mobile = $("#mobile_number").val();
              var count = 0;
              for (var i = 0; i < mobile.length; i++) {
                var cur_count = 1;
                for (var j = i + 1; j < mobile.length; j++) {
                  if (mobile.charAt(i) != mobile.charAt(j))
                    break;
                  cur_count++;
                }
                if (cur_count > count) {
                  count = cur_count;
                }
              }
              if (count > 7) {
                $("#mobile_number")[0].setCustomValidity('Please enter valid 10 digit mobile number.');
                return;
              } else {
                $("#mobile_number")[0].setCustomValidity('');
              }
              if ($("#enquiryForm")[0].checkValidity()) {
                e.preventDefault();
                $("#enquirySubmit").attr("disabled", "disabled");
                var form_data = $("#enquiryForm").serialize();
                $.ajax({
                  type: "POST",
                  url: drupalSettings.path.baseUrl + "enquiry-submit",
                  data: form_data,
                  cache: false,
                  success: function (response) {
                    if (response['msg'] == "success") {
                      window.location = drupalSettings.path.baseUrl + "thank-you"
                    } else {
                      alert('Something went wrong! Please refresh the page and try again!');
                    }
                  },
                  error: function (response) {
                    if (response['msg'] == "token error") {
                      alert('Something went wrong! Please refresh the page and try again!');
                    } else {
                      alert('Something went wrong! Please refresh the page and try again!');
                    }
                  }
                });
              }
            });
          }

          if ($(".search-product .no-result").length > 0 && $(".search-article .no-result").length > 0) {
            $(".search-article").hide();
          }

          if (($('.map-contact').length > 0) || ($('.near-me-map').length > 0)) {
            const mapObserver = lozad('.map-lozad', {
              threshold: 0.1,
              enableAutoReload: true,
              load: function(el) {
                el.src = el.getAttribute("data-src");
                el.onload = function() {
                  // load google map if user reach here
                  const url = "https://maps.googleapis.com/maps/api/js?key="+drupalSettings.google_map_api_key;
                  const script = document.createElement('script');
                  script.setAttribute('src', url);
                  script.setAttribute('async', '');
                  document.head.appendChild(script);

                  // load map with nearest dealers
                  var gmarkers = new Array();
                  var baseUrl = window.location.origin;
                  var markers = new Array();
                  var styles = {
                    default: null,
                  
                    silver: [
                      {
                        elementType: 'geometry',
                        stylers: [{ color: '#f5f5f5' }]
                      },
                      {
                        elementType: 'labels.icon',
                        stylers: [{ visibility: 'off' }]
                      },
                      {
                        elementType: 'labels.text.fill',
                        stylers: [{ color: '#616161' }]
                      },
                      {
                        elementType: 'labels.text.stroke',
                        stylers: [{ color: '#f5f5f5' }]
                      },
                      {
                        featureType: 'administrative.land_parcel',
                        elementType: 'labels.text.fill',
                        stylers: [{ color: '#bdbdbd' }]
                      },
                      {
                        featureType: 'poi',
                        elementType: 'geometry',
                        stylers: [{ color: '#eeeeee' }]
                      },
                      {
                        featureType: 'poi',
                        elementType: 'labels.text.fill',
                        stylers: [{ color: '#757575' }]
                      },
                      {
                        featureType: 'poi.park',
                        elementType: 'geometry',
                        stylers: [{ color: '#e5e5e5' }]
                      },
                      {
                        featureType: 'poi.park',
                        elementType: 'labels.text.fill',
                        stylers: [{ color: '#9e9e9e' }]
                      },
                      {
                        featureType: 'road',
                        elementType: 'geometry',
                        stylers: [{ color: '#ffffff' }]
                      },
                      {
                        featureType: 'road.arterial',
                        elementType: 'labels.text.fill',
                        stylers: [{ color: '#757575' }]
                      },
                      {
                        featureType: 'road.highway',
                        elementType: 'geometry',
                        stylers: [{ color: '#dadada' }]
                      },
                      {
                        featureType: 'road.highway',
                        elementType: 'labels.text.fill',
                        stylers: [{ color: '#616161' }]
                      },
                      {
                        featureType: 'road.local',
                        elementType: 'labels.text.fill',
                        stylers: [{ color: '#9e9e9e' }]
                      },
                      {
                        featureType: 'transit.line',
                        elementType: 'geometry',
                        stylers: [{ color: '#e5e5e5' }]
                      },
                      {
                        featureType: 'transit.station',
                        elementType: 'geometry',
                        stylers: [{ color: '#eeeeee' }]
                      },
                      {
                        featureType: 'water',
                        elementType: 'geometry',
                        stylers: [{ color: '#c9c9c9' }]
                      },
                      {
                        featureType: 'water',
                        elementType: 'labels.text.fill',
                        stylers: [{ color: '#9e9e9e' }]
                      }
                    ],
                  
                    night: [
                      { elementType: 'geometry', stylers: [{ color: '#242f3e' }] },
                      { elementType: 'labels.text.stroke', stylers: [{ color: '#242f3e' }] },
                      { elementType: 'labels.text.fill', stylers: [{ color: '#746855' }] },
                      {
                        featureType: 'administrative.locality',
                        elementType: 'labels.text.fill',
                        stylers: [{ color: '#d59563' }]
                      },
                      {
                        featureType: 'poi',
                        elementType: 'labels.text.fill',
                        stylers: [{ color: '#d59563' }]
                      },
                      {
                        featureType: 'poi.park',
                        elementType: 'geometry',
                        stylers: [{ color: '#263c3f' }]
                      },
                      {
                        featureType: 'poi.park',
                        elementType: 'labels.text.fill',
                        stylers: [{ color: '#6b9a76' }]
                      },
                      {
                        featureType: 'road',
                        elementType: 'geometry',
                        stylers: [{ color: '#38414e' }]
                      },
                      {
                        featureType: 'road',
                        elementType: 'geometry.stroke',
                        stylers: [{ color: '#212a37' }]
                      },
                      {
                        featureType: 'road',
                        elementType: 'labels.text.fill',
                        stylers: [{ color: '#9ca5b3' }]
                      },
                      {
                        featureType: 'road.highway',
                        elementType: 'geometry',
                        stylers: [{ color: '#746855' }]
                      },
                      {
                        featureType: 'road.highway',
                        elementType: 'geometry.stroke',
                        stylers: [{ color: '#1f2835' }]
                      },
                      {
                        featureType: 'road.highway',
                        elementType: 'labels.text.fill',
                        stylers: [{ color: '#f3d19c' }]
                      },
                      {
                        featureType: 'transit',
                        elementType: 'geometry',
                        stylers: [{ color: '#2f3948' }]
                      },
                      {
                        featureType: 'transit.station',
                        elementType: 'labels.text.fill',
                        stylers: [{ color: '#d59563' }]
                      },
                      {
                        featureType: 'water',
                        elementType: 'geometry',
                        stylers: [{ color: '#17263c' }]
                      },
                      {
                        featureType: 'water',
                        elementType: 'labels.text.fill',
                        stylers: [{ color: '#515c6d' }]
                      },
                      {
                        featureType: 'water',
                        elementType: 'labels.text.stroke',
                        stylers: [{ color: '#17263c' }]
                      }
                    ],
                  
                    retro: [
                      { elementType: 'geometry', stylers: [{ color: '#ebe3cd' }] },
                      { elementType: 'labels.text.fill', stylers: [{ color: '#523735' }] },
                      { elementType: 'labels.text.stroke', stylers: [{ color: '#f5f1e6' }] },
                      {
                        featureType: 'administrative',
                        elementType: 'geometry.stroke',
                        stylers: [{ color: '#c9b2a6' }]
                      },
                      {
                        featureType: 'administrative.land_parcel',
                        elementType: 'geometry.stroke',
                        stylers: [{ color: '#dcd2be' }]
                      },
                      {
                        featureType: 'administrative.land_parcel',
                        elementType: 'labels.text.fill',
                        stylers: [{ color: '#ae9e90' }]
                      },
                      {
                        featureType: 'landscape.natural',
                        elementType: 'geometry',
                        stylers: [{ color: '#dfd2ae' }]
                      },
                      {
                        featureType: 'poi',
                        elementType: 'geometry',
                        stylers: [{ color: '#dfd2ae' }]
                      },
                      {
                        featureType: 'poi',
                        elementType: 'labels.text.fill',
                        stylers: [{ color: '#93817c' }]
                      },
                      {
                        featureType: 'poi.park',
                        elementType: 'geometry.fill',
                        stylers: [{ color: '#a5b076' }]
                      },
                      {
                        featureType: 'poi.park',
                        elementType: 'labels.text.fill',
                        stylers: [{ color: '#447530' }]
                      },
                      {
                        featureType: 'road',
                        elementType: 'geometry',
                        stylers: [{ color: '#f5f1e6' }]
                      },
                      {
                        featureType: 'road.arterial',
                        elementType: 'geometry',
                        stylers: [{ color: '#fdfcf8' }]
                      },
                      {
                        featureType: 'road.highway',
                        elementType: 'geometry',
                        stylers: [{ color: '#f8c967' }]
                      },
                      {
                        featureType: 'road.highway',
                        elementType: 'geometry.stroke',
                        stylers: [{ color: '#e9bc62' }]
                      },
                      {
                        featureType: 'road.highway.controlled_access',
                        elementType: 'geometry',
                        stylers: [{ color: '#e98d58' }]
                      },
                      {
                        featureType: 'road.highway.controlled_access',
                        elementType: 'geometry.stroke',
                        stylers: [{ color: '#db8555' }]
                      },
                      {
                        featureType: 'road.local',
                        elementType: 'labels.text.fill',
                        stylers: [{ color: '#806b63' }]
                      },
                      {
                        featureType: 'transit.line',
                        elementType: 'geometry',
                        stylers: [{ color: '#dfd2ae' }]
                      },
                      {
                        featureType: 'transit.line',
                        elementType: 'labels.text.fill',
                        stylers: [{ color: '#8f7d77' }]
                      },
                      {
                        featureType: 'transit.line',
                        elementType: 'labels.text.stroke',
                        stylers: [{ color: '#ebe3cd' }]
                      },
                      {
                        featureType: 'transit.station',
                        elementType: 'geometry',
                        stylers: [{ color: '#dfd2ae' }]
                      },
                      {
                        featureType: 'water',
                        elementType: 'geometry.fill',
                        stylers: [{ color: '#b9d3c2' }]
                      },
                      {
                        featureType: 'water',
                        elementType: 'labels.text.fill',
                        stylers: [{ color: '#92998d' }]
                      }
                    ],
                  
                    hiding: [
                      {
                        featureType: 'poi.business',
                        stylers: [{ visibility: 'off' }]
                      },
                      {
                        featureType: 'transit',
                        elementType: 'labels.icon',
                        stylers: [{ visibility: 'off' }]
                      }
                    ]
                  };
                  setTimeout(function () {
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
                    LoadMap();

                    $(".map-section.map-contact .map-form").on("click", ".update", function (e) {
                      if ($("#mapForm")[0].checkValidity()) {
                        e.preventDefault();
                        $(".nearBylist").show();
                        $(".map-form").hide();
                        var pin = $("#pin").val();
                        var city_cord = $("#city-cord").val();
                        var city = $("#city-name").val();
                        var latNlongCity = new Array();
                        latNlongCity = city_cord.split(",");

                        var latStCity = $.trim(latNlongCity[0]);
                        var longStCity = $.trim(latNlongCity[1]);
  
                        var pointCity = new google.maps.LatLng(latStCity, longStCity);
                        var mapOptions = {
                          center: pointCity,
                          zoom: 11,
                          mapTypeId: google.maps.MapTypeId.ROADMAP,
                          streetViewControl: false,
                          styles: styles.silver
                        };
                        var map = new google.maps.Map(
                          document.getElementById("map"),
                          mapOptions
                        );
                        loadingNearBylist(pin, city, city_cord, map);
                      }
                    });
  
                    $(".map-section.map-contact .map-form").on("click", ".changed", function (e) {
                      if ($("#mapForm")[0].checkValidity()) {
                        e.preventDefault();
                        $(".nearBylist").show();
                        $(".map-form").hide();
                        var pin = $("#pin").val();
                        var city_cord = $("#city-cord").val();
                        var city = $("#city-name").val();
                        var latNlongCity = new Array();
                        latNlongCity = city_cord.split(",");
  
                        var latStCity = $.trim(latNlongCity[0]);
                        var longStCity = $.trim(latNlongCity[1]);
  
                        var pointCity = new google.maps.LatLng(latStCity, longStCity);
                        var mapOptions = {
                          center: pointCity,
                          zoom: 11,
                          mapTypeId: google.maps.MapTypeId.ROADMAP,
                          streetViewControl: false,
                          styles: styles.silver
                        };
                        var map = new google.maps.Map(
                          document.getElementById("map"),
                          mapOptions
                        );
                        loadingNearBylist(pin, city, city_cord, map);
                      }
                    });
  
                    $('.near-me-map .map-form').on('click', '.update', function (e) {
                      if ($("#mapForm")[0].checkValidity()) {
                        e.preventDefault();
                        $('.nearBylist').show();
                        $('.map-form').hide();
                        var pin = $("#pin").val();
                        var city_cord = $("#city-cord").val();
                        var city = $("#city-name").val();
                        loadingNearBylistNearMe(pin, city, city_cord);
                      }
                    });
  
                    $('.near-me-map .map-form').on('click', '.changed', function (e) {
                      if ($("#mapForm")[0].checkValidity()) {
                        e.preventDefault();
                        $('.nearBylist').show();
                        $('.map-form').hide();
                        var pin = $("#pin").val();
                        var city_cord = $("#city-cord").val();
                        var city = $("#city-name").val();
                        loadingNearBylistNearMe(pin, city, city_cord);
                      }
                    });
  
                    $(".change-location a").click(function (e) {
                      $(".update").addClass("changed");
                      $(".changed").removeClass("update");
                      $(".nearBylist").hide();
                      $(".map-form").show();
                      return false;
                    });
  
                    var select2 = $('#city');
                    $('#state').on('change', function () {
                      var str = $(this.options[this.selectedIndex]).attr('data-value');
                      var latNlong = new Array();
                      latNlong = str.split(",");
                      var latSt = $.trim(latNlong[0]);
                      var longSt = $.trim(latNlong[1]);
                      var point = new google.maps.LatLng(latSt, longSt);
                      var mapOptions = {
                        center: point,
                        zoom: 11,
                        mapTypeId: google.maps.MapTypeId.ROADMAP,
                        streetViewControl: false,
                        styles: styles.silver
                      };
                      var map = new google.maps.Map(
                        document.getElementById("map"),
                        mapOptions
                      );
                      map.setCenter(point);
                      map.setZoom(7);
  
                      select2.attr("disabled", false);
                      var selectedState = $(this).val();
                      if (selectedState != '') {
                        $.ajax({
                          type: "GET",
                          url: drupalSettings.path.baseUrl + "get-city",
                          dataType: 'json',
                          data: "state=" + encodeURIComponent(selectedState),
                          success: function (response) {
                            select2.attr("disabled", false);
                            select2.find("option").remove();
                            $.each(response, function (key, value) {
                              select2.append('<option data-value="' + value.cord + '" value="' + value.city + '">' + value.city + '</option>');
                            });
                            $(".selectpicker").selectpicker("refresh");
                          }
                        });
                      }
                      $(".selectpicker").selectpicker("refresh");
                    });
  
                    $('#city').on('change', function () {
                      var strCity = $(this.options[this.selectedIndex]).attr('data-value');
                      var city = $(this.options[this.selectedIndex]).val().toLowerCase();
                      $("#city-cord").val(strCity);
                      $("#city-name").val(city);
                      var latNlongCity = new Array();
                      latNlongCity = strCity.split(",");
  
                      var latStCity = $.trim(latNlongCity[0]);
                      var longStCity = $.trim(latNlongCity[1]);
                      $(".view-dealer").attr("href", "near-me?city=" + city + "&co=" + latStCity + "," + longStCity);
                      var pointCity = new google.maps.LatLng(
                        latStCity,
                        longStCity
                      );
                      var mapOptions = {
                        center: pointCity,
                        zoom: 11,
                        mapTypeId: google.maps.MapTypeId.ROADMAP,
                        streetViewControl: false,
                        styles: styles.silver
                      };
                      var map = new google.maps.Map(
                        document.getElementById("map"),
                        mapOptions
                      );
                      map.setCenter(pointCity);
                      map.setZoom(11);
                    });
  
                    var cord = getUrlParameter("co");
                    var city = getUrlParameter("city");
                    if (cord && city) {
                      var latNlongCity = new Array();
                      latNlongCity = cord.split(",");
  
                      var latStCity = $.trim(latNlongCity[0]);
                      var longStCity = $.trim(latNlongCity[1]);
  
                      var pointCity = new google.maps.LatLng(latStCity, longStCity);
                      var mapOptions = {
                        center: pointCity,
                        zoom: 11,
                        mapTypeId: google.maps.MapTypeId.ROADMAP,
                        streetViewControl: false,
                        styles: styles.silver
                      };
                      var map = new google.maps.Map(document.getElementById("map"), mapOptions);
                      if (city) {
                        $(".nearBylist").show();
                        $(".map-form").hide();
                        loadingNearBylistNearMe(city, city, cord, map);
                      }
                    }
                  }, 2000);

                  function LoadMap() {
                    // Getting Current Location from HTML5 geolocation.
                    if (navigator.geolocation) {
                      var time = Math.floor(Date.now() / 1000);
                      var limit = 0;
                      navigator.geolocation.getCurrentPosition(function (position) {
                        var pos = {
                          lat: position.coords.latitude,
                          long: position.coords.longitude,
                        };
                        if ($(".map-carousel").length > 0) {
                          limit = 3;
                        }
                        var data = $.parseJSON(
                          $.ajax({
                            url: window.location.origin + "/dealer-filter",
                            type: "POST",
                            data: {
                              lat: position.coords.latitude,
                              long: position.coords.longitude,
                              limit: limit,
                              time: time
                            },
                            async: false
                          }).responseText
                        );
                        var dealer = $.map(data, function (value, index) {
                          return [value];
                        });
                        markers = dealer;

                        $('.nearBylist').show();
                        $('.map-form').hide();
                        $(".change-location").show();
                        if ($(".map-carousel").length > 0) {
                          var pin = "";
                          loadingNearBylist(pin);
                        }
                        if ($(".near-me").length > 0) {
                          loadingNearBylistNearMe();
                        }


                      }, function () {
                        // handleLocationError(true, infoWindow, map.getCenter());
                      });
                    } else {
                      // handleLocationError(false, infoWindow, map.getCenter());
                    }

                  }

                  function loadingNearBylist(pin, city, city_cord, map) {
                    if (map == null) {
                      var mapOptions = {
                        center: new google.maps.LatLng(20.5937, 78.9629),
                        zoom: 11,
                        mapTypeId: google.maps.MapTypeId.ROADMAP,
                        streetViewControl: false,
                        styles: styles.silver
                      };

                      var map = new google.maps.Map(
                        document.getElementById("map"),
                        mapOptions
                      );
                    }

                    //Create and open InfoWindow.
                    var infoWindow = new google.maps.InfoWindow();
                    // Attaching Data
                    var time = Math.floor(Date.now() / 1000);
                    if (pin === undefined) {
                      pin = "";
                    }
                    var data = $.parseJSON(
                      $.ajax({
                        url: window.location.origin + "/dealer-filter",
                        type: "POST",
                        data: {
                          pin: pin,
                          city: city,
                          city_cord: city_cord,
                          limit: 3,
                          time: time
                        },
                        async: false
                      }).responseText
                    );
                    if (data != "No Results Found") {
                      var dealer = $.map(data, function (value, index) {
                        return [value];
                      });
                      markers = dealer;
                    } else {
                      markers = data;
                    }

                    if (markers != 'No Results Found') {
                      var dnum = 0,
                        datalisting;
                      gmarkers = new Array();
                      if ($(".map-carousel").length > 0) {
                        $(".map-carousel").owlCarousel("destroy");
                        $(".nearBylist").html('<div class="map-carousel owl-carousel green"></div><hr class=""><p class="link2"><a href="' + $('.view-dealer').attr('href') + '" class="view-dealer" target="_blank">View All</a></p>');
                      } else {
                        $(".nearBylist").html('<div class="map-carousel owl-carousel green"></div><hr class=""><p class="link2"><a href="' + $('.view-dealer').attr('href') + '" class="view-dealer" target="_blank">View All</a></p>');
                      }
                      for (var i = 0; i < markers.length; i++) {
                        var data = markers[i];
                        if (pin != "") {
                          var km = "";
                        } else {
                          var km = "(" + data.dist + " km from your location )";
                          km = "";
                        }
                        var direction = "https://www.google.com/maps?q=" + data.lat + "," + data.long;
                        datalisting =
                          '<div class="marker-link" data-markerid="' +
                          dnum +
                          '"><h3>' +
                          data.title +
                          " <span>" +
                          km +
                          '</span> </h3><ul class="map-list">' +
                          "<li><p><span>Address : </span> " +
                          data.description +
                          "</p></li>" +
                          "<li>" +
                          "<p> <span>Contact : </span> " +
                          data.contactN +
                          "</p></li>" +
                          "<li><p> <span>Sales Head : </span>" +
                          data.salesHN +
                          "</p>" +
                          "</li>" +
                          "<li>" +
                          "<p> <span>Email : </span>" +
                          data.email +
                          "</p>" +
                          "</li>" +
                          "</ul>" +
                          '<div class="row MT10"><div class="col-md-4 col-sm-4"><a href="' + direction + '" target="_blank" class="btn green btn-default">GET DIRECTION</a></div><div class="col-md-4 col-sm-4 MT5"><a href="contact-us?request=call" class="icons-link"><i class="icon-get-a-call-back"></i><span>GET A CALL BACK</span></a></div><div class="col-md-4 col-sm-4 MT5"><a href="contact-us?request=drive" class="icons-link"><i class="icon-book-a-drive"></i><span>TRACTOR DEMO</span></a></div></div></div>';

                        dnum++;
                        var myLatlng = new google.maps.LatLng(data.lat, data.long);
                        var marker = new google.maps.Marker({
                          position: myLatlng,
                          map: map,
                          icon: window.location.origin + '/themes/swaraj/images/map-pin.png',
                          title: data.title
                        });
                        if (i == 0) {
                          setTimeout(function () {
                            map.setCenter(myLatlng);
                          }, 100);
                          $(".view-dealer").attr("href", "near-me?city=" + data.city + "&co=" + data.lat + "," + data.long);
                        }

                        //Attach click event to the marker.
                        (function (marker, data) {


                          google.maps.event.addListener(marker, "click", function (e) {

                            infoWindow.setContent(

                              "<div class='media'>" +

                              "<div class='media-body'>" +
                              "<h6>" + data.title + "</h6>"
                              +
                              "<p>" + data.contactN + "</p>"
                              +
                              "<p>" + data.salesHN + "</p>"
                              +
                              "<p>" + data.email + "</p>"
                              +
                              "<p>" + data.description + "</p>"
                              +
                              "</div>" +

                              "</div>"

                            );

                            infoWindow.open(map, marker);
                          });

                        })(marker, data);

                        gmarkers.push(marker);

                        // Append a link to the markers DIV for each marker
                        $('.map-carousel').append(datalisting);

                      }
                      $('.map-carousel').owlCarousel({
                        loop: false,
                        responsiveClass: true,
                        nav: false,
                        dots: true,
                        autoplay: false,
                        autoplayHoverPause: true,
                        responsive: {
                          0: {
                            items: 1
                          },
                          600: {
                            items: 1
                          },
                          1000: {
                            items: 1
                          }
                        }
                      });
                      $(".change-location").show();
                    } else {
                      datalisting = '<div class="marker-link"><h3>No Results Found</h3></div>';
                      $(".nearBylist").html(datalisting);
                      $(".change-location").show();
                    }

                    //this part runs when the mapobject is created and rendered
                    //this part runs when the mapobject shown for the first time

                    if ($('.nearBylist .map-carousel .owl-stage .owl-item.active .marker-link').length > 0) {
                      var markeId = $('.nearBylist .map-carousel .owl-stage .owl-item.active .marker-link').data('markerid');

                      (function (marker, markeId) {
                        for (var j = 0; j < gmarkers.length; j++) {
                          gmarkers[j].setIcon(window.location.origin + "/themes/swaraj/images/map-pin-empty.png");
                        }
                        gmarkers[markeId].setIcon(window.location.origin + "/themes/swaraj/images/map-pin.png");
                      })(marker, markeId)
                      gmarkers.push(marker);

                      $('.map-carousel').on('changed.owl.carousel', function () {


                        setTimeout(function () {
                          markeId = $('.map-carousel .owl-stage .owl-item.active .marker-link').data('markerid');
                          (function (marker, markeId) {

                            for (var j = 0; j < gmarkers.length; j++) {
                              gmarkers[j].setIcon(window.location.origin + "/themes/swaraj/images/map-pin-empty.png");
                            }
                            setTimeout(function () {
                              gmarkers[markeId].setIcon(window.location.origin + "/themes/swaraj/images/map-pin.png");
                              map.setCenter(gmarkers[markeId].position);
                            }, 100);

                          })(marker, markeId)
                          gmarkers.push(marker);

                        }, 100);

                      });

                    }
                  }

                  function loadingNearBylistNearMe(pin, city, city_cord, map) {
                    $('.near-me').html('');
                    if (map == null) {
                      var mapOptions = {
                        center: new google.maps.LatLng(20.5937, 78.9629),
                        zoom: 11,
                        mapTypeId: google.maps.MapTypeId.ROADMAP,
                        streetViewControl: false,
                        styles: styles.silver
                      };

                      var map = new google.maps.Map(
                        document.getElementById("map"),
                        mapOptions
                      );
                    }
                    //Create and open InfoWindow.
                    var infoWindow = new google.maps.InfoWindow();
                    // Attaching Data
                    var time = Math.floor(Date.now() / 1000);
                    if (pin === undefined) {
                      pin = "";
                    }
                    var data = $.parseJSON(
                      $.ajax({
                        url: window.location.origin + "/dealer-filter",
                        type: "POST",
                        data: {
                          pin: pin,
                          city: city,
                          city_cord: city_cord,
                          time: time
                        },
                        async: false
                      }).responseText
                    );
                    if (data != "No Results Found") {
                      var dealer = $.map(data, function (value, index) {
                        return [value];
                      });
                      markers = dealer;
                    } else {
                      markers = data;
                    }
                    if (markers != 'No Results Found') {
                      var dnum = 0, km = "", datalisting;
                      gmarkers = new Array();
                      for (var i = 0; i < markers.length; i++) {
                        var data = markers[i];
                        if (pin != "" && pin != undefined) {
                          km = "";
                        } else {
                          km = "(" + data.dist + " km from your location )";
                          km = "";
                        }
                        var direction = "https://www.google.com/maps?q=" + data.lat + "," + data.long;
                        datalisting =
                          '<div class="marker-link-me" data-markerid="' +
                          dnum +
                          '"><h3>' +
                          data.title +
                          ' <span>' + km + '</span> </h3><ul class="map-list">' +
                          "<li><p><span>Address : </span> " +
                          data.description +
                          "</p></li>" +
                          "<li>" +
                          "<p> <span>Contact : </span> " +
                          data.contactN +
                          "</p>" +
                          "</li>" +
                          "<li>" +
                          "<p> <span>Email : </span>" +
                          data.email +
                          "</p>" +
                          "</li>" +
                          "</ul></div>" +
                          '<div class="row MB30"><div class="col-md-4 col-sm-4"><a href="' + direction + '" target="_blank" class="btn green btn-default">GET DIRECTION</a></div><div class="col-md-4 col-sm-4 MT5"><a href="contact-us?request=call" class="icons-link"><i class="icon-get-a-call-back"></i><span>GET A CALL BACK</span></a></div><div class="col-md-4 col-sm-4 MT5"><a href="contact-us?request=drive" class="icons-link"><i class="icon-book-a-drive"></i><span>TRACTOR DEMO</span></a></div></div>';

                        dnum++;
                        var myLatlng = new google.maps.LatLng(data.lat, data.long);
                        var marker = new google.maps.Marker({
                          position: myLatlng,
                          map: map,
                          icon: window.location.origin + "/themes/swaraj/images/map-pin.png",
                          title: data.title
                        });
                        if (i == 0) {
                          map.setCenter(myLatlng);
                        }
                        //Attach click event to the marker.
                        (function (marker, data) {
                          google.maps.event.addListener(marker, "click", function (e) {

                            infoWindow.setContent(
                              "<div class='media'>" +
                              "<div class='media-body'>" +
                              "<h6>" +
                              data.title +
                              "</h6>" +
                              "<p>" +
                              data.contactN +
                              "</p>" +
                              "<p>" +
                              data.salesHN +
                              "</p>" +
                              "<p>" +
                              data.email +
                              "</p>" +
                              "<p>" +
                              data.description +
                              "</p>" +
                              "</div>" +
                              "</div>"
                            );

                            infoWindow.open(map, marker);
                          });
                        })(marker, data);

                        gmarkers.push(marker);

                        // Append a link to the markers DIV for each marker
                        $(".near-me").append(datalisting);

                        $(".near-me-map .map-info .desc").hide();
                      }
                      $(".marker-link-me").on("click", function () {
                        google.maps.event.trigger(
                          gmarkers[$(this).data("markerid")],
                          "click"
                        );
                      });
                      $(".change-location").show();
                    } else {
                      datalisting = '<div class="marker-link"><h3>No Results Found</h3></div>';
                      $(".near-me").append(datalisting);
                      $(".change-location").show();
                    }
                  }
 
                }
              }
            });
            mapObserver.observe();

            $(".map-form .refresh").on('click', function () {
              $("#state").val('');
              $("#city").val('');
              $("#pin").val('');
              $(".selectpicker").selectpicker("refresh");
            });
          }

          $("#datepicker").on('change', function () {
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
                return sParameterName[1] === undefined ?
                  true :
                  decodeURIComponent(sParameterName[1]);
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

          function thankYou() {
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

          var videoCar = $('.gallery-carousal-video'), videoCarN;
          $(".josh-gallery a[href='#galleryModal-3']").click(function () {
            videoCarN = $(this).data('id');
          });
          $('#galleryModal-3').on('shown.bs.modal', function () {
            videoCar.owlCarousel({
              loop: false,
              nav: true,
              dots: true,
              animateOut: 'fadeOut',
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
              },
              onTranslate: function (event) {
                var currentSlide, player, command;
                currentSlide = $('.owl-item.active');
                player = currentSlide.find(".flex-video iframe").get(0);
                command = {
                  "event": "command",
                  "func": "pauseVideo"
                };
                if (player != undefined) {
                  player.contentWindow.postMessage(JSON.stringify(command), "*");
                }
              }
            });
            videoCar.trigger('to.owl.carousel', [videoCarN, 0, true])
          });

          Date.prototype.yyyymmdd = function () {
            var mm = this.getMonth() + 1; // getMonth() is zero-based
            var dd = this.getDate();
  
            return [
              this.getFullYear(),
              (mm > 9 ? "" : "0") + mm,
              (dd > 9 ? "" : "0") + dd
            ].join("-");
          };
          
          $(document).ajaxComplete(function (event, request, settings) {
            if ($('.news-contentTab').length) {
              var tarikh = $("input[name='created']").val().split('-');
              var mydate = new Date(tarikh[0], tarikh[1] - 1);
              var jsdate = mydate.toString().split(" ");
              $("#datepicker").val(jsdate[1] + " " + jsdate[3]);
            }
            $('.selectpicker').selectpicker('refresh');
          });
        });
      }
      Drupal.behaviors.custom.click_set = true;
    }
  }

})(jQuery, Drupal, drupalSettings);