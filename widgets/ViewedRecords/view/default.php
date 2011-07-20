<?php
/**
 * viewed-item
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
 * @since       2011-01-03 01:29:49
 */
?>
<div class="qw-sidebar-header ui-state-default">
    <a class="qw-icon qw-icon-clock-16" href="javascript:;"><?php echo qw_t('LBL_VIEWED_RECORDS') ?></a>
</div>
<div class="qw-sidebar">
    <ul>
    <?php
    if(empty($viewRecords)):
    ?>
        <li><a><?php echo qw_t('MSG_NO_VIEWED_RECORDS') ?></a></li>
	<?php
    else:
        foreach($viewRecords as $row):
    ?>
        <li>
        	<a href="<?php echo $row['href'] ?>"><?php echo $row['title'] ?></a>
        </li>
    <?php
        endforeach;
    endif;
    ?>
    </ul>
</div>