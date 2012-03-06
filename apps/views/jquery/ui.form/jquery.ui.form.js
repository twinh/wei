/**
 * Qwin Framework
 *
 * Copyright (c) 2008-2012 Twin Huang. All rights reserved.
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
 * ui.form
 *
 * @namespace   Qwin
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2011-10-18 15:33:15
 */
(function($) {
    $.fn.form = function(options) {
        // todo each
        if (!this.length || 'FORM' != this[0].tagName.toUpperCase()) {
            alert('element tag should be form');
            return this;
        }

        options = $.extend({}, $.form.defaults, options);
        options.labelDefaults = $.extend({}, $.form.labelDefaults, options.labelDefaults);
        options.fieldDefaults = $.extend({}, $.form.fieldDefaults, options.fieldDefaults);

        var form = $(this);
        form.options = options;
        form.addClass('ui-form ' + options.classes);
        form.css(options.style);

        var width;
        if (options.autoWidth) {
            width = form.parent().width();
        } else if (options.width) {
            width = options.width;
        } else {
            width = form.width();
        }
        form.fullWidth(width);
        width = form.width();

        // render form header
        if (options.title) {
            var header = $('<div class="ui-form-header ui-corner-top ui-widget-header">' + options.title + '</div>');
            header.appendTo(form).fullWidth(width);
        }

        var body = $('<div class="ui-form-body"></div>');
        body.appendTo(form).fullWidth(width);

        // step 1, create the layout
        var rows = {};
        $.form.renderLayout(options.fields, true, body, rows, 0, 1, form);

        // step 2, render fields
        $.form.renderFields(rows, form);

        // step 3 bind events
        // todo ...

        // render footer buttons
        if (options.buttons) {
            var footer = $('<div class="ui-form-footer ui-clear"></div>');
            footer.appendTo(form).fullWidth(width);

            var button;
            $.each(options.buttons, function(name, options) {
                options = $.extend({}, $.form.buttonDefaults, options);
                button = '<button type="' + options.type + '" hidefocus="true" class="qw-button" data="{icons:{primary:\'' + options.icon + '\'}}">' + options.label + '</button>';
                button = $(button).button();
                footer.append(button);

                if (options.click) {
                    button.click(function() {
                        options.click();
                    });
                }
            });
        }

        if (options.afterRendered) {
            options.afterRendered();
        }

        $('input.ui-form-text, textarea.ui-form-textarea, legend .ui-form-legend-toggle-icon').qui();

        return this;
    };

    $.form = {};
    $.extend($.form, {
        defaults: {
            title: null,
            width: null,
            autoWidth: false,
            data: {},
            style: {},
            classes: 'ui-widget ui-widget-content ui-corner-all',
            fieldDefaults: {},
            labelDefaults: {},
            fieldSetDefaults: {},
            afterRendered: null,
            fields: [],
            buttons: [{
                label: 'Submit',
                type: 'submit',
                icon: 'ui-icon-check'
            },{
                label: 'Reset',
                type: 'reset',
                icon: ''
            }]
        },
        fieldDefaults: {
            label: null,
            type: 'text',
            value: '',
            name: '',
            width: null,
            readonly: false,
            required: false,
            disabled: false,
            formatter: null,
            buttons: [],
            style: [],
            render: null
        },
        labelDefaults: {
            title: null,
            target: null,
            align: 'right',
            hidden: false,
            width: 75,
            separator: ':'
        },
        buttonDefaults: {
            icon: '',
            label: '',
            type: 'button'
        },
        fieldSetDefaults: {
            collapsed: false,
            collapsible: true,
            title: '',
            label: false,
            checkbox: [],
            fields: [],
            labelDefaults: {}
        },
        // TODO checkboxGroup, radioGroup, tabs, accordion ?
        containers: {
            fieldSet: true,
            fieldGroup: true
        },
        renderLayout: function(fields, first, body, rows, fieldNum, rowNum, form) {
            /*if (!first) {
                var row = $('<div class="ui-form-row"></div>');
                body.append(row);
                body = row;
            }*/

            var options;
            for (var field in fields) {
                options = fields[field];
                // container should create fields by itself
                if ('object' == typeof options['fields'] && undefined == $.form.containers[options.type]) {
                    $.form.renderLayout(options['fields'], false, body, rows, fieldNum, rowNum, form);
                } else {
                    if ('string' == typeof options) {
                        options = {
                            type: 'label',
                            value: options
                        };
                    } else if (undefined == options.type) {
                        options.type = 'text';
                    }

                    var cell = $('<div class="ui-form-cell"></div>');
                    body.append(cell);

                    // create label
                    if (false !== options.label) {
                        $.form.renderLabel(options, cell, form);
                    }

                    // create empty field
                    var emptyField = $('<div class="ui-form-field"></div>');
                    cell.append(emptyField);
                    if ('label' == options.type) {
                        $.form.types.label(options, emptyField, emptyField.fullWidth());
                    }

                    // created button
                    if (undefined == $.form.containers[options.type] && options.buttons) {
                        $.form.renderButtons(options.buttons, cell);
                    }

                    if ('hidden' == options.type) {
                        cell.css({
                            display: 'none',
                            width: 0
                        });
                    }

                    // remember the field and options
                    if ('undefined' == typeof rows[rowNum]) {
                        rows[rowNum] = {};
                        rows[rowNum]['fields'] = {};
                    }
                    rows[rowNum]['fields'][fieldNum] = {
                        field: emptyField,
                        options: options,
                        parentOptions : fields,
                        width: cell.fullWidth()
                    };
                    fieldNum++;
                }
                if (first) {
                    // 统计一行中有多少空白可用,记录每个空白
                    rows[rowNum]['total'] = body.width();
                    rowNum++;
                    fieldNum = 0;
                    body.append('<div class="ui-clear"></div>');
                }
            }
        },
        renderFields: function(rows, form) {
            for (var i in rows) {
                var avgWidth = 0,
                    fieldsCount = 0,
                    total = rows[i]['total'];
                for (var j in rows[i]['fields']) {
                    total -=  rows[i]['fields'][j]['width'];
                    if ('label' == rows[i]['fields'][j]['options']['type'] || 'hidden' == rows[i]['fields'][j]['options']['type']) {
                        continue;
                    }
                    if (rows[i]['fields'][j]['options']['width']) {
                        total -= rows[i]['fields'][j]['options']['width'];
                    } else {
                        fieldsCount++;
                    }
                }

                avgWidth = total/fieldsCount;

                for (var k in rows[i]['fields']) {
                    var options = $.extend({}, $.form.fieldDefaults, rows[i]['fields'][k]['options']);
                    var field = rows[i]['fields'][k]['field'];

                    if (form.options.data && form.options.data[options.name]) {
                        options.value = form.options.data[options.name];
                    }

                    if (options.formatter) {
                        options.value = options.formatter(options.value);
                    }

                    if ('label' == options.type) {
                        continue;
                    }

                    if (!options.width) {
                        field.fullWidth(avgWidth);
                    }

                    if ($.form.types[options.type]) {
                        $.form.types[options.type](options, field, avgWidth, form, rows[i]['fields'][k]['parentOptions']);
                    } else {
                        alert('Undefined field type: ' + options.type);
                        return;
                    }
                }
            }
        },
        renderLabel: function(options, cell, form) {
            if ('label' == options.type) {
                return;
            }

            // label html code
            var html = '';

            // get label title
            if (null == options.label) {
                options.label = {
                    title: options.name
                };
            } else if ('string' == typeof options.label) {
                options.label = {
                    title: options.label
                };
            }

            // _this.options.labelDefaults
            var label = $.extend({}, form.options.labelDefaults, options.label);

            if (!label.target) {
                label.target = options.name;
            }
            html += '<label class="ui-form-label" for="' + label.target + '">';

            if (options.required) {
                html += '<span class="ui-form-label-required">*</span>';
            }

            html += label.title + label.separator + '</label>';

            html = $(html).fullWidth(label.width);

            cell.append(html);
        },
        renderButtons: function(buttons, cell) {
            if (!buttons.length) {
                return;
            }
            var button = '<div class="ui-form-field-buttons">';
            for (var i in buttons) {
                button += '<div class="ui-form-field-button ui-widget-content ui-corner-all ui-form-button-0" id="button6"><span class="ui-icon ' + buttons[i].icon + '"></span></div>'
            }
            button += '</div>';

            button = $(button);

            button.find('div.ui-form-field-button').qui();

            cell.append(button);
        },
        setType: function(name, callback) {
            $.form.types[name] = callback;
        },
        types: {
            text: function(options, container, width) {
                var input = $('<input class="ui-form-text ui-widget-content ui-corner-all" type="' + options.type + '" id="' + options.name + '" name="' + options.name + '" value="' + options.value + '" />');

                container.append(input);

                if (!options.width) {
                    input.fullWidth(width);
                } else {
                    input.fullWidth(options.width);
                }
            },
            plain: function(options, container, width) {
                var html = '<div class="ui-form-field ui-form-plain">' + options.value +  '</div>';
                var input = $(html);

                container.append(input);

                input.fullWidth(width);
            },
            label: function(options, container, width) {
                var input = $('<div class="ui-form-field ui-form-plain">' + options.value + '</div>');

                container.append(input);

                if (options.style) {
                    input.css(options.style);
                }

                if (options.width) {
                    input.fullWidth(options.width);
                }
            },
            select: function(options, container, width) {
                var html = '<select id="' + options.name + '" name="' + options.name + '" class="ui-form-select ui-widget-content ui-corner-all">';

                var selected = '';
                for (var i in options.sources) {
                    if (options.value == i) {
                        selected = ' selected="selected"';
                    } else {
                        selected = '';
                    }
                    html += '<option value="' + i + '" ' + selected + '>' + options.sources[i] + '</option>';
                }
                html += '</select>';
                var input = $(html);

                container.append(input);

                input.fullWidth(width);
            },
            radio: function(options, container, width) {
                var html = '<div class="ui-form-checkbox-group">';
                var id = options.name;
                for (var i in options.sources) {
                    if (0 != i) {
                        id = options.name + '-' + i;
                    }
                    html += '<div class="ui-form-checkbox-elem"><input class="ui-form-checkbox-input" type="radio" name="' + options.name + '" id="' + id + '" /><label class="ui-form-checkbox-label" for="' + id + '" >' + options.sources[i] + '</label></div>';
                }
                html += '</div>';
                var input = $(html);

                container.append(input);

                input.fullWidth(width);
            },
            checkbox: function(options, container, width) {
                var html = '<div class="ui-form-checkbox-group">';
                var id = options.name;
                for (var i in options.sources) {
                    if (0 != i) {
                        id = options.name + '-' + i;
                    }
                    html += '<div class="ui-form-checkbox-elem"><input class="ui-form-checkbox-input" type="checkbox" name="' + options.name + '[]" id="' + id + '" /><label class="ui-form-checkbox-label" for="' + id + '" >' + options.sources[i] + '</label></div>';
                }
                html += '</div>';
                var input = $(html);

                container.append(input);

                input.fullWidth(width);
            },
            textarea: function(options, container, width) {
                var input = $('<textarea class="ui-form-textarea ui-widget-content ui-corner-all" id="' + options.name + '" name="' + options.name + '" rows="4" cols="20">' + options.value + '</textarea>');

                container.append(input);

                input.fullWidth(width);
            },
            password: function(options, container, width) {
                return $.form.types.text(options, container, width);
            },
            hidden: function(options, container, width) {
                return $.form.types.text(options, container, width);
            },
            file: function(options, container, width, form) {
                if ('multipart/form-data' != form.attr('enctype')) {
                    form.attr('enctype', 'multipart/form-data');
                }

                var bid = 'ui-form-button-' + options.name;
                container.append('<button id="' + bid + '" class="ui-state-default ui-corner-all" style="height:24px;margin-left:1px;">浏览...</button>');

                width = width - $('#' + bid).qui().fullWidth() - 2;

                var inputId = 'ui-form-input-' + options.name;
                var html = '<input class="ui-form-text ui-widget-content ui-corner-all" readonly="readonly" type="text" id="' + inputId + '" name="' + options.name + '" value="' + options.value + '"/>';
                html += '<input class="ui-helper-hidden" type="' + options.type + '" id="' + options.name + '" name="' + options.name + '" value="' + options.value + '"/>';

                var input = $(html);
                container.append(input);

                input.fullWidth(width);

                $('#' + bid).click(function(){
                    $('#' + options.name).click();
                    return false;
                });
                $('#' + options.name).change(function(){
                    $('#' + inputId).val($(this).val());
                });
            },
            fieldSet: function(options, container, width, form, parentOptions) {
                options = $.extend($.form.fieldSetDefaults, form.options.fieldSetDefaults, options);

                var html = '<fieldset class="ui-form-fieldset ui-widget-content ui-corner-all">'
                         + '<legend class="ui-form-legend">';

                if (true == options.checkbox || options.checkbox.length) {
                    var attr = '';
                    for (var i in options.checkbox) {
                        attr += ' ' + i + '="' + options.checkbox[i] + '"';
                    }

                    var checked = options.collapsed ? '' : 'checked="checked" ';
                    html += '<div class="ui-form-legend-toggle"><input type="checkbox"' + checked + attr + ' /></div>';
                } else if (options.collapsible) {
                    var icon = options.collapsed ? 'ui-icon-triangle-1-s' : 'ui-icon-triangle-1-n';
                    html += '<div class="ui-form-legend-toggle ui-form-legend-toggle-icon ui-state-default ui-corner-all"><span class="ui-icon ' + icon + '"></span></div>';
                }

                html += '<div class="ui-form-legend-title">' + options.title + '</div><div class="ui-clear"></div></legend>'
                      + '<div class="ui-form-fieldset-content"></div></fieldset>';

                var input = $(html);

                if (options.collapsible) {
                    input.find('div.ui-form-legend-toggle').click(function(){
                        $(this).parent().next().toggle();

                        // toggle icons
                        var icon = $(this).find('span.ui-icon');
                        if (icon.length) {
                            if (icon.hasClass('ui-icon-triangle-1-s')) {
                                icon.removeClass('ui-icon-triangle-1-s');
                                icon.addClass('ui-icon-triangle-1-n')
                            } else {
                                icon.removeClass('ui-icon-triangle-1-n');
                                icon.addClass('ui-icon-triangle-1-s')
                            }
                        }
                    });
                }

                container.append(input);
                if ('ui-clear' !== container.parent().next().attr('class')) {
                    input.fullWidth(width - 5);
                } else {
                    input.fullWidth(width);
                }

                if ('object' == typeof options.fields) {
                    var fieldSet = input.find('div.ui-form-fieldset-content');
                    var rows = {};
                    $.form.renderLayout(options.fields, true, fieldSet, rows, 0, 1, form);

                    $.form.renderFields(rows, form);
                }

                if (options.collapsed) {
                    input.find('div.ui-form-fieldset-content:first').hide();
                }
            },
            fieldGroup: function(options, container, width, form) {
                if ('object' == typeof options.fields) {
                    var rows = {};
                    $.form.renderLayout(options.fields, true, container, rows, 0, 1, form);

                    $.form.renderFields(rows, form);
                }
            },
            datepicker: function(options, container, width, form) {
                if (undefined == $.datepicker) {
                    alert('jQuery ui datepicker plugin is not loaded.');
                    return;
                }

                $.form.types.text(options, container, width);

                $('#' + options.name).datepicker(options.options);
            }
        }
    });

    $.fn.fullWidth = function(width) {
        if (width) {
            // < 0 ?
            this.outerWidth(width - parseFloat(this.css('marginLeft')) - parseFloat(this.css('marginRight')));
            return this;
        } else {
            return this.outerWidth(true);
        }
    }
})(jQuery);