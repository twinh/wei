<?php
/**
 * 表单页
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
 * @since       2009-11-24 18:47:32
 */
$minify->add(array(
    $jQuery->loadUi('mouse', false),
    $jQuery->loadUi('resizable', false),
    $jQuery->loadPlugin('ui.form', false),
));
?>
<script type="text/javascript">
jQuery(function($){
    $('#qw-form').form(<?php echo json_encode($form) ?>);
});
</script>
<form id="qw-form" method="post" action="" class="qw-form ui-widget ui-corner-all" style="width:800px; margin: 0 0 0 10px;">
    
</form>
