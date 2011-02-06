<?php
/**
 * FromToDatepicker
 *
 * Copyright (c) 2008-2010 Twin Huang. All rights reserved.
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
 * @package     Qwin
 * @subpackage  Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-10-30 18:19:02
 */

class Qwin_Widget_JQuery_FromToDatepicker
{
    public function __construct()
    {

    }

    public function render($meta, $id)
    {
        $jquery = Qwin::call('Qwin_Resource_JQuery');
        $cssPacker = Qwin::call('Qwin_Packer_Css');
        $jsPacker = Qwin::call('Qwin_Packer_Js');

        $datepickerFile = $jquery->loadUi('datepicker', false);
        $cssPacker->add($datepickerFile['css']);
        $jsPacker->add($datepickerFile['js']);

        $code = '<script type="text/javascript">
    jQuery(function($){
        var dates = $( "#' . $id[0] . ', #' . $id[1] . '" ).datepicker({
            dateFormat: "yy-mm-dd",
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 2,
            onSelect: function( selectedDate ) {
                var option = this.id == "' . $id[0] . '" ? "minDate" : "maxDate",
                    instance = $( this ).data( "datepicker" );
                    date = $.datepicker.parseDate(
                        instance.settings.dateFormat ||
                        $.datepicker._defaults.dateFormat,
                        selectedDate, instance.settings );
                dates.not( this ).datepicker( "option", option, date );
            }
        });
    });
               </script>';
        return $code;
    }
}

