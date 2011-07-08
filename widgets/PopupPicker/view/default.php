<?php
/**
 * jQuery-popupgrid
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
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2011-01-13 00:36:36
 */
echo $this->_form->renderElement($element);
?>
&nbsp;<button id="qw-button-popup-<?php echo $id ?>" class="qw-button-common" type="button"><span class="ui-icon ui-icon-calculator"><?php echo $element['name'] ?></span></button>
<button id="qw-button-popup-<?php echo $id ?>-clear" class="qw-button-common" type="button"><span class="ui-icon ui-icon-arrowreturnthick-1-w"><?php echo $element['name'] ?></span></button>
<script type="text/javascript">
jQuery(function($){
    $('#<?php echo $id ?>').hide();
    $('#qw-button-popup-<?php echo $id ?>').popupGrid({
        title       : '<?php  echo $lang['LBL_PLEASE_SELECT'] ?>',
        url         : "<?php  echo $url ?>",
        viewInput   : '#<?php echo $element['id'] ?>',
        valueInput  : '#<?php echo $id ?>',
        viewColumn  : '<?php  echo $options['display'] ?>',
        valueColumn : '<?php  echo $options['field'] ?>'
    });
    $('#qw-button-popup-<?php echo $id ?>-clear').click(function(){
        $('#<?php echo $id ?>').val('');
        $('#<?php echo $element['id'] ?>').val('(<?php echo $lang['LBL_NOT_SELECTED']?>,<?php echo $lang['LBL_READONLY'] ?>)');
    });
})
</script>
