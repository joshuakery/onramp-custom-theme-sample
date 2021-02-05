$j(document).ready(function () {
    
    var w = $j(window);
    var element = $j('#wheel_decision_fork');
    element.show();
    
    var elementTop = element.offset().top,
        elementHeight = element.height(),
        viewportHeight = w.height(),
        scrollIt = elementTop - ((viewportHeight - elementHeight) / 2);
    $j("html, body").scrollTop(scrollIt);
    
});