/*Browser detection script start*/
var BrowserDetect = {
  init: function () {
    this.browser = this.searchString(this.dataBrowser) || "Other";
    this.version =
      this.searchVersion(navigator.userAgent) ||
      this.searchVersion(navigator.appVersion) ||
      "Unknown";
  },
  searchString: function (data) {
    for (var i = 0; i < data.length; i++) {
      var dataString = data[i].string;
      this.versionSearchString = data[i].subString;

      if (dataString.indexOf(data[i].subString) !== -1) {
        return data[i].identity;
      }
    }
  },
  searchVersion: function (dataString) {
    var index = dataString.indexOf(this.versionSearchString);
    if (index === -1) {
      return;
    }

    var rv = dataString.indexOf("rv:");
    if (this.versionSearchString === "Trident" && rv !== -1) {
      return parseFloat(dataString.substring(rv + 3));
    } else {
      return parseFloat(
        dataString.substring(index + this.versionSearchString.length + 1)
      );
    }
  },

  dataBrowser: [
    {
      string: navigator.userAgent,
      subString: "Edge",
      identity: "ms-edge",
    },
    {
      string: navigator.userAgent,
      subString: "MSIE",
      identity: "explorer",
    },
    {
      string: navigator.userAgent,
      subString: "Trident",
      identity: "explorer",
    },
    {
      string: navigator.userAgent,
      subString: "Firefox",
      identity: "firefox",
    },
    {
      string: navigator.userAgent,
      subString: "Opera",
      identity: "opera",
    },
    {
      string: navigator.userAgent,
      subString: "OPR",
      identity: "opera",
    },

    {
      string: navigator.userAgent,
      subString: "Chrome",
      identity: "chrome",
    },
    {
      string: navigator.userAgent,
      subString: "Safari",
      identity: "safari",
    },
  ],
};

/* Waypoint script*/
$(function () {
  function onScrollInit(items, trigger) {
    items.each(function () {
      var osElement = $(this),
        osAnimationClass = osElement.attr("data-os-animation"),
        osAnimationDelay = osElement.attr("data-os-animation-delay");

      osElement.css({
        "-webkit-animation-delay": osAnimationDelay,
        "-moz-animation-delay": osAnimationDelay,
        "animation-delay": osAnimationDelay,
      });

      var osTrigger = trigger ? trigger : osElement;

      osTrigger.waypoint(
        function () {
          osElement.addClass("animated").addClass(osAnimationClass);
        },
        {
          triggerOnce: true,
          offset: "90%",
        }
      );
    });
  }
  onScrollInit($(".os-animation"));
  onScrollInit($(".staggered-animation"), $(".staggered-animation-container"));
});

var changeSlide = 4; // mobile -1, desktop + 1
// Resize and refresh page. slider-two slideBy bug remove
var slide = changeSlide;
if ($(window).width() < 600) {
  var slide = changeSlide;
  slide--;
} else if ($(window).width() > 999) {
  var slide = changeSlide;
  slide++;
} else {
  var slide = changeSlide;
}

$("button#formSubmit").on("click", function () {
  //console.log('123');
  //debugger;
  try {
    var elements = document.querySelectorAll("input,select,textarea");
    elements.forEach(function (el, idx) {
      //console.log('tst123');
      if (!el.checkValidity()) {
        var n = $(el).attr("id");
        console.log("Arif" + n);
        $("html,body").animate(
          {
            scrollTop:
              $("#" + n).offset().top -
              $(".header-box").outerHeight() -
              $(".listing-tab").outerHeight(),
          },
          10
        );
        throw Error;
      }
    });
  } catch (e) {
    //console.log("Ending loop")
  }
});

// create as many regular expressions here as you need:
function isNumberKey(evt) {
  var charCode = evt.which ? evt.which : event.keyCode;
  if (charCode > 31 && (charCode < 48 || charCode > 57)) return false;

  return true;
}

// owl for detail page

$(document).ready(function () {
  var bigimage = $(".slider .one");
  var thumbs = $(".slider-two .two");
  //var totalslides = 10;
  var syncedSecondary = true;

  bigimage
    .owlCarousel({
      items: 1,
      slideSpeed: 2000,
      nav: false,
      autoplay: false,
      dots: false,
      loop: true,
      responsiveRefreshRate: 200,
    })
    .on("changed.owl.carousel", syncPosition);

  thumbs
    .on("initialized.owl.carousel", function () {
      thumbs.find(".owl-item").eq(0).addClass("current");
    })
    .owlCarousel({
      margin: 10,
      items: 5,
      dots: false,
      nav: true,
      smartSpeed: 200,
      slideSpeed: 500,
      slideBy: 5,
      responsiveRefreshRate: 100,
      responsive: {
        0: {
          items: 3,
        },
        600: {
          items: 4,
        },
      },
    })
    .on("changed.owl.carousel", syncPosition2);

  function syncPosition(el) {
    //if loop is set to false, then you have to uncomment the next line
    //var current = el.item.index;

    //to disable loop, comment this block
    var count = el.item.count - 1;
    var current = Math.round(el.item.index - el.item.count / 2 - 0.5);

    if (current < 0) {
      current = count;
    }
    if (current > count) {
      current = 0;
    }
    //to this
    thumbs
      .find(".owl-item")
      .removeClass("current")
      .eq(current)
      .addClass("current");
    var onscreen = thumbs.find(".owl-item.active").length - 1;
    var start = thumbs.find(".owl-item.active").first().index();
    var end = thumbs.find(".owl-item.active").last().index();

    if (current > end) {
      thumbs.data("owl.carousel").to(current, 100, true);
    }
    if (current < start) {
      thumbs.data("owl.carousel").to(current - onscreen, 100, true);
    }
  }

  function syncPosition2(el) {
    if (syncedSecondary) {
      var number = el.item.index;
      bigimage.data("owl.carousel").to(number, 100, true);
    }
  }

  thumbs.on("click", ".owl-item", function (e) {
    e.preventDefault();
    var number = $(this).index();
    bigimage.data("owl.carousel").to(number, 300, true);
  });
});

// end

$(document).ready(function () {
  //   $(".one").owlCarousel({
  //     nav: true,
  //     dots: false,
  //     items: 1,
  //   });
  //   $(".two").owlCarousel({
  //     nav: true,
  //     dots: false,
  //     margin: 10,
  //     // mouseDrag: false,
  //     // touchDrag: false,
  //     responsive: {
  //       0: {
  //         items: changeSlide,
  //         slideBy: changeSlide,
  //         margin: 12,
  //       },
  //       600: {
  //         items: changeSlide,
  //         slideBy: changeSlide,
  //       },
  //       1000: {
  //         items: changeSlide + 1,
  //         slideBy: changeSlide + 1,
  //       },
  //     },
  //   });
  //   var owl = $(".one");
  //   owl.owlCarousel();
  //   owl.on("translated.owl.carousel", function (event) {
  //     $(".right").removeClass("nonr");
  //     $(".left").removeClass("nonl");
  //     if ($(".one .owl-next").is(".disabled")) {
  //       $(".slider .right").addClass("nonr");
  //     }
  //     if ($(".one .owl-prev").is(".disabled")) {
  //       $(".slider .left").addClass("nonl");
  //     }
  //     $(".slider-two .item").removeClass("active");
  //     var c = $(".slider .owl-item.active").index();
  //     $(".slider-two .item").eq(c).addClass("active");
  //     var d = Math.ceil((c + 1) / slide) - 1;
  //     $(".slider-two .owl-dots .owl-dot").eq(d).trigger("click");
  //   });
  //   $(".right").click(function () {
  //     $(".slider .owl-next").trigger("click");
  //   });
  //   $(".left").click(function () {
  //     $(".slider .owl-prev").trigger("click");
  //   });
  //   $(".slider-two .item").click(function () {
  //     var b = $(".item").index(this);
  //     $(".slider .owl-dots .owl-dot").eq(b).trigger("click");
  //     $(".slider-two .item").removeClass("active");
  //     $(this).addClass("active");
  //   });
  //   var owl2 = $(".two");
  //   owl2.owlCarousel();
  //   owl2.on("translated.owl.carousel", function (event) {
  //     $(".right-t").removeClass("nonr-t");
  //     $(".left-t").removeClass("nonl-t");
  //     if ($(".two .owl-next").is(".disabled")) {
  //       $(".slider-two .right-t").addClass("nonr-t");
  //     }
  //     if ($(".two .owl-prev").is(".disabled")) {
  //       $(".slider-two .left-t").addClass("nonl-t");
  //     }
  //   });
  //   $(".right-t").click(function () {
  //     $(".slider-two .owl-next").trigger("click");
  //   });
  //   $(".left-t").click(function () {
  //     $(".slider-two .owl-prev").trigger("click");
  //   });

  $("input[name^='sltProImpForm']").click(function () {
    // alert('tst')
    var vl = $(this).val();

    $("div.ProImpForm").hide();
    $("#" + vl).show();
  });

  $("input[name^='selectService']").click(function () {
    // alert('tst')
    var vl = $(this).val();
    $("div.selectService").hide();

    if (vl === "tractor-feild" || vl === "harvesters-feild") {
      $("div.selectService").show();
    }
    $(".selectService .form-group").hide();
    $(".selectService " + "#" + vl).show();
  });
});

$(document).ready(function () {
  $(".about-detail .read-details").click(function () {
    $(this).parents(".about-detail").addClass("active");
    return false;
  });

  $(".speci-carousel").owlCarousel({
    margin: 20,
    loop: false,
    autoWidth: true,
    nav: true,
    dots: false,
    responsive: {
      0: {
        nav: false,
        loop: true,
      },
      600: {
        nav: false,
      },
      1000: {
        items: 4,
      },
    },
  });

  $(".speci-tabs .sp-tabs a").click(function (e) {
    $(".speci-tabs .sp-tabs.active").removeClass("active");
    $(this).parent(".speci-tabs .sp-tabs").addClass("active");
  });

  // Strict only six Character
  var maxLength = 6;
  $("#pincode").keyup(function () {
    var textlen = maxLength - $(this).val().length;
    $("#rchars").text(textlen);
  });
  // -- End

  // $('.search-text-box').keypress(function (event) {
  //     var keycode = (event.keyCode ? event.keyCode : event.which);
  //     if (keycode == '13') {
  //         alert('You pressed a "enter" key in textbox');
  //     }
  // });

  BrowserDetect.init();
  $("body").addClass(
    BrowserDetect.browser + " " + BrowserDetect.browser + BrowserDetect.version
  );
  //for main meun
  $(".dropdown-menu")
    .find(".dorp-dwon-stop")
    .click(function (e) {
      e.stopPropagation();
    });

  //var offsetHt = $('.hero-box').outerHeight() + $('.swaraj717').outerHeight() + $('.listing-tab').outerHeight();
  setTimeout(function () {}, 300);
  var offsetHt = $(".hero-box").outerHeight();
  var stickyHt;
  var lastScrollTop = 0,
    varscroll,
    offsethd;
  var scrollDir = "";

  if ($(window).width() < 768) {
    stickyHt = "65px";
    offsethd = 0;
  } else {
    stickyHt = "75px";
    offsethd = 75;
  }

  //console.log(offset);
  $(window).scroll(function () {
    var st = $(this).scrollTop();
    if (st > lastScrollTop) {
      scrollDir = "down";
    } else {
      scrollDir = "up";
    }
    lastScrollTop = st;

    if (st > offsethd) {
      $(".animate-strip").addClass("scroll animated fadeInDown");
    } else {
      $(".animate-strip").removeClass("scroll animated fadeInDown");
    }

    var HeigtListingTab =
      $(".animate-strip.scroll").outerHeight() * 2 +
      $(".inner-hero").outerHeight() +
      $(".listing-section-1").outerHeight();
    // for stickyTab

    // console.log('animate' + $(".animate-strip.scroll").outerHeight());
    // console.log('hero' + $(".inner-hero").outerHeight());
    // console.log('list' + $(".listing-section-1").outerHeight());

    var $el = $(".listing-tab");
    var isPositionFixed = $el.css("position") == "fixed";
    if ($(this).scrollTop() > HeigtListingTab && !isPositionFixed) {
      $el.css({
        position: "fixed",
        top: "0px",
        width: "100%",
        "z-index": "11",
      });
      $(".swaraj-home.swaraj-home .quick-tools").fadeIn("slow");

      if ($(".listing-tab").length > 0) {
        $(".header-box.inner-header").css("top", "-75px");
      }
    } else if (scrollDir == "up" && st > HeigtListingTab) {
      $(".header-box.inner-header").css("top", "0px");
      $el.css("top", stickyHt);
    } else if (scrollDir == "down" && st > HeigtListingTab) {
      if ($(".listing-tab").length > 0) {
        $(".header-box.inner-header").css("top", "-75px");
      }
      $el.css("top", "0px");
    }
    if ($(this).scrollTop() < HeigtListingTab && isPositionFixed) {
      $el.css({
        position: "static",
        top: "0px",
      });
      $(".header-box.inner-header").css("top", "0px");
      $(".swaraj-home.swaraj-home .quick-tools").fadeOut("slow");
    }

    //console.log("--->" + offsetHt);
    $(".main-menu > ul > li").removeClass("open");
    if ($(this).scrollTop() >= offsetHt) {
      $(".quick-tools").addClass("fixedqt");
    } else {
      //console.log('33');
      $(".quick-tools").removeClass("fixedqt");
    }
  });

  //$('.animate-strip').stickyHeader();

  $(".tog_cont").hide();
  $(".menu-trgr:eq(0)").addClass("act").next().show();

  $(".menu-trgr:not(.no-child-menu)").click(function () {
    if ($(this).next().is(":hidden")) {
      $(".menu-trgr").removeClass("act").next().slideUp("slow");
      $(this).addClass("act").next().slideDown("slow");
    } else {
      $(this).removeClass("act").next().slideUp("slow");
    }
    return false;
  });

  $(".tab_content_box").hide(); //Hide all content
  $(".menutabsbox li:first").addClass("active").show(); //Activate first tab
  $(".tab_content_box:first").show(); //Show first tab content

  //On Click Event
  $(".menutabsbox li").click(function () {
    $(".menutabsbox li").removeClass("active"); //Remove any "active" class
    $(this).addClass("active"); //Add "active" class to selected tab
    $(".tab_content_box").hide(); //Hide all tab content
    var activeTab = $(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
    $(activeTab).fadeIn("slow"); //Fade in the active content
    return false;
  });

  if ($(window).width() > 992) {
    $(".quick-tools a").hover(
      function () {
        $(this).addClass("active");
      },
      function () {
        $(this).removeClass("active");
      }
    );
  }

  $(".swaraj-carousel").owlCarousel({
    loop: false,
    responsiveClass: true,
    //animateOut: 'fadeOut',
    margin: 0,
    nav: true,
    dots: true,
    autoplay: false,
    autoplayHoverPause: true,
    responsive: {
      0: {
        items: 1,
        nav: false,
      },
      600: {
        items: 3,
      },
      1000: {
        items: 4,
      },
    },
  });
  $(".card-carousel").owlCarousel({
    loop: false,
    responsiveClass: true,
    //animateOut: 'fadeOut',
    margin: 30,
    nav: true,
    dots: true,
    autoplay: false,
    autoplayHoverPause: true,
    responsive: {
      0: {
        items: 1,
        nav: false,
      },
      600: {
        items: 2,
        nav: false,
      },
      1000: {
        items: 3,
      },
    },
  });

  $(".similarPro-carousel").owlCarousel({
    loop: false,
    responsiveClass: true,
    //animateOut: 'fadeOut',
    margin: 30,
    nav: true,
    dots: true,
    autoplay: false,
    autoplayHoverPause: true,
    responsive: {
      0: {
        items: 1,
        nav: false,
        dots: true,
      },
      600: {
        items: 2,
        nav: false,
      },
      1000: {
        items: 3,
      },
    },
  });

  $(".tesimonial-carousel").owlCarousel({
    loop: true,
    responsiveClass: true,
    stagePadding: 150,
    nav: true,
    dots: true,
    autoplay: false,
    responsive: {
      0: {
        items: 1,
        stagePadding: 0,
        nav: false,
      },
      600: {
        items: 1,
        stagePadding: 0,
        nav: false,
      },
      1000: {
        items: 1,
      },
    },
  });

  $(".tabs-carousel").owlCarousel({
    loop: true,
    responsiveClass: true,
    nav: false,
    dots: true,
    autoplay: false,
    responsive: {
      0: {
        items: 1,
      },
      600: {
        items: 1,
      },
      1000: {
        items: 1,
      },
    },
  });
  $(".listing-tabs-carousel").owlCarousel({
    loop: false,
    responsiveClass: true,
    //autoWidth:true,
    nav: true,
    dots: false,
    autoplay: false,
    // margin: 65,
    responsive: {
      0: {
        items: 1,
      },
      600: {
        items: 5,
      },
      1000: {
        items: 6,
      },
    },
  });

  $(".imp-listing-tabs-carousel").owlCarousel({
    loop: false,
    responsiveClass: true,
    //autoWidth:true,
    nav: true,
    dots: false,
    autoplay: false,
    // margin: 65,
    responsive: {
      0: {
        items: 1,
      },
      600: {
        items: 2,
      },
      1000: {
        items: 3,
      },
    },
  });

  $(".milestone-carousel").owlCarousel({
    loop: false,
    responsiveClass: true,
    nav: true,
    dots: false,
    rewindNav: false,
    autoplay: false,
    responsive: {
      0: {
        items: 5,
        nav: false,
      },
      600: {
        items: 5,
      },
      1000: {
        items: 7,
        slideBy: 7,
      },
    },
  });

  //     $('.milestone-tabs a').on('click', function () {
  //         $('.milestoneBox-carousel .owl-stage-outer').hide();
  //         setTimeout(function () {
  //             $('.milestoneBox-carousel .owl-stage-outer').show();
  //         }, 500);
  //     })

  $(".milestoneBox-carousel").owlCarousel({
    loop: false,
    responsiveClass: true,
    nav: false,
    //margin:70,
    dots: true,
    responsive: {
      0: {
        items: 1,
        dots: false,
      },
      600: {
        items: 1,
      },
      1000: {
        items: 1,
      },
    },
  });

  $(".gallery-carousal").owlCarousel({
    loop: false,
    nav: true,
    dots: true,
    responsive: {
      0: {
        items: 1,
        dots: false,
      },
      600: {
        items: 1,
      },
      1000: {
        items: 1,
      },
    },
    onTranslate: function (event) {
      var currentSlide, player, command;

      currentSlide = $(".owl-item.active");

      player = currentSlide.find(".flex-video iframe").get(0);

      command = {
        event: "command",
        func: "pauseVideo",
      };

      if (player != undefined) {
        player.contentWindow.postMessage(JSON.stringify(command), "*");
      }
    },
  });

  var photoCar = $(".gallery-carousal-photo"),
    photoCarN;
  $(".josh-gallery a[href='#galleryModal-2']").click(function () {
    photoCarN = $(this).data("id");
    // console.log(photoCarN);
  });
  $("#galleryModal-2").on("shown.bs.modal", function () {
    photoCar.owlCarousel({
      loop: false,
      nav: true,
      dots: true,
      animateOut: "fadeOut",
      responsive: {
        0: {
          items: 1,
          dots: false,
        },
        600: {
          items: 1,
        },
        1000: {
          items: 1,
        },
      },
      onTranslate: function (event) {
        var currentSlide, player, command;

        currentSlide = $(".owl-item.active");

        player = currentSlide.find(".flex-video iframe").get(0);

        command = {
          event: "command",
          func: "pauseVideo",
        };

        if (player != undefined) {
          player.contentWindow.postMessage(JSON.stringify(command), "*");
        }
      },
    });
    photoCar.trigger("to.owl.carousel", [photoCarN, 0, true]);
  });

  // $('#photosModal').on('shown.bs.modal', function () {
  //   photoCar.owlCarousel({
  //     loop: false,
  //     nav: true,
  //     dots: false,
  //     navText: ['<span class="arw-left"></span>', '<span class="arw-right"></span>'],
  //     // smartSpeed: 1000,
  //     animateOut: 'fadeOut',
  //     items: 1
  //   });
  //   photoCar.trigger('to.owl.carousel', [photoCarN, 0, true])

  // });

  $(".galleryModal").on("hidden.bs.modal", function () {
    $(".galleryModal .flex-video iframe").attr(
      "src",
      $(".galleryModal .flex-video iframe").attr("src")
    );
  });

  // $('#galleryModal .gallery-fig img').load(function() {
  //     var gMimgH = $('#galleryModal .gallery-fig img').outerHeight(); // control test
  // });

  $(".galleryModal .modal-dialog .owl-stage-outer").hide();
  $(".galleryModal").on("shown.bs.modal", function (e) {
    setTimeout(function () {
      $(".galleryModal .modal-dialog .owl-stage-outer").show();
      var gMimgH = $(".galleryModal .gallery-fig img").outerHeight();
      // console.log(gMimgH);
      $(".galleryModal .flex-video iframe").height(gMimgH);
    }, 500);
  });

  // $("#myModal").on('hidden.bs.modal', function (e) {
  //     $("#myModal iframe").attr("src", $("#myModal iframe").attr("src"));
  // });

  // $('#galleryModal .close-video').click(function(e){

  //     console.log('111');
  //     var vidc = document.getElementsByClassName("SwarajVideo");
  //     //var vidc = $('.gallery-carousal iframe.SwarajVideo');
  //     // for (var i = 0; i < vidc.length; i++) {
  //     //     vidc[i].pause();
  //     // }

  //     var $if = $(e.delegateTarget).find('iframe');
  //     var src = $if.attr("src");
  //     $if.attr("src", '/empty.html');
  //     $if.attr("src", src);

  // });

  $(".milestone .milestone-tabs a").click(function (e) {
    $(".milestone .milestone-tabs.active").removeClass("active");
    $(this).parent(".milestone .milestone-tabs").addClass("active");
  });

  $(".share-box").click(function () {
    console.log("click toggle");
    $(this).toggleClass("act");
  });

  $(document).click(function (e) {
    if (!$(e.target).parents().addBack().is(".share-box")) {
      $(".share-box").removeClass("act");
    }
  });

  $(".tabs-slider .tabsnav a").click(function (e) {
    $(".tabs-slider .tabsnav.active").removeClass("active");
    $(this).parent(".tabs-slider .tabsnav").addClass("active");
  });

  $(".owl-carousel.owl-drag .owl-item, .farmer-box").hover(
    function () {
      $(this).find(".hover-img").show();
      $(this).find(".normal-img").hide();
      console.log("hover");
    },
    function () {
      $(this).find(".hover-img").hide();
      $(this).find(".normal-img").show();
    }
  );

  if ($(window).width() >= 768) {
    $(".product-details").hover(
      function () {
        $(this).find(".info").stop().slideDown();
      },
      function () {
        $(this).find(".info").stop().slideUp();
      }
    );
  }

  /* Get iframe src attribute value i.e. YouTube video url
    and store it in a variable */
  var url = $("#SwarajVideo").attr("src");

  /* Assign empty url value to the iframe src attribute when
    modal hide, which stop the video playing */
  $("#myModalVideo").on("hide.bs.modal", function () {
    $("#SwarajVideo").attr("src", "");
  });

  /* Assign the initially stored url back to the iframe src
    attribute when modal is displayed again */
  $("#myModalVideo").on("show.bs.modal", function () {
    $("#SwarajVideo").attr("src", url);
  });

  if ($(window).width() <= 767) {
    //$('#homeproduct').remove();
    var ulWidth = 0;
    $(".our-product ul li").each(function () {
      ulWidth = ulWidth + 15 + $(this).outerWidth();
    });

    $(".our-product ul").width(ulWidth);
    var ulWidth2 = 0;
    $(".wrapper-spec-list ul li").each(function () {
      ulWidth2 = ulWidth2 + 15 + $(this).outerWidth();
    });
    $(".wrapper-spec-list ul").width(ulWidth2);

    $(".tabsnav a").click(function () {
      setTimeout(function () {
        //console.log('testing..console')
        var ulWidth2 = 0;
        $(".wrapper-spec-list ul li").each(function () {
          ulWidth2 = ulWidth2 + 15 + $(this).outerWidth();
        });
        $(".wrapper-spec-list ul").width(ulWidth2);
      }, 500);
    });

    //console.log($('.our-product ul li:last-child.active').length);

    if ($(".our-product ul li:last-child.active").length > 0) {
      var outerContent = $(".our-product");
      var innerContent = $(".our-product > ul");

      outerContent.scrollLeft(innerContent.width() - outerContent.width());
    }
  }

  $(".menu-mob").click(function () {
    $(".menu-popup").addClass("open");
    $("body").addClass("overflow");
  });

  $(".close-mob").click(function () {
    $(".menu-popup, .search-popup").removeClass("open");
    $("body").removeClass("overflow");
  });

  $(".search-mob").click(function () {
    $(".search-popup").addClass("open");
    $("body").addClass("overflow");
  });

  $(".mob-cont").hide();
  $(".mob-trgr:eq(0)").addClass("act").next().show();

  $(".langSlt li a").click(function () {
    $(this).parents(".langSlt").children().removeClass("act");
    $(this).parent().addClass("act");
    $(this)
      .parents(".transparent-bg.v2")
      .children(".btn")
      .children("span.lng")
      .text("Selected " + $(this).text());
  });

  //$('.mob-cont').hide();
  $(".accordion .tab-pane .grey .mob-trgr:eq(0)").addClass("act").next().show();

  $(".mob-trgr").click(function () {
    if ($(this).next().is(":hidden")) {
      $(".mob-trgr").removeClass("act").next().slideUp("slow");
      $(this).addClass("act").next().slideDown("slow");
    } else {
      $(this).removeClass("act").next().slideUp("slow");
    }
  });

  $(".mob-coin").hide();
  $(".mob-trin:eq(0)").addClass("act").next().show();

  $(".mob-trin").click(function () {
    if ($(this).next().is(":hidden")) {
      $(".mob-trin").removeClass("act").next().slideUp("slow");
      $(this).addClass("act").next().slideDown("slow");
    } else {
      $(this).removeClass("act").next().slideUp("slow");
    }
  });
  var heroCarousel = $(".hero-carousel");
  heroCarousel.owlCarousel({
    loop: true,
    responsiveClass: true,
    //animateOut: 'fadeOut',
    margin: 0,
    nav: true,
    dots: true,
    autoplay: true,
    autoplaySpeed: 1500,
    smartSpeed: 1500,
    autoplayHoverPause: true,
    responsive: {
      0: {
        nav: false,
        items: 1,
      },
      600: {
        nav: true,
        items: 1,
      },
      1000: {
        items: 1,
      },
    },
  });

  var producCarousel = $(".product-carousel");
  producCarousel.owlCarousel({
    loop: true,
    responsiveClass: true,
    margin: 0,
    nav: true,
    dots: true,
    autoplay: true,
    responsive: {
      0: {
        items: 1,
      },
      600: {
        items: 1,
      },
      1000: {
        items: 1,
      },
    },
  });

  $(".media-hero-carousel").owlCarousel({
    loop: true,
    responsiveClass: true,
    margin: 0,
    nav: false,
    dots: true,
    autoplay: false,
    responsive: {
      0: {
        items: 1,
      },
      600: {
        items: 1,
      },
      1000: {
        items: 1,
      },
    },
  });

  $(".article-media-car").owlCarousel({
    loop: true,
    responsiveClass: true,
    margin: 0,
    nav: false,
    dots: true,
    autoplay: false,
    responsive: {
      0: {
        items: 1,
      },
      600: {
        items: 1,
      },
      1000: {
        items: 1,
      },
    },
  });

  $(".list-box li").click(function () {
    $(this).parents(".list-box").toggleClass("active");
  });

  $(".quickLink").click(function () {
    $(".quick-tools.fixedqt ul").toggle();
    $(this).toggleClass("active");
  });

  $(".counter").countUp();

  heroCarousel.on("change.owl.carousel", function (property) {
    console.log(11111111);

    obthero1.reset().play();
    obthero2.reset().play();
    obthero3.reset().play();
    obthero4.reset().play();
    obthero5.reset().play();
    obthero6.reset().play();

    // new Vivus('canvas3', {type: 'delayed', duration: 700, }, function(canvas3){
    //     setTimeout(function(){ canvas3.stop(); }, 9000);

    // });
  });

  setTimeout(function () {
    $(".goog-te-menu-frame skiptranslate").click(function () {
      $(this).addClass("active");
    });
  }, 4000);

  $(".proDetail .tabsnav a").click(function () {
    id = $(this).attr("href");
    //console.log(id);
    $("html,body").animate(
      {
        scrollTop: $(id).offset().top - $(".proDetail").outerHeight() - 70,
      },
      "slow"
    );
  });

  // For Mobile

  $(".proDetail .our-product li a").click(function () {
    id = $(this).attr("href");
    //console.log(id);
    $("html,body").animate(
      {
        scrollTop: $(id).offset().top - $(".proDetail").outerHeight() - 70,
      },
      "slow"
    );
  });

  var date = new Date();
  var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());

  $("#datetimepicker1").datepicker({
    // format: "dd MM yyyy"
    todayHighlight: true,
    format: "M yyyy",
    viewMode: "months",
    minViewMode: "months",
    autoclose: true,
  });

  $("#datetimepicker1").datepicker("setDate", today);

  $("#datetimepicker2").datepicker({
    // format: "dd MM yyyy"
    todayHighlight: true,
    format: "dd-mm-yyyy",
    autoclose: true,
    // ,
    // viewMode: "months",
    // minViewMode: "months"
  });

  $("#datepickerEnquiry").datepicker({
    startDate: "+1d",
    endDate: "+3m",
    todayHighlight: true,
    format: "dd-mm-yyyy",
    autoclose: true,
  });

  $("#datepickerEnquiry1").datepicker({
    dateFormat: "dd-mm-yy",
    //maxDate: 0,
    changeMonth: true,
    changeYear: true,
    yearRange: "-99:-18",
  });

  // For Mobile Next Form

  $("div.step.row").each(function (i) {
    $(this)
      .find("span")
      .text(++i + 1);
  });

  $(document).on("click", ".tell-us-box .next-btn a", function () {
    $(this).parents(".tell-us-box").next().show();
    $(this).hide();
    if ($(".tell-us-box.step-bx .container .step").is(":hidden")) {
      console.log($(".tell-us-box.step-bx .container .step.hidden").length);
      $(".tell-us-box.step-bx .container .step.hidden")
        .next()
        .addClass("hidden");
    }
    $(".tell-us-box.step-bx .container .step:first-child").addClass("hidden");
  });

  $(".business-select-box select.selectpicker").on("change", function () {
    if ($(window).width() <= 767) {
      $(".tell-us-box").hide();
      $(".next-btn a").show();
      $(".tell-us-box:first-child").show();
      $(".tell-us-box.step-bx .container .step").removeClass("hidden");
      $("button.ref-btn").click();
    }
  });

  //    $('.yourself-bx .next-btn a').click(function(){
  //       // alert('to looking');
  //        $(this).hide();
  //        $('.step-bx .st2').hide();
  //        $('.test-drive-bx').show();

  //        $('html,body').animate({
  //            scrollTop: $('.test-drive-bx').offset().top - 65
  //        }, 'slow');
  //    });

  //    $('.test-drive-bx .next-btn a').click(function(){
  //        //alert('to Located ');
  //        $(this).hide();
  //        $('.step-bx .st3').hide();
  //        $('.located-bx').show();

  //        $('html,body').animate({
  //            scrollTop: $('.located-bx').offset().top - 65
  //        }, 'slow');
  //    });

  //    $('.located-bx .next-btn a').click(function(){
  //       // alert('to Message');
  //        $(this).hide();
  //        $('.step-bx .st4').hide();
  //        $('.feedback').show();

  //        $('html,body').animate({
  //            scrollTop: $('.feedback').offset().top - 65
  //        }, 'slow');
  //    });

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
        nav: true,
      },
      600: {
        items: 2,
      },
      1000: {
        items: 3,
      },
    },
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
        nav: false,
      },
      600: {
        items: 1,
        nav: false,
      },
      1000: {
        items: 1,
      },
    },
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
        nav: false,
      },
      600: {
        items: 2,
        nav: false,
        margin: 20,
      },
      1000: {
        items: 1,
      },
    },
  });

  $(".dealer-carousel").owlCarousel({
    loop: true,
    responsiveClass: true,
    nav: false,
    dots: false,
    autoplay: true,
    autoplaySpeed: 1500,
    items: 1,
  });

  if ($(window).width() < 992) {
    $(".sel-prod").click(function () {
      $(this).toggleClass("act").next().slideToggle();
    });

    $(".prod-dtl .tog_cont ul li a").click(function () {
      var a = $(this).text(),
        b = $(this).attr("data-catg");
      $(this).parents(".prod-dtl").slideUp().prev().removeClass("act");
      $(".sel-prod span").text(a);
      $(".sel-prod strong").text(b);
    });
  }

  // $("button[data-id='tractor_model']").click(function(){
  //     alert('test--2');
  // })

  $(".ref-btn").click(function () {
    // alert('test');
    $(".tell-us-box .selectpicker").selectpicker("deselectAll");
    $(".tell-us-box .selectpicker").val("default").selectpicker("refresh");
  });

  // Form Js

  $("#myform").submit(function (e) {
    e.preventDefault();
    show_album();
  });
});

function show_album() {
  $("body").addClass("ohidden");
  $("#dialog").fadeIn();

  var popMargTop = $("#dialog").height() / 2;
  var popMargLeft = $("#dialog").width() / 2;

  $("#dialog").css({
    "margin-top": -popMargTop,
    "margin-left": -popMargLeft,
  });

  $("body").append('<div id="fade"></div>'); //Add the fade layer to bottom of the body tag.
  $("#fade")
    .css({
      filter: "alpha(opacity=80)",
    })
    .fadeIn();
}

function hide_album() {
  $("body").removeClass("ohidden");
  $("#fade , #dialog").fadeOut(function () {
    $("#fade").remove(); //fade them both out
  });
}

$(document).ready(function () {
  var offsethdBand = 0;
  if ($(window).width() < 768) {
    offsethdBand = 65;
  } else {
    offsethdBand = 75;
  }

  $("html,body").animate(
    {
      scrollTop: $(".scrollTopBand").offset().top - offsethdBand,
    },
    1500
  );
});

obthero1 = new Vivus("canvas1", {
  type: "delayed",
  duration: 700,
});

obthero2 = new Vivus("canvas2", {
  type: "delayed",
  duration: 500,
});
obthero3 = new Vivus("canvas3", {
  type: "delayed",
  duration: 700,
});
obthero4 = new Vivus("canvas4", {
  type: "delayed",
  duration: 500,
});
obthero5 = new Vivus("canvas5", {
  type: "delayed",
  duration: 700,
});
obthero6 = new Vivus("canvas6", {
  type: "delayed",
  duration: 500,
});

obt1 = new Vivus("obturateur1", {
  type: "delayed",
  duration: 150,
});
obt2 = new Vivus("obturateur2", {
  type: "delayed",
  duration: 150,
});
obt3 = new Vivus("obturateur3", {
  type: "delayed",
  duration: 300,
});
obt4 = new Vivus("obturateur4", {
  type: "delayed",
  duration: 300,
});
obt5 = new Vivus("obturateur5", {
  type: "delayed",
  duration: 300,
});

$(function () {
  $(".cityvalue li a").click(function () {
    $(".citybox .textval:first-child").text($(this).text());
    $(".citybox .textval:first-child").val($(this).text());
  });
});
$("document").ready(function () {
  $("#google_translate_element").on("click", function () {
    if ($(".goog-te-menu-frame").is(":visible")) {
      console.log(1111);
    } else {
      console.log(222);
    }
    // Change font family and color
    $("iframe")
      .contents()
      .find(
        ".goog-te-menu2-item div, .goog-te-menu2-item:link div, .goog-te-menu2-item:visited div, .goog-te-menu2-item:active div"
      ) //, .goog-te-menu2 *
      .css({
        color: "#333333",
        "background-color": "#fff",
        "font-family": '"Gilroy-Light,Arial,sans-serif',
        padding: "5px 20px",
      });

    $(this).find(".skiptranslate").addClass("selected");
    $(this).find(".skiptranslate").css("width", "154px");

    // Change hover effects  #e3e3ff = white
    $("iframe")
      .contents()
      .find(".goog-te-menu2-item div")
      .hover(
        function () {
          $(this)
            .css("background-color", "#f5f5f5")
            .find("span.text")
            .css("color", "#333");
        },
        function () {
          $(this)
            .css("background-color", "#FFF")
            .find("span.text")
            .css("color", "#333");
        }
      );

    // Change Google's default blue border
    $("iframe")
      .contents()
      .find(".goog-te-menu2")
      .css("border", "1px solid #FFF");

    $("iframe")
      .contents()
      .find(".goog-te-menu2")
      .css("background-color", "#FFF");
    $("iframe").contents().find(".goog-te-menu2").css("width", "154px");

    //$("iframe").contents().find('.goog-te-menu2').css('width', '154px');

    // Change the iframe's box shadow
    $(".goog-te-menu-frame").css({
      "-moz-box-shadow": "none",
      "-webkit-box-shadow": "none",
      "box-shadow": "none",
    });

    // $('body').find('#google_translate_element').click(function (e) {
    //     e.stopPropagation();
    //     $("#google_translate_element").find('.skiptranslate').removeClass('active');
    // });
  });

  $("#google_translate_element-mobile").on("click", function () {
    // Change font family and color
    $("iframe")
      .contents()
      .find(
        ".goog-te-menu2-item div, .goog-te-menu2-item:link div, .goog-te-menu2-item:visited div, .goog-te-menu2-item:active div"
      ) //, .goog-te-menu2 *
      .css({
        color: "#333333",
        "background-color": "#fff",
        "font-family": '"Gilroy-Light,Arial,sans-serif',
        padding: "5px 20px",
      });

    $(this).find(".skiptranslate").addClass("selected");
    $(this).find(".skiptranslate").css("width", "154px");

    // Change hover effects  #e3e3ff = white
    $("iframe")
      .contents()
      .find(".goog-te-menu2-item div")
      .hover(
        function () {
          $(this)
            .css("background-color", "#f5f5f5")
            .find("span.text")
            .css("color", "#333");
        },
        function () {
          $(this)
            .css("background-color", "#FFF")
            .find("span.text")
            .css("color", "#333");
        }
      );

    // Change Google's default blue border
    $("iframe")
      .contents()
      .find(".goog-te-menu2")
      .css("border", "1px solid #FFF");

    $("iframe")
      .contents()
      .find(".goog-te-menu2")
      .css("background-color", "#FFF");
    $("iframe").contents().find(".goog-te-menu2").css("width", "154px");

    //$("iframe").contents().find('.goog-te-menu2').css('width', '154px');

    // Change the iframe's box shadow
    $(".goog-te-menu-frame").css({
      "-moz-box-shadow": "none",
      "-webkit-box-shadow": "none",
      "box-shadow": "none",
    });

    // $('body').find('#google_translate_element').click(function (e) {
    //     e.stopPropagation();
    //     $("#google_translate_element").find('.skiptranslate').removeClass('active');
    // });
  });

  // setTimeout(function () {
  //         var $button = $('.skiptranslate goog-te-gadget').clone();
  //         $('.tst').html($button);
  // },5000);
});
