/*
 * jquery.ui.tip 0.9
 *
 * Copyright (c) 2010 Twin
 *
 * 2010-02-05 - 2010-02-06
 * TODO 箭头的大小,位置, 提示框位置, zIndex
 */
(function($){
$.widget('ui.tip', {
    _init: function() {
        var self = this;
        if(false == self.options.object)
        {
            self.options.objct = self.element;
        } else if('string' == typeof(self.options.object)) {
            self.options.object = $(self.options.object);
        }
        var obj = self.options.object;
        var tipOffset = obj.offset();
        tip = $('<div></div>');
        tip
            .attr('class', 'ui-widget ui-form-tip')
            .css({
                left : tipOffset.left + obj.width() + 2,
                top : tipOffset.top,
                zIndex : self.options.zIndex,
                height : self.options.height,
                width : self.options.width
            })
        self._tip = tip;
        self.loadData();
        self._isOpen = false;
        (self.options.autoOpen && self.open());
        return self._tip;
    },
    _fixedPosition: function()
    {

    },
    open: function()
    {
        if(this._isOpen){ return; }
        // 显示提示内容
        this._tip.show(this.options.show);
        this._isOpen = true;
        this._trigger('open');
    },
    close: function(event) {
        if (false === this._trigger('beforeClose', event)) {
            return;
        }
        if(true == this.options.fadeOut)
        {
            this._tip.fadeOut('slow');
        } else {
            this._tip.hide(this.options.hide);
        }
        this._isOpen = false;
    },
    toggle: function(event){
        if(this._isOpen)
        {
            this.close(event);
        } else {
            this.open();
        }
    },
    isOpen: function(){
        return this._isOpen;
    },
    reLoadData: function(){
        this.loadData();

    },
    loadData: function(){
        var self = this;
        self.element.hide();
        self._tip
            .html(self._getTipHtml())
            .hide()
            .find('.ui-form-tip-arrow').css('zIndex', tip.find('.ui-form-tip-content').css('zIndex') + 1);
        self._tip
            .find('.ui-form-tip-content').css('width', parseInt(self.options.width) - 16);
        self._tip
            .find('.ui-form-tip-close-button')
            .click(function(event) {
                self.close(event);
                return false;
            })
        $('body').append(tip);
        //self.options.object.append(tip);
    },
    _getTipHtml: function(){
        // 箭头
        var options = this.options,
            html = '<div class="ui-form-tip-arrow">';
        for(var i = 1; i <= 10; i++)
        {
            html += '<div class="arrow-line-' + i + ' ' + options.bgClass + '"><!-- --></div>';
        }
        for(var i = 9; i >= 1; i--)
        {
            html += '<div class="arrow-line-' + i + ' ' + options.bgClass + '"><!-- --></div>';
        }
        // 内容
        html += '</div><div class="ui-form-tip-content ' + options.bgClass + ' ui-corner-all">';
        html += '<span class="ui-icon ui-icon-close ui-form-tip-close-button"></span>'
        html += this.element.html();
        html += '</div>';
        // 清空,防止id重复?
        this.element.html('');
        return html;
    },
    destroy: function() {
        $.widget.prototype.destroy.apply(this, arguments);
        this._tip.remove();
    }
});
$.extend($.ui.tip, {
    defaults: {
        autoOpen: false,
        position: 'right, top',
        width: '200px',
        height: 'auto',
        object: false,
        zIndex: '1000',
        //data: [{'icon' : 'ui-icon-info', 'data' : '请至少提供一条信息'}],
        bgClass: 'ui-state-highlight',
        show: null,
        hide: null,
        fadeOut: false
    },
    getter: 'isOpen'
});
})(jQuery);