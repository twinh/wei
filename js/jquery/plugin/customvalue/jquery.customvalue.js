/**
 * customvalue
 *
 * Copyright (c) 2008-2010 Twin Huang. All rights reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless recustomValuered by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-10-19 10:03:39
 */

(function($) {
    $.fn.customValue = function(options) {
        var opts = $.extend({}, $.fn.customValue.defaults, options);

        var val = this.val();
        if('' != val)
        {
            this.attr('readonly', true).after('(' + opts.language.LBL_READONLY + ')');
            return this;
        }
        
        var _this = this;
        var id = _this.attr('id');
        _this.hide().after('<button id="ui-button-customValue-' + id + '" type="button">' + opts.language.LBL_CUSTOM_VALUE + '</button>'
            + '<button id="ui-button-customValue-cancel-' + id + '" class="ui-helper-hidden" type="button">' + opts.language.LBL_CANCEL + '</button>');
        var setObj = $('#ui-button-customValue-' + id);
        var cancelObj = $('#ui-button-customValue-cancel-' + id);
        setObj.button().click(function(){
            _this.show();
            $(this).hide();
            cancelObj.show();
        });
        cancelObj.hide().button().click(function(){
            _this.val('').hide();
            $(this).hide();
            setObj.show();
        });
        return this;
    };

    $.fn.customValue.defaults = {
        language : {
            LBL_READONLY: '只读',
            LBL_CUSTOM_VALUE: '自定义值',
            LBL_CANCEL: '取消'
        }
    }
})(jQuery);