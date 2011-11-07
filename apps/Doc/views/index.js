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
 * index
 * 
 * @package     Qwin
 * @subpackage  Qwin
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2011-09-26 01:40:41
 */
jQuery(function($){
    $("#qw-docs-tabs").tabs();
        
    $.each({
        'options': {
            'box': '#qw-docs-options',
            'content': 'div.qw-docs-option-content',
            'title': '.qw-docs-option-name a'
        },
        'events': {
            'box': '#qw-docs-events',
            'content': 'div.qw-docs-event-content',
            'title': '.qw-docs-event-name a'
        },
        'results': {
            'box': '#qw-docs-results',
            'content': 'div.qw-docs-result-content',
            'title': '.qw-docs-result-message a'
        },
        'properties': {
            'box': '#qw-docs-properties',
            'content': 'div.qw-docs-property-content',
            'title': '.qw-docs-property-name a'
        },
        'methods': {
            'box': '#qw-docs-methods',
            'content': 'div.qw-docs-method-content',
            'title': '.qw-docs-method-name a'
        },
        'parameters': {
            'box': '#qw-docs-parameters',
            'content': 'div.qw-docs-parameter-content',
            'title': '.qw-docs-parameter-name a'
        }
    }, function(index, s) {
        $(s.box).find(s.title).qui().add($(s.box).find('span.qw-docs-detail-toggle')).click(function(){
            var parent = $(this).parent().parent().parent();
            var toggle = parent.find('span.qw-docs-detail-toggle');
            if (toggle.first().hasClass('ui-icon-triangle-1-s')) {
                toggle.removeClass('ui-icon-triangle-1-s');
                parent.find(s.content).hide();
            } else {
                toggle.addClass('ui-icon-triangle-1-s');
                parent.find(s.content).show();
            }
        });
    });

    $('#qw-docs-tabs div.qw-docs-empty-cell a').qui();
    $('#qw-docs-overview h3 a').qui();
    $('#qw-docs-summary a').qui();
    $('#qw-docs-methods dl a').qui();
    $('#qw-doc-inheritence-details, #qw-doc-inheritence-toggle').click(function(){
        $('#qw-doc-inheritence-tree1').toggle(600);
        $('#qw-doc-inheritence-tree').toggle(600);
    });
});