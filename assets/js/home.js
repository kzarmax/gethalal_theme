
// Faq
jQuery( function( $ ) {
    function initFaqs(){
        var plusActions = $( '.faq-item-action-plus, .faq-item-action-minus' );

        for ( var i = 0, j = plusActions.length; i < j; i++ ) {
            plusActions[i].addEventListener(
                'click',
                function (e) {
                    var $thisbutton = $(this).parent().parent();
                    if($thisbutton.hasClass('expanded')){
                        $thisbutton.removeClass('expanded');
                    } else {
                        $thisbutton.addClass('expanded');
                    }
                }
            );
        }
    }

    function initBuggerMenu() {
        if($('.gethalal-app-menu')[0]) {
            $('.gethalal-app-menu')[0].addEventListener('click', function(e){
                var $thisbutton = $(this);
                if($thisbutton.hasClass('expanded')){
                    $thisbutton.removeClass('expanded');
                } else {
                    $thisbutton.addClass('expanded');
                }
            });
        }
        if($('.language-container-app')[0]) {
            $('.language-container-app')[0].addEventListener('click', function(e){
                var $thisbutton = $(this);
                if($thisbutton.hasClass('expanded')){
                    $thisbutton.removeClass('expanded');
                } else {
                    $thisbutton.addClass('expanded');
                }
            });
        }
    }

    ( function() {
        initFaqs();
        initBuggerMenu();
    }());
});
