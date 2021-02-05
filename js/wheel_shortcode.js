var $j = jQuery.noConflict();
let selected = "no-selection";
let hovered = "no-selection";
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

    $j('.home_wheel_more_item').hide();

});

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

function chooseWheelCat(data) {
    data.event.preventDefault();
    $j(data.element).blur();
    if (!data.onMobile &&
        (window.matchMedia("(max-width: 500px)").matches ||
         !window.matchMedia('(hover: hover)').matches
        )
       ) {
        return;
    }
    
    setHiddenInput(data.event, data.hInput.name, data.hInput.value);
    //breadcrumbs
    setWheelBreadcrumb(data.hInput.name, data.hInput.value);
    setWheelCatStyles(data.hInput.name, data.hInput.value);
    //mobile
    // orcMobileShowLess();
    
    if  ('scroll' in data) {
        if (window.matchMedia("(max-width: 500px)").matches) {
            setTimeout(function() {
                scrollToTop(data.scroll.target,data.scroll.time);
            }, 200);
        } else {
            scrollToCenterInViewport(data.scroll.target,data.scroll.time);
        }
    }
                
}

function setWheelCatStyles(name,value) {
    console.log('selecting ' + value + ' for ' + name);
    
    //deselect the selected category
    $j('input[name=' + name + ']').parent().removeClass('wheel_slice_checked');
    $j('.overview').hide();
    
    //select the new category
    $j('input[name=' + name + ']').each(function() {
        if ($j(this).val() == value) {
            $j(this).parent().addClass('wheel_slice_checked');
        }
    });
    
    if (selected == "no-selection") {
        $j('#overview_' + value).fadeIn(200);
    } else {
        $j('#overview_' + value).show();
    }
    
    selected = value;
}

function setWheelBreadcrumb(name, value) {
    $j('.wheel_cat_breadcrumb').find('.breadcrumb_image')
     .css('background-image','url(https://onrampremotelearning.net/wp-content/themes/Astra-Child/img/circle-'+value+'.png)');
    $j('.wheel_cat_breadcrumb').find('.breadcrumb_image').parent().addClass('breadcrumb_followed');
    
    for (const property in colorsDict) {
        if (colorsDict[property] == value) {
            $j('.wheel_cat_breadcrumb').find('h3').html(property);
        }
    }
     
    $j('.content-type_breadcrumb').removeClass('hide');
    
}

var filtersInfoHeight;
function showPrinciple() {
    $j('.home_wheel_background').find('img').fadeIn();
    $j('.filters_info').children().fadeIn();
}

function hidePrinciple() {
    $j('.home_wheel_background').find('img').hide();
    
    if (!filtersInfoHeight) {
        filtersInfoHeight = $j('.filters_info').height();
        $j('.filters_info').css('height',filtersInfoHeight);
    }
    $j('.filters_info').children().hide();
}

function orcMobileToggleShow(element) {
    let container = $j(element).closest('.home_wheel_slice').find('.home_wheel_slice_contents');
    console.log(container);
    let more = $j(container).find('.home_wheel_more_item');
    if ($j(more).first().is(':visible')) {
        //show less
        console.log('show less');
        orcMobileShowLess(container);
    } else {
        //show more
        console.log('show more');
        orcMobileShowMore(container);
    }
}

function orcMobileShowMore(btn) {
    console.log('showing...');
    //animate height growing
    //first get the height it will be:
    $j(btn).find('.home_wheel_more_item').show();
    var h = $j(btn).outerHeight();
    $j(btn).find('.home_wheel_more_item').hide();
    
    //then animate:
    $j(btn).animate({
        height: h
    },500, function() {
        $j(btn).find('.home_wheel_more_item').fadeIn();
        $j(btn).find('.home_wheel_check_confirmation').fadeIn();
        $j(btn).css('height','auto');
    });
    //hide the 'show more' button
    $j(btn).find('.home_wheel_show_more').fadeOut(200);
    $j(btn).find('.home_wheel_check_confirmation').fadeOut(200);
    
}

function orcMobileShowLess(btn) {
    
    //animate height shrinking
    //first get the height it will be:
    $j(btn).find('.home_wheel_more_item').hide();
    $j(btn).find('.home_wheel_show_more').show();
    var nh = $j(btn).outerHeight();
    $j(btn).find('.home_wheel_show_more').hide();
    $j(btn).find('.home_wheel_more_item').show();
    
    //then animate:
    var h = $j(btn).outerHeight();
    $j(btn).css('height', h);
    $j(btn).find('.home_wheel_more_item').fadeOut(200);

    $j(btn).find('.home_wheel_check_confirmation').fadeOut(200);
    setTimeout(function() {
        $j(btn).animate({
            height: nh
        },500, function() {
            $j(btn).find('.home_wheel_show_more').fadeIn();
            $j(btn).find('.home_wheel_check_confirmation').fadeIn();
            $j(btn).css('height','auto');
        });
    },200);
    
}