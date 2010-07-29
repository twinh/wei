/*
2010 02 10 
QUI is short for Quick UI
*/
(function($) {
    $.fn.qui = function(options) {
        var opts = $.extend({}, $.fn.qui.defaults, options);

        if(opts.hover)
        {
            this.hover(
                function(){ $(this).addClass("ui-state-hover"); },
                function(){ $(this).removeClass("ui-state-hover");
            })
        }

        if(opts.click)
        {
            this.mousedown(function(){
                $(this).addClass("ui-state-active");
            }).mouseup(function(){
                $(this).removeClass("ui-state-active");
            });
        }

        if(opts.focus)
        {
            this.focus(function(){
                $(this).addClass("ui-state-active");
            }).blur(function(){
                $(this).removeClass("ui-state-active");
            });
        }
        return this;
    };

    $.fn.qui.defaults = {
        hover : true,
        click : false,
        focus : false
    }
})(jQuery);