
    // Wait for 2 seconds before appending the code
    setTimeout(function () {
        
        var url = window.location.pathname;
        var lastSegment = url.split("/").pop();
        var newCode = '<li class="lg-d-none">' +
                     '<div class="dropdown transparent-bg v2">' +
                       '<button class="btn dropdown-toggle" type="button" id="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">' +
                         '<span class="lng">Select Language</span>' +
                         '<span class="fa fa-angle-down"></span>' +
                       '</button>' +
                       '<ul class="dropdown-menu langSlt" aria-labelledby="dropdownMenu1">' +
                         '<li><a href="/'+ lastSegment +'">English</a></li>' +
                         '<li><a href="/hi/'+ lastSegment +'">Hindi</a></li>' +
                       '</ul>' +
                     '</div>' +
                   '</li>';
  
        // Find the element with class "mob-trgr.act"
        var targetElement = document.querySelector(".mob-trgr.act");
    
        // Create a new div element to hold the new code
        var newElement = document.createElement("div");
        newElement.innerHTML = newCode;
    
        // Insert the new code before the target element
        targetElement.parentNode.insertBefore(newElement, targetElement);
    }, 2000); // 2-second delay (2000 milliseconds)
