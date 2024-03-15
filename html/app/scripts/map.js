

var markers = [

  {
    "lat": '39.71449',
    "lng": '-86.29842',
    "title": 'Radhe Tractors 1',
    "contactN": "9914145859, 0186 2347330",
    "salesHN": "9914388859",
    "email": "info@vw-swaraj.co.in",
    "cEmail": "crm@vw-cswaraj.co.in",
    "description": 'Dalhousie Road, Mamoon, Pathankot - 145 001'
  },

  {

    "lat": '19.0745',
    "lng": '72.9978',
    "title": 'Radhe Tractors 2',
    "contactN": "9914145859, 0186 2347330",
    "salesHN": "9914388859",
    "email": "info@vw-swaraj.co.in",
    "cEmail": "crm@vw-cswaraj.co.in",
    "description": 'Dalhousie Road, Mamoon, Pathankot - 145 001'
  },

  {
    "lat": '19.2183',
    "lng": '72.9781',
    "title": 'Radhe Tractors 3',
    "contactN": "9914145859, 0186 2347330",
    "salesHN": "9914388859",
    "email": "info@vw-swaraj.co.in",
    "cEmail": "crm@vw-cswaraj.co.in",
    "description": 'Dalhousie Road, Mamoon, Pathankot - 145 001'
  }

];

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


var gmarkers = new Array();

function LoadMap() {

  var mapOptions = {
    center: new google.maps.LatLng(20.5937, 78.9629),
    zoom: 5,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    streetViewControl: false,
    styles: styles.silver
  };

  var map = new google.maps.Map(document.getElementById("map"), mapOptions);

  //Create and open InfoWindow.
  var infoWindow = new google.maps.InfoWindow();

  // Getting Current Location from HTML5 geolocation.
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function (position) {
      var pos = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
      };

      //alert('Getting Geo Location');
      infoWindow.setPosition(pos);
      //infoWindow.setContent('Location found.');
      //infoWindow.open(map);
      map.setCenter(pos);
      map.setZoom(10);
      // map.setIcon('images/map-pin.png');
      $('.nearBylist').show();
      $('.map-form').hide();
      $('.change-location').show();
      if ($('.map-carousel').length > 0) {
        console.log('load ... 1');
        loadingNearBylist();
      }
      if ($('.near-me').length > 0) {
        console.log('load ... 2');
        loadingNearBylistNearMe();
      }



    }, function () {
      handleLocationError(true, infoWindow, map.getCenter());
    });
  } else {

    //alert('Error Geo Location');

    // Browser doesn't support Geolocation
    handleLocationError(false, infoWindow, map.getCenter());
  }

  function handleLocationError(browserHasGeolocation, infoWindow, pos) {
    infoWindow.setPosition(pos);
    infoWindow.setContent(browserHasGeolocation ?
      console.log('Error: The Geolocation service failed.') :
      console.log('Error: Your browser doesn\'t support geolocation.'));
    // infoWindow.open(map);
  }

  function loadingNearBylist() {
    // Attaching Data
    var dnum = 0, datalisting;
    for (var i = 0; i < markers.length; i++) {
      //console.log(markers[i]);
      var data = markers[i];
      datalisting = '<div class="marker-link" data-markerid="' + dnum + '"><h3>' + data.title + ' <span>( 2 km from your location )</span> </h3><ul class="map-list">'
        + '<li><p><span>Address: </span> ' + data.description + '</p></li>'
        + '<li>' + '<p> <span>Contact : </span> ' + data.contactN + '</p>' + '<p> <span>Sales Head : </span>' + data.salesHN + '</p>' + '</li>'
        + '<li>' + '<p> <span>Email : </span>' + data.email + '</p>' + '<p> <span>Confirm Email : </span>' + data.cEmail + '</p>' + '</li>' +
        '</ul></div>';

      dnum++;

      var myLatlng = new google.maps.LatLng(data.lat, data.lng);
      var marker = new google.maps.Marker({
        position: myLatlng,
        map: map,
        icon: 'images/map-pin2.png',
        title: data.title
      });

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

      //$('.map-carousel').append();
      $('.map-carousel').append(datalisting);

    }

    // if ($('.owl-stage-outer').length > 0) { 
    //     $('.map-carousel').owlCarousel('destroy');
    // }

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



    //console.log('tst');


    //this part runs when the mapobject is created and rendered
    //console.log('loaded mapobject');

    //this part runs when the mapobject shown for the first time

    console.log('Map loaded ...');
    var markeId = $('.nearBylist .map-carousel .owl-stage .owl-item.active .marker-link').data('markerid');


    // console.log(markeId, 'Testing Mark ID');

    (function (marker, markeId) {
      for (var j = 0; j < gmarkers.length; j++) {
        gmarkers[j].setIcon("images/map-pin-empty.png");
      }
      gmarkers[markeId].setIcon("images/map-pin.png");
    })(marker, markeId)
    gmarkers.push(marker);

    $('.map-carousel').on('changed.owl.carousel', function () {

      setTimeout(function () {
        console.log(markeId, 'onchange');
        markeId = $('.map-carousel .owl-stage .owl-item.active .marker-link').data('markerid');
        (function (marker, markeId) {

          for (var j = 0; j < gmarkers.length; j++) {
            gmarkers[j].setIcon("images/map-pin-empty.png");
          }
          setTimeout(function () {
            //alert(gmarkers[markeId].position);
            gmarkers[markeId].setIcon("images/map-pin.png");
            //var point = new google.maps.LatLng(19.0213, 72.8424);
            map.setCenter(gmarkers[markeId].position);
          }, 100);

        })(marker, markeId)
        gmarkers.push(marker);

      }, 100);

    });





  }


  function loadingNearBylistNearMe() {
    // Attaching Data
    var dnum = 0, datalisting;
    for (var i = 0; i < markers.length; i++) {
      //console.log(markers[i]);
      var data = markers[i];
      datalisting = '<div class="marker-link-me" data-markerid="' + dnum + '"><h3>' + data.title + ' <span>( 2 km from your location )</span> </h3><ul class="map-list">'
        + '<li><p><span>Address: </span> ' + data.description + '</p></li>'
        + '<li>' + '<p> <span>Contact : </span> ' + data.contactN + '</p>' + '<p> <span>Sales Head : </span>' + data.salesHN + '</p>' + '</li>'
        + '<li>' + '<p> <span>Email : </span>' + data.email + '</p>' + '<p> <span>Confirm Email : </span>' + data.cEmail + '</p>' + '</li>' +
        '</ul></div>' +
        '<div class="row MB30"><div class="col-md-4 col-sm-4"><a href="#" class="btn green btn-default">GET DIRECTION</a></div><div class="col-md-4 col-sm-4 MT5"><a href="#" class="icons-link"><i class="icon-get-a-call-back"></i><span>GET A CALL BACK</span></a></div><div class="col-md-4 col-sm-4 MT5"><a href="#" class="icons-link"><i class="icon-book-a-drive"></i><span>BOOK A TEST DRIVE</span></a></div></div>';

      dnum++;

      var myLatlng = new google.maps.LatLng(data.lat, data.lng);
      var marker = new google.maps.Marker({
        position: myLatlng,
        map: map,
        icon: 'images/map-pin.png',
        title: data.title
      });

      //Attach click event to the marker.
      (function (marker, data) {


        google.maps.event.addListener(marker, "click", function (e) {

          //Wrap the content inside an HTML DIV in order to set height and width of InfoWindow.
          // var art = $('.list-of-country ul li a');
          // //console.log(art);

          // for(var j = 0; j<art.length; j++){
          //     var trt = art[j];
          //     console.log($(trt).text());

          //     if( $(trt).text() == data.title + ' ' +data.country){
          //         $(trt).parents('.list-of-country').find('.' + actClass).removeClass(actClass);
          //         $(trt).addClass(actClass);
          //     }
          // }

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
      $('.near-me').append(datalisting);

      $('.near-me-map .map-info .desc').hide();

    }

    //this part runs when the mapobject shown for the first time

    // console.log('Map loaded ...');
    //var markeId = $('.nearBylist .near-me .marker-link-me').data('markerid');


    // console.log(markeId, 'Testing Mark ID');

    // (function(marker, markeId){
    //   for (var j = 0; j < gmarkers.length; j++) {
    //       gmarkers[j].setIcon("images/map-pin.png");
    //   }
    //   gmarkers[markeId].setIcon("images/map-pin.png");

    //   })(marker, markeId)
    //   gmarkers.push(marker);



    $('.marker-link-me').on('click', function () {
      // $(this).parents('.list-of-country').find('.' + actClass).removeClass(actClass);
      // $(this).addClass(actClass);
      google.maps.event.trigger(gmarkers[$(this).data('markerid')], 'click');
    });



  }

  $('.map-form select.state').on('change', function () {
    var str = this.value;
    var latNlong = new Array();
    latNlong = str.split(",");

    var latSt = $.trim(latNlong[0]);
    var longSt = $.trim(latNlong[1]);


    $('.map-form select.city').attr('disabled', false);
    $('.map-form .bootstrap-select').removeClass('disabled');
    $('.map-form .bootstrap-select .dropdown-toggle').removeClass('disabled');

    var point = new google.maps.LatLng(latSt, longSt);
    map.setCenter(point);
    map.setZoom(7);

  });

  $('.map-form select.city').on('change', function () {
    var strCity = this.value;
    var latNlongCity = new Array();
    latNlongCity = strCity.split(",");

    var latStCity = $.trim(latNlongCity[0]);
    var longStCity = $.trim(latNlongCity[1]);

    var pointCity = new google.maps.LatLng(latStCity, longStCity);
    map.setCenter(pointCity);
    map.setZoom(11);

  });


  // $('#mapForm').submit(function (e) {
  //   e.preventDefault();
  //     show_album();
  // });

  $('#mapForm').on('submit', function (e) {
    e.preventDefault();
    $('.nearBylist').show();
    $('.change-location').show();
    $('.map-form').hide();


    if ($('.map-carousel').length > 0) {
      console.log('load ... 1');
      loadingNearBylist();
    }

    if ($('.near-me').length > 0) {
      console.log('load ... 2');
      $('.near-me-map .map-info .desc').show();
      loadingNearBylistNearMe();
    }


  });

  $('.map-form').on('click', '.changed', function () {
    $('.nearBylist').show();
    $('.change-location').show();
    $('.map-form').hide();
    $('.near-me-map .map-info .desc').hide();
  });

  $('.change-location a').click(function () {

    $('.map-carousel').owlCarousel('destroy');
    $('.map-carousel, .near-me').html('');

    $(this).parent().hide();
    $('.near-me-map .map-info .desc').show();
    $('.update').addClass('changed');
    $('.changed').removeClass('update');
    $('.nearBylist').hide();
    $('.map-form').show();
    return false;
  });


  $('#Distributor').click(function () {
    var point = new google.maps.LatLng(3.6804852, 73.413607);
    map.setCenter(point);

  });

  $('#Dealer').click(function () {
    var point = new google.maps.LatLng(19.0213, 72.8424);
    map.setCenter(point);

  });




}





