(function($) { jQuery.fn.mytoggle = function(collapsed){
        
        $(this).click(function() {
                $(this).parent().find('div').slideToggle('fast', $(this).parent().toggleClass('collapsed'));
                $(this).toggleClass('up').toggleClass('down');
        });
        
        if (collapsed) { $(this).click(); }
        
    };
})(jQuery);
