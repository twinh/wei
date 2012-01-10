/*
 * jQuery UI Tabs Ext
 *
 * Depends:
 *	jquery.tabs.js
 *	
 * @todo see Rotate
 */
(function( $, undefined ) {
    /**
     * @todo
     * 1. scrollable or fit
     */
    var _tabifyOrig = $.ui.tabs.prototype._tabify;
    $.extend($.ui.tabs.prototype.options, {
        closable: false,
        closeTemplate: '<span class="ui-icon ui-icon-close">Remove Tab</span>'
    });
    $.extend($.ui.tabs.prototype, {
        _tabify: function(init) {
            _tabifyOrig.apply(this, arguments);
    
            var self = this, o = this.options;
            if (o.closable) {
                var lis = this.element.find('ul.ui-tabs-nav li');
                
                lis.not(':has(span.ui-icon-close)').each(function(){
                    $(o.closeTemplate).appendTo(this)
                        .click(function(){
                            self.remove(lis.index(this.parentNode));
                        });
                });
            }
        },
        /**
         * @todo ui-tabs-iframe
         */
        addIframe: function(url, title){
            var self = this,
                id = self._tabId(title),
                foundIframe = false;
            
            // 检查是否有该url的iframe
            self.element.find('iframe').each(function(){
                if ($(this).attr('src') == url) {
                    foundIframe = true;
                    self.select($(this).parent().attr('id'));
                    return false;
                }
            });
            if (foundIframe) {
                return this;
            }
            
            // add iframe
            self.element.append('<div id="' + id + '"><iframe class="ui-tabs-iframe" src="' + url + '"></iframe></div>');
            self.add('#' + id, title);
            
            // selete tab
            self.select(id);
            
            return this;
        }
    });
})( jQuery );