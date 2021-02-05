var $j = jQuery.noConflict();
var sHeader, sHeaderTop, sHeaderBottom;
var contentQTop;

var colorsDict = {
    "Social & Emotional Learning" : "social-and-emotional-learning",
    "Connection to Families & Students" : "connection-to-families-and-students",
    "Student Engagement Aligned to Standards" : "student-engagement-aligned-to-standards",
    "Flexibility & Choice for Diverse Needs" : "flexibility-and-choice-for-diverse-needs",
    "Feedback on Student Work" : "feedback-on-student-work",
    "Collaboration Among Students" : "collaboration-among-students"
};

$j(document).ready(function () {
    
    $j('.hidden').hide();

    //sticky header setup
    window.onscroll = function() {showHomeElements()};
    sHeader = $j('.landing_breadcrumbs');
    sHeaderTop = $j('#get_started').offset().top;
    sHeaderBottom= $j('#content_type_decision_fork').offset().top;
    contentQTop = $j('#wheel_decision_fork').offset().top + $j('#wheel_decision_fork').outerHeight(true);
    
    //load content-type-decision-fork breadcrumb images at start
    let img1 = document.createElement('IMG');
    $j('body').append(img1);
    $j(img1).attr('src','https://onrampremotelearning.net/wp-content/themes/Astra-Child/img/asynchronously.png');
    $j(img1).hide();
    let img2 = document.createElement('IMG');
    $j('body').append(img2);
    $j(img2).attr('src','https://onrampremotelearning.net/wp-content/themes/Astra-Child/img/synchronously.png');
    $j(img2).hide();
    
    //onramp spacer setup
    setupOnRampSpacers();
    

});

function setupOnRampSpacers() {
    $j('.onramp_spacer').each(function() {
        const targetID = $j(this).find('input').first().val();
        const target = $j('#' + targetID);
        
        const margintop = 250;
        const elementHeight = target.height();
        
        const spacerHeight = window.innerHeight - (margintop + elementHeight);
        $j(this).height(spacerHeight);
    });
}

function showHomeElements() {
    if (window.pageYOffset > $j('#get_started').offset().top) {
        
        //header
        sHeader.addClass('sticky');
        
        //wheel question
        $j('.wheel_cat_breadcrumb').removeClass('hide');
        
        //content-type question
        if (window.pageYOffset > $j('#wheel_decision_fork').offset().top + $j('#wheel_decision_fork').outerHeight(true)) {
            $j('.content-type_breadcrumb').removeClass('hide');
        }
        
        //sponsorship
        if (window.pageYOffset > $j('#content_type_decision_fork').offset().top) {
            sHeader.removeClass('sticky');
        }
        
    } else {
        sHeader.removeClass('sticky');
    }
}

function stickyHeader() {
    if (window.matchMedia("(max-width: 500px)").matches) {
          if (window.pageYOffset > stickyTop &&
              window.pageYOffset < stickyBottom) {
              if (!sHeader.hasClass('sticky')) {
                $j('.wheel_cat_breadcrumb').removeClass('hide');
                sHeader.addClass('sticky');
              }
          } else {
            sHeader.removeClass('sticky');
          }
          
    }

}

function startLearning(data) {
    data.event.preventDefault();
    
    //other animations
    if ('scroll' in data) {
        // console.log('scroll in data');
        // console.log(data);
        
        var targetID;
        if ('id' in data.scroll.target) {
            targetID = data.scroll.target.id;
        } else {
            targetID = data.scroll.target;
        }
        
        scrollToTop(targetID,data.scroll.time);
        
    }
    
}

function chooseContentType(data) {
    data.event.preventDefault();
    // console.log(data);
    
    setHiddenInput(data.event, data.hInput.name, data.hInput.value);
    setContentTypeBreadcrumb(data.hInput.name, data.hInput.value);
    
    $j('.loader').removeClass('hide');
    
    setTimeout(function() {
        sHeader.removeClass('sticky');
    },500);
    
    //mobile
    if (window.matchMedia("(max-width: 1000px)").matches) {
        
        
    }

    submitForm(data.event, data.element);
    
}

function scrollToCenterInViewport(elementID, duration) {
    // console.log('scrolling to ' + elementID);
    var w = $j(window);
    $j('body').addClass('disable-hover');
    var element = $j('#' + elementID);
    element.show();
    
    var headerHeight = $j('.landing_breadcrumbs').height();
    // console.log(headerHeight);
	var elementTop = element.offset().top,
	elementHeight = element.height(),
	viewportHeight = w.height(),
	scrollIt = elementTop - ((viewportHeight - elementHeight) / 2) - headerHeight;
	setTimeout(function() {
	    $j('body').removeClass('disable-hover');
	}, duration);
    $j("html, body").animate({ scrollTop: scrollIt}, duration );
}

function scrollToTop(elementID, duration) {
    var w = $j(window);
    $j('body').addClass('disable-hover');
    var element = $j('#' + elementID);
    element.show();
    
    var elementTop = element.offset().top;
    // console.log('top ' + elementTop);
    // console.log($j('.landing_breadcrumbs'));
    var headerHeight = $j('.landing_breadcrumbs').height();
    // console.log('header ' + headerHeight);
    var scrollIt = elementTop - headerHeight - 100; //with bottom padding
	$j("html, body").animate({ scrollTop: scrollIt},
	                         duration,
	                         function() {
	                             $j('body').removeClass('disable-hover');
	                         });
}

function showNextElement(elementID) {
    // console.log(elementID);
    var element = $j('#' + elementID);
    if (element.length > 0) {
        element.show();
    }
}

function setHiddenInput(e=undefined, name, value) {
    if (e != undefined) e.preventDefault();
    
    var form;
    if ($j('form.submit_container').length != 0) {
        form = $j('form.submit_container').first();
        var input = form.find('input[name="' + name + '"]');
        if (input.length <= 0) {
            var input = document.createElement("INPUT");
            $j(input).attr('name', name);
            $j(input).attr('value', value);
            $j(input).addClass('hidden_input');
            form.append(input);
        } else {
            $j(input).attr('value', value);
        }
    }
}

function submitForm(event, element) {
    event.preventDefault();
    // console.log('submitting...');
    if ($j('form.submit_container').length != 0) {
        $j('form.submit_container').first().submit();
    }
}

function setContentTypeBreadcrumb(name,value) {

    $j('.content-type_breadcrumb').find('.breadcrumb_image').parent().addClass('breadcrumb_followed');
    
    if (value == 25594) {
        $j('.content-type_breadcrumb').find('h3').html('asynchronously');
        $j('.content-type_breadcrumb').find('.breadcrumb_image')
        .css('background-image','url(https://onrampremotelearning.net/wp-content/themes/Astra-Child/img/asynchronously.png)');
    } else if (value == 25596) {
        $j('.content-type_breadcrumb').find('h3').html('synchronously');
        $j('.content-type_breadcrumb').find('.breadcrumb_image')
        .css('background-image','url(https://onrampremotelearning.net/wp-content/themes/Astra-Child/img/synchronously.png)');
    }
    
}


