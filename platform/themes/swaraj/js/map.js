var markers = '';
var hd = [];
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


(function ($) {
  var gmarkers = new Array();
  var baseUrl = window.location.origin;
  var markers = new Array();
  if ($('.map-contact').length || $('.near-me-map').length) {
    LoadMap();
  }

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

    function handleLocationError(browserHasGeolocation, infoWindow, pos) {
      infoWindow.setPosition(pos);
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
        var dnum = 0,
          datalisting;
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

    $(".map-section.map-contact .map-form").on("click", ".update", function (e) {
      if ($("#mapForm")[0].checkValidity()) {
        e.preventDefault();
        $(".nearBylist").show();
        $(".map-form").hide();
        var pin = $("#pin").val();
        var city_cord = $("#city-cord").val();
        var city = $("#city-name").val();
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

    $(window).on('load', function (e) {
      var cord = getUrlParameter("co");
      var city = getUrlParameter("city");
      if (cord && city) {
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
    });

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

  }
})(jQuery);