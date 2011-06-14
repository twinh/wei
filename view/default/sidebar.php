<?php
/**
 * common-sidebar
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
 * @package     Common
 * @subpackage  View
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2011-01-04 02:47:27
 */
?>
<?php Qwin::hook('viewSidebar', array(
    'view' => $this
)); ?>
<div class="qw-sidebar-content qw-sidebar-content-2">
    <ul>
        <li><a class="qw-icon qw-icon-trash-16" href="<?php echo qw_url(array('module' => 'trash')) ?>"><?php echo qw_t('LBL_MODULE_TRASH') ?></a></li>
    </ul>
</div>
