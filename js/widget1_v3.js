var $j = jQuery.noConflict();
var checked_wheel_cat;

var selected_wheel_cat;
var selected_color;

var colorsDict = {
    "Social & Emotional Learning" : "social-and-emotional-learning",
    "Connection to Families & Students" : "connection-to-families-and-students",
    "Student Engagement Aligned to Standards" : "student-engagement-aligned-to-standards",
    "Flexibility & Choice for Diverse Needs" : "flexibility-and-choice-for-diverse-needs",
    "Feedback on Student Work" : "feedback-on-student-work",
    "Collaboration Among Students" : "collaboration-among-students"
};

$j(document).ready(function () {
    
    prepDropdownsForStyling();
    prepSearchBar();
    setCSSVariables();
    $j(".orc_calendar_listings").hide();
    $j('.submit_container').hide();
    setClickEvents();
    prepEventListings();
    prepCourseListings();
    rotateWheel();
    
    //modal functionality - position depending on screen size
    $j('a[href="#login"]').click(function() {
        if (!window.matchMedia("(max-width: 500px)").matches) {
            let w = $j('.hfe-flyout-content').outerWidth();
            $j('.ld-modal').css('right',w+'px');
        }
    });
    //modal functionality - hide with flyout menu
    $j('.hfe-flyout-close').children().click(function() {
        $j('.learndash-wrapper-login-modal').removeClass('ld-modal-open');
        $j('.learndash-wrapper-login-modal').addClass('ld-modal-closed');
    });
    
    //Show Category Name of checked category
    let checked_elem = $j('input[type=radio][name="wheel_categories"]:checked');
    //style wheel slice:
    let slice = checked_elem.parent();
    slice.addClass('wheel_slice_checked');
    //show design principle name:
    $j(".design_principle_name").hide();
    let checked_id = "#design_principle_name_" + checked_elem.val();
    $j(checked_id).show();
    //hide description elements
    $j(".design_principle_description").hide();
    
    //if student-engagement-aligned-to-standards, show subjects dropdown:
    if(checked_elem.val() == "student-engagement-aligned-to-standards") {
        showSubjectsDropdown(checked_elem.val());
    }
    else {
        hideSubjectsDropdown();
    }
    
    $j('.back_to_home').hover(
        function() {
            $j('.wheel_background_container').addClass('back_to_home_up');
            $j('.wheel').addClass('back_to_home_up');
            $j('.wheel_center_container').addClass('back_to_home_up');
        },
        function() {
            $j('.wheel_background_container').removeClass('back_to_home_up');
            $j('.wheel').removeClass('back_to_home_up');
            $j('.wheel_center_container').removeClass('back_to_home_up');
        }
    );
        

    
    
	
});

var LAST_SEARCH = '';
function prepSearchBar() {
    $j('input[name="onramp-search"]').blur(function() {
        if ($j(this).val() != LAST_SEARCH) {
            requestContent();
            LAST_SEARCH = $j(this).val();
        }
    });
    $j('input[name="onramp-search"]').keypress(function(e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            requestContent(); 
            LAST_SEARCH = $j(this).val();
        }
    }); 
}
function searchContent(e) {
    e.preventDefault();
    requestContent(); 
    LAST_SEARCH = $j(this).val();
}

function prepCourseListings() {
    if ($j('.orc_course_grid').length > 0 && 
        $j('.orc_course_grid').find('.listing_container').length == 0) {
            //feedback there's no courses for these filters
            let feedback = document.createElement('DIV');
            $j(feedback).addClass('no_courses_message');
            $j(feedback).html('Looks like there are no courses based on your filters. Try seaching for something else!');
            $j('.orc_course_grid').append(feedback);
    }
}

function prepEventListings() {
    
    //hide the link texts, which come default from Events Manager Placeholder
    // $j(".event_link").children().html("");
    // $j(".event_cal_link").children().html("");
    
    //remove the links to event categories and tags
    // $j('.event-categories').children().each(function () {
    //     let text = $j(this).find('a').html();
    //     $j(this).find('a').remove();
    //     $j(this).html(text);
    // });
    
    //remove links from calendar day numbers

    $j('.eventful').each(function() {
        let num = $j(this).find('> a').html();
        $j(this).find('> a').remove();
        if (num) {
            $j(this).prepend('<p>' + num + '</p>');
        }
        
    });
    $j('.eventful-today').each(function() {
        let num = $j(this).find('> a').html();
        $j(this).find('> a').remove();
        if (num) {
             $j(this).prepend('<p>' + num + '</p>');
        }
    });
    $j('.eventful-post').each(function() {
        let num = $j(this).find('> a').html();
        $j(this).find('> a').remove();
        if (num) {
             $j(this).prepend('<p>' + num + '</p>');
        }
    });
    
    //crop event names that are too long
    if (window.matchMedia("(max-width: 500px)").matches) {
        let maxLen = 8;
        $j('.event_cal_title').each(function() {
            let title = $j(this).html();
            if (title.length > maxLen) {
                $j(this).html(title.slice(0,maxLen-3) + "...");
            }
        });
    } else if (window.matchMedia("(max-width: 1100px)").matches) {
        let maxLen = 14;
        $j('.event_cal_title').each(function() {
            let title = $j(this).html();
            if (title.length > maxLen) {
                $j(this).html(title.slice(0,maxLen-3) + "...");
            }
        });
    }
    
    //replace more... href element
    let mores = $j('.orc_calendar_body')
    .find('td')
    .find('li')
    .find('a');

    mores.each(function() {
        if ($j(this).html().startsWith('more...')) {
            //this is the element we want
            let link = $j(this).attr('href');
            if (link.includes('&limit=')) {
                let str = link;
                var i = str.indexOf("&limit=");
                var j = i + ("&limit=").length;
                var c = str[j];
                str = str.replace('&limit=' + c, '');
                link = str;
            }
            
            let parent = $j(this).parent();
            let grandparent = $j(parent).parent();
            $j(parent).remove();
            
            let replace = document.createElement('DIV');
            $j(grandparent).append(replace);
            
            if (window.matchMedia("(max-width: 500px)").matches) {
                $j(replace).html('...');
            } else {
                $j(replace).html('more...');
            }
            $j(replace).addClass('orc-calendar-more-button');
            $j(replace).click(function() {
                window.location = link;
            });
        }
    })
    
    //add colors
    $j('.event_container').each(function() {
        let container = $j(this);
        let classes = [];
        $j(this).find('.event_category').each(function() {
            var classList = $j(this).attr("class");
            var classArr = classList.split(/\s+/);
            if (classArr.length > 0) {
                classArr.forEach(function(c) {
                    if (c.startsWith('wheel_color_border')) {
                        classes.push(c);
                    }
                });
            }
        });
        classes.forEach(function(c) {
            container.addClass(c);
            container.find('.event_datetimes').addClass(c);
            container.find('.event_zoom_link').addClass(c);
        });
    });
    $j('.event_cal_container').each(function() {
        let container = $j(this);
        let classes = [];
        $j(this).find('.event_category').each(function() {
            var classList = $j(this).attr("class");
            var classArr = classList.split(/\s+/);
            if (classArr.length > 0) {
                classArr.forEach(function(c) {
                    if (c.startsWith('wheel_color_border')) {
                        classes.push(c);
                    }
                });
            }
        });
        classes.forEach(function(c) {
            container.addClass(c);
            container.find('.event_cal_time').addClass(c);
        });
    });

}

function requestContent(tabID = undefined,
                        wheel_cat = undefined,
                        grade_band = undefined,
                        search_term = undefined) {
    //query params
    if (tabID == undefined) {
        tabID = $j('input[name="content-type"]:checked').val();
    }
    if (wheel_cat == undefined) {
        wheel_cat = $j('select[name="wheel_categories"]').val();
    }
    if (grade_band == undefined) {
        grade_band = $j('select[name="grade_band"]').val();
    }
    if (search_term == undefined) {
        search_term = $j('input[name="onramp-search"]').val();
    }

    //styling
    $j("html, body").animate({ scrollTop: 0 }, "slow");
    $j('#orc_content_container').empty();
    $j('#orc_content_container').html('<h3 id="loader">Loading...</h3>');
    $j('#orc_content_container').removeClass();
    $j('#orc_content_container').addClass('orc_content_container');
    $j('#orc_content_container').addClass('orc_' + tabID + '_container');
    //ajax
    $j.ajax({
        type: 'POST',
        url: myAjax.ajaxurl,
        data: {
            'action': 'orc_request_content',
            'content-type': tabID,
            'wheel_cat' : wheel_cat,
            'grade_band' : grade_band,
            'search_term' : search_term,
            
        },
        success: function (result) {
            $j('#orc_content_container').empty();
            $j('#orc_content_container').html(result.data);
            // toggleCalendarListings();
            
            //bug?
            //ajax hrefs are incorrect after being loaded
            //wp-admin/admin-ajax.php
            //needs to be replaced by
            //all-courses/
            $j('.em-calnav').each(function() {
                var href = $j(this).attr('href');
                var toReplace = '/wp-admin/admin-ajax.php';
                var replaceWith = '/all-courses/';
                if (href.includes('/wp-admin/admin-ajax.php')) {
                    var end = href.substr(toReplace.length);
                    href = replaceWith + end;
                    $j(this).attr('href', href);
                }
            });
            //rerun onclick ajax from events manager js
        	$j('.em-calendar-wrapper a').off("click");
        	$j('.em-calendar-wrapper').on('click', 'a.em-calnav, a.em-calnav', function(e){
        		e.preventDefault();
        		$j(this).closest('.em-calendar-wrapper').prepend('<div class="loading" id="em-loading"></div>');
        		var url = em_ajaxify($j(this).attr('href'));
        		$j(this).closest('.em-calendar-wrapper').load(url, function(){$j(this).trigger('em_calendar_load');});
        	} );
        	//tidy up as before
        	prepEventListings();
        	prepCourseListings();

        },
        error: function () {
            console.log("error");
        }
    });
}

//----------------------------onclicks----------------------------
function setClickEvents() {
    $j(document).ajaxComplete( function() {
        prepEventListings();
    });
    
    window.addEventListener('beforeunload', (event) => {
        showLoader();
    });
    
    $j('.back_to_home').click(function() {
        $j('.wheel_container').addClass('back_to_home_up_and'); 
        $j('.wheel_container').addClass('back_to_home_away');
    });

}

function showLoader() {
    
    if ($j('.loader').length == 0) {
        let l = document.createElement('DIV');
        $j(l).addClass('loader');
        $j(l).addClass('hide');
        let h3 = document.createElement('H3');
        $j(h3).html('loading...');
        l.append(h3);
        $j('body').append(l);
    }
    
    if ($j('.wheel_container').length > 0) {
        $j('.wheel_container').fadeOut();
        setTimeout(function() {
            revealLoader();
        }, 250);
    } else {
        revealLoader();
    }

}

function revealLoader() {
    if ($j('.loader').hasClass('hide')) {
        $j('.loader').hide();
        $j('.loader').removeClass('hide');
        $j('.loader').fadeIn();
    }
}

function seeContent(e, tabID) {
    if (e) e.preventDefault();
    $j('input[name="content-type"]').prop("checked", false);
    $j('input[name="content-type"][value="'+tabID+'"]').prop("checked", true);
    requestContent(tabID);
    
}

function submitBackToHomeForm(e) {
    e.preventDefault();
    $j('#back_to_home_form').submit();
}
   

function advanceToNext(elementID) {
    var element = $j('#' + elementID);
    if (element.length > 0) {
        element.show();
    }
}

function toggleCalendarListings() {
    if ( $j('#cal_toggle_list').is(':visible') ){
        //hide the calendar, show the list
        // console.log('hiding calendar');
        $j('#cal_toggle_cal').show();
        $j(".orc_calendar_listings").hide();
        $j('#cal_toggle_list').hide();
        $j(".orc_event_listings").show();
    } else {
        // console.log('showing calendar');
        $j('#cal_toggle_cal').hide();
        $j(".orc_calendar_listings").show();
        $j('#cal_toggle_list').show();
        $j(".orc_event_listings").hide();
    }
}

function setCSSVariables() {
    selected_wheel_cat = $j('.wheel_select').children('option:selected').val();
    setDesignPrinciple(selected_wheel_cat);
}

function getWheelColor(cat) {
    if (cat == "student-engagement-aligned-to-standards") {
        return "rgb(111,125,28)";
    } else if (cat == "social-and-emotional-learning") {
        return "rgb(204,0,0)";
    } else if (cat == "flexibility-and-choice-for-diverse-needs") {
        return "rgb(66,126,147)";
    } else if (cat == "connection-to-families-and-students") {
        return "rgb(237,171,24)";
    } else if (cat == "feedback-on-student-work") {
        return "rgb(65,86,161)";
    } else if (cat == "collaboration-among-students") {
        return "rgb(102,102,102)";
    } else {
        return "#2d2d2d";
    }
}

function setDesignPrinciple(slug) {
    selected_color = getWheelColor(slug);
    document.documentElement.style
    .setProperty('--selected-color', selected_color);
}

function showSubjectsDropdown(wheel_cat) {
    $j(".subjects_select_container").removeClass("hide");
    $j(".subjects_select_container").find(".select-selected").addClass("wheel_color_" + wheel_cat);
    $j(".subjects_select_container").find(".select-items").addClass("wheel_color_" + wheel_cat);
    $j(".subjects_unselectable").hide();
}

function hideSubjectsDropdown() {
    $j(".subjects_select_container").addClass("hide");
    $j(".subjects_unselectable").show();
}

var rotation;
function rotateWheel() {
    if (rotation == null) rotation = 0;
    
    var cat = $j('select[name="wheel_categories"]').val();
    var wheel = $j('.wheel');
    
    var target = rotation;
    if        (cat == "student-engagement-aligned-to-standards") {
        target = 60;
    } else if (cat == "connection-to-families-and-students") {
        target = 120;
    } else if (cat == "social-and-emotional-learning") {
        target = 180;
    } else if (cat == "collaboration-among-students") {
        target = 240;
    } else if (cat == "feedback-on-student-work") {
        target = 300;
    } else if (cat == "flexibility-and-choice-for-diverse-needs") {
        target = 0;
    }
    
    wheel.css('transition','transform 1s');
    if(rotation < target) {
        if(Math.abs(rotation - target) > 180) {
            wheel.css('transform','rotate('+(target - 360)+'deg)');
        } else {
            wheel.css('transform','rotate('+target+'deg)');
        }
    } else {
        if(Math.abs(rotation - target) > 180) {
            wheel.css('transform','rotate('+(target + 360)+'deg)');
        }
        else {
            wheel.css('transform','rotate('+target+'deg)');
        }
    }
    rotation = target;
    setTimeout(function() {
        wheel.css('transition','transform 0s');
        wheel.css('transform','rotate('+rotation+'deg)');
    }, 1000);
}

//https://www.w3schools.com/howto/tryit.asp?filename=tryhow_custom_select
function prepDropdownsForStyling() {
    var x, i, j, l, ll, selElmnt, a, b, c;
    /*look for any elements with the class "custom-select":*/
    x = document.getElementsByClassName("custom-select");
    l = x.length;
    for (i = 0; i < l; i++) {
        
      selElmnt = x[i].getElementsByTagName("select")[0];
      ll = selElmnt.length;
      /*for each element, create a new DIV that will act as the selected item:*/
      a = document.createElement("DIV");
      a.setAttribute("class", "select-selected");
      a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
      x[i].appendChild(a);
      
      /*for each element, create a new DIV that will contain the option list:*/
      b = document.createElement("DIV");
      b.setAttribute("class", "select-items select-hide");
      for (j = 0; j < ll; j++) {
          
        /*for each option in the original select element,
        create a new DIV that will act as an option item:*/
        c = document.createElement("DIV");
        c.innerHTML = selElmnt.options[j].innerHTML;
        giveClasses(selElmnt.options[j], c);
        
        c.addEventListener("click", function(e) {
            /*when an item is clicked, update the original select box,
            and the selected item:*/
            var y, i, k, s, h, sl, yl;
            s = this.parentNode.parentNode.getElementsByTagName("select")[0];
            sl = s.length;
            h = this.parentNode.previousSibling;
            for (i = 0; i < sl; i++) {
              if (s.options[i].innerHTML == this.innerHTML) {
                s.selectedIndex = i;
                h.innerHTML = this.innerHTML;
                if ($j(s.options[i]).hasClass("wheel_option")) {
                    setDesignPrinciple(s.options[i].value);
                }
                y = this.parentNode.getElementsByClassName("same-as-selected");
                yl = y.length;
                for (k = 0; k < yl; k++) {
                    $j(y[k]).removeClass("same-as-selected");
                }
                $j(this).addClass("same-as-selected");
              }
            }
            h.click();
            //click event: ajax request
            var optionID = $j('.content_type_select').children("option:selected").val()
            if (s.name == "wheel_categories") {
                rotateWheel();
                requestContent(undefined, s.value);
            } else if (s.name == "grade_band") {
                requestContent(undefined, undefined, s.value);
            } else if (s.name == "content-type") {
                seeContent(undefined, optionID);
            }
        });
        
        
        b.appendChild(c);
      }
      
      x[i].appendChild(b);
      a.addEventListener("click", function(e) {
          /*when the select box is clicked, close any other select boxes,
          and open/close the current select box:*/
          e.stopPropagation();
          closeAllSelect(this);
          this.nextSibling.classList.toggle("select-hide");
          this.classList.toggle("select-arrow-active");
        });
    }
}
function closeAllSelect(elmnt) {
  /*a function that will close all select boxes in the document,
  except the current select box:*/
  var x, y, i, xl, yl, arrNo = [];
  x = document.getElementsByClassName("select-items");
  y = document.getElementsByClassName("select-selected");
  xl = x.length;
  yl = y.length;
  for (i = 0; i < yl; i++) {
    if (elmnt == y[i]) {
      arrNo.push(i)
    } else {
      y[i].classList.remove("select-arrow-active");
    }
  }
  for (i = 0; i < xl; i++) {
    if (arrNo.indexOf(i)) {
      x[i].classList.add("select-hide");
    }
  }
}
function giveClasses(a,b) {
    var classes = a.classList;
    for (var i = 0; i < classes.length; i++) {
        b.classList.add(classes[i]);
    }
}
/*if the user clicks anywhere outside the select box,
then close all select boxes:*/
document.addEventListener("click", closeAllSelect);