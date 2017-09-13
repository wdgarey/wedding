(function($) {
    "use strict"; // Start of use strict

    // jQuery for page scrolling feature - requires jQuery Easing plugin
    $('a.page-scroll').bind('click', function(event) {
        var $anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: ($($anchor.attr('href')).offset().top - 50)
        }, 1250, 'easeInOutExpo');
        event.preventDefault();
    });

    // Highlight the top nav as scrolling occurs
    $('body').scrollspy({
        target: '.navbar-fixed-top',
        offset: 51
    });

    // Closes the Responsive Menu on Menu Item Click
    $('.navbar-collapse ul li a').click(function() {
        $('.navbar-toggle:visible').click();
    });

    // Offset for Main Navigation
    $('#mainNav').affix({
        offset: {
            top: 100
        }
    })

    // Initialize and Configure Scroll Reveal Animation
    window.sr = ScrollReveal();
    sr.reveal('.sr-icons', {
        duration: 600,
        scale: 0.3,
        distance: '0px'
    }, 200);
    sr.reveal('.sr-button', {
        duration: 1000,
        delay: 200
    });
    sr.reveal('.sr-contact', {
        duration: 600,
        scale: 0.3,
        distance: '0px'
    }, 300);

    // Initialize and Configure Magnific Popup Lightbox Plugin
    $('.popup-gallery').magnificPopup({
        delegate: 'a',
        type: 'image',
        tLoading: 'Loading image #%curr%...',
        mainClass: 'mfp-img-mobile',
        gallery: {
            enabled: true,
            navigateByImgClick: true,
            preload: [0, 1] // Will preload 0 - before current, and 1 after the current image
        },
        image: {
            tError: '<a href="%url%">The image #%curr%</a> could not be loaded.'
        }
    });

})(jQuery); // End of use strict

function myMap() {
        var myLatLng = {lat: 39.3228346, lng: -77.7561169};

        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 16,
          center: myLatLng
        });

        var marker = new google.maps.Marker({
          position: myLatLng,
          map: map,
          title: 'St. Peter\'s Roman Catholic Church'
        });

  receptionMap();
}

function receptionMap() {
        var myLatLng = {lat: 39.297148, lng: -77.851232};

        var map = new google.maps.Map(document.getElementById('receptionmap'), {
          zoom: 16,
          center: myLatLng
        });

        var marker = new google.maps.Marker({
          position: myLatLng,
          map: map,
          title: 'Ballroom located at Hollywood Casino at Charles Town Races'
        });
}

function validatePhone(phone) {
  var valid;
  phone = phone.replace(/[^0-9]/g, '');
  if(phone.length != 10) { 
    valid = false;
  } else {
    valid = true
  }
  return valid;
}

function validateEmail(email) {
  var valid;
  var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;  
  if(email.trim().length == 0
    || email.match(mailformat))   {  
    valid = true;  
  } else {  
    valid = false;  
  }  
  return valid;
}

function validatePartySize(size) {
  var n = parseFloat(size);
  var valid = (!isNaN(n) && n >= 0 && n <= 10);
  return valid;
}

function validateAcceptForm() {
  var msg = "";
  var valid = true;
  var partySize = $('#partysize').val();
  var email = $('#email').val();
  var phone = $('#phone').val();
  if (!validatePartySize(partySize)) {
    msg += "* Invalid party size entered. Must be an integer between 1 and 10\n";
  }
  if (!validateEmail(email)) {
    msg += "* Invalid email entered.\n";
  }
  if (!validatePhone(phone)) {
    msg += "* Invaild phone number entered. Must be 10 digit telephone number.";
  }
  if (msg.length > 0) {
    valid = false;
    alert(msg);
  }
  return valid;
}

