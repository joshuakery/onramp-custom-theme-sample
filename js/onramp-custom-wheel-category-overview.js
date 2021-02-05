var $j = jQuery.noConflict();

$j(document).ready(function () {
    
    //prep overview
    $j('.overview').removeClass('hide');
    let greatest = 0;
    $j('.overview').each(function() {
        let h = $j(this).outerHeight();
        if (h > greatest) {
            greatest = h;
        }
    });
    $j('.overview_container').height(greatest);
    $j('.overview').hide();
    
    //hover states
    $j('.home_wheel_slice_contents').hover(
        function() {
            let cat = $j(this).find('input').val();
            if (selected != "no-selection") {
                $j('.overview').hide();
                if (cat == selected) {
                    $j('#overview_' + cat).show();
                } else {
                    if (hovered == "no-selection") {
                        $j('#overview_' + cat).show();
                    } else {
                        $j('#overview_' + cat).fadeIn();
                    }
                }
            } else {
                $j('.overview').hide();
                $j('#overview_' + cat).show();
            }
            hovered = cat;
        },
        function() {
            let cat = $j(this).find('input').val();
            $j('.overview').hide();
            if (selected != "no-selection") {
                $j('#overview_' + selected).show();
            }
        }
    );

});