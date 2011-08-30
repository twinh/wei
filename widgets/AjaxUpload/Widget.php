<?php
/**
 * widget
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
 * @since       2010-08-20 10:51:21
 */

class AjaxUpload_Widget extends Qwin_Widget_Abstract
{
    public function render($options = null)
    {
        $jQuery = $this->_widget->get('JQuery');
        $minify = $this->_widget->get('Minify');

        $minify->add(array(
            $jQuery->loadUi('position', false),
            $jQuery->loadUi('dialog', false),
            $this->_path . 'source/jquery.ajaxupload.css',
            $this->_path . 'source/jquery.ajaxupload.js',
        ));
        

        $buttonId = 'ui-button-ajaxupload-' . $options['form']['id'];

        $code = '<button id="' . $buttonId . '" type="button"><span class="ui-icon ui-icon-arrowthickstop-1-n">' . $options['form']['id'] . '</span></button>
                <script type="text/javascript">jQuery(function($){
                    $("#' . $buttonId . '").ajaxUpload({
                        input : "#' . $options['form']['id'] . '"
                    });
                });
              </script>';
        return $code;
    }
}
