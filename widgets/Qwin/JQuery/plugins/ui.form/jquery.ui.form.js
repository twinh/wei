/**
 * Qwin Framework
 *
 * Copyright (c) 2008-2011 Twin Huang. All rights reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 */

/**
 * jquery
 * 
 * @namespace   Qwin
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2011-10-18 15:33:15
 */
(function($) {
    $.fn.form = function(options) {
        options = $.extend({}, $.fn.form.defaults, options);
        var form = $(this);
        var html = '';
        
        var width = $(this).width();
        // 创建表单标题
        if (options.title) {
            var header = $('<div class="qw-form-header ui-corner-top ui-widget-header">' + options.title + '</div>');
            // mark resize
            header.outerWidth(width);
            form.append(header);
        }

        // 创建表单内容
        var body = $('<div class="qw-form-body"></div>');
        // mark resize
        body.outerWidth(width);
        form.append(body);
        
        renderElems(options.elems, body.width(), true);
        
        function renderElems(elems, totalWidth, first)
        {
            var width,
                lastWidth,
                cellWidth,
                i = 1,
                elemsLength = elems.length;
            
            if (!first) {
                width = totalWidth / elemsLength;
                if (isFloat(width)) {
                    width = parseInt(width);
                    lastWidth = width + (totalWidth % elemsLength);
                } else {
                    lastWidth = width;
                }
            } else {
                width = totalWidth;
                lastWidth = width;
            }
            
            for (var item in elems) {
                if ('object' == typeof elems[item]['elems']) {
                    renderElems(elems[item]['elems'], width, false);
                } else {
                    if (i != elemsLength) {
                        cellWidth = width;
                    } else {
                        cellWidth = lastWidth;
                    }
                    var elemWidth = width - 75 - 8;
                    var cell = $('<div class="qw-form-cell"></div>').outerWidth(cellWidth);
                    body.append(cell);
                    
                    var label = $('<label class="qw-form-label" for="' + elems[item].name + '">' + elems[item].label + ':</label>');
                    cell.append(label);
                    
                    var inputWidth = cell.width() - label.outerWidth();
                    var input = $('<div class="qw-form-elem"><input class="qw-form-text ui-widget-content qw-corner-all" type="text" value="' + elems[item].name + '"/></div>');
                    input.outerWidth(inputWidth);
                    
                    cell.append(input);
                    input.find('input').outerWidth(inputWidth);
                    cell.append('<div class="qw-clear"></div>');
                    i++;
                }
                if (first) {
                    body.append('<div class="qw-clear"></div>');
                }
            }
        }

        // 创建表单底部按钮
        if (options.buttons) {
            var footer = $('<div class="qw-form-footer qw-clear"></div>');
            // mark resize
            footer.outerWidth(width);
            
            var button;
            for (var i in options.buttons) {
                button = '<button type="' + options.buttons[i].type + '" hidefocus="true" class="qw-button" data="{icons:{primary:\'' + options.buttons[i].icon + '\'}}">' + options.buttons[i].label + '</button>';
                button = $(button).button();
                footer.append(button);
            }
            form.append(footer);
        }
        
        /**
         * @see http://stackoverflow.com/questions/3885817/how-to-check-if-a-number-is-float-or-integer 
         */
        function isFloat (n) {
          return n===+n && n!==(n|0);
        }

        function isInteger (n) {
          return n===+n && n===(n|0);
        }

        
        return this;
    };

    $.fn.form.defaults = {
        title: null,
        fieldDefaults: {
            
        },
        lableDefaults: {
            
        },
        elems: [],
        buttons: []
    }
})(jQuery);