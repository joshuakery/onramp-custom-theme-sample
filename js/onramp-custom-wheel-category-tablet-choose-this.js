var $j = jQuery.noConflict();

$j(document).ready(function () {
    
    //prep overview
    $j('.tablet_choose_this').removeClass('hide');
    let h = $j('.tablet_choose_this').first().height();
    $j('.tablet_choose_this_container').height(h);
    $j('.tablet_choose_this').hide();
    
    //tablet click states
    if (!window.matchMedia('(hover: hover)').matches) {
        $j('.home_wheel_slice_contents').click(
            function() {
                let cat = $j(this).find('input').val();
                if (selected != "no-selection") {
                    $j('.tablet_choose_this').hide();
                    if (cat == selected) {
                        $j('#tablet_choose_this_' + cat).show();
                    } else {
                        if (hovered == "no-selection") {
                            $j('#tablet_choose_this_' + cat).show();
                        } else {
                            $j('#tablet_choose_this_' + cat).fadeIn();
                        }
                    }
                } else {
                    $j('.tablet_choose_this').hide();
                    $j('#tablet_choose_this_' + cat).show();
                }
                hovered = cat;
            }
        );
    }
});
