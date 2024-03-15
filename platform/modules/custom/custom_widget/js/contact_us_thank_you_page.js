function gtag_report_conversion(url) {

    var callback = function () {
  
      if (typeof(url) != 'undefined') {
  
        window.location = url;
  
      }
  
    };
  
    gtag('event', 'conversion', {
  
        'send_to': 'AW-11315652344/bAsRCN7PoOEYEPjN3JMq',
  
        'event_callback': callback
  
    });
  
    return false;
  
  }