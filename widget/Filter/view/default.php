<?php
/**
 * default
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
 * @since       2011-04-13 04:16:38
 */
?>
<style type="text/css">
.qw-filter{
    margin: 0 15px;
}
.qw-filter h3{
    height: 30px;
}
.qw-filter h3 a.qw-filter-return {
    float: right;
    display: block;
    width: 20px;
    height: 20px;
}
.qw-filter h3 a.qw-filter-cancel {
    font-size: 12px;
    font-weight: normal;
}
.qw-filter a.ui-state-default {
    font-weight: normal;
    padding: 2px;
}

.qw-filter .qw-filter-content {
    line-height: 35px;
    clear: both;
}
.qw-filter .qw-filter-content a {
    font-weight: normal;
    text-decoration: underline;
}
.qw-filter .qw-filter-content a.ui-state-default {
    text-decoration: none;
}
.qw-filter .qw-filter-content a.qw-filter-seleted {
    cursor: text;
}
</style>
<div class="qw-c"></div>
<div class="ui-widget qw-filter">
    <div class="ui-widget-content">
        <h3>
            <a class="qw-filter-return" href="<?php echo $returnUrl ?>"><span class="ui-icon ui-icon-close"></span></a>
            <?php echo $lang[$meta['page']['title']] , $lang['ACT_FILTER'] ?>
            <?php if (!empty($searchData)) : ?>
            <a class="qw-filter-cancel" href="<?php echo $cancelUrl ?>">(<?php echo $lang['LBL_FILTER_CANCEL'] ?>)</a>
            <?php endif ?>
        </h3>
        <?php
        if ($data) :
            foreach ($data as $field => $row) :
        ?>
        <div class="qw-filter-content">
            <strong><?php echo $lang[$meta['field'][$field]['basic']['title']] ?>：</strong>
            <?php
            foreach ($row as $element) :
                if (isset($element['selected'])) :
                    $class = 'ui-state-default qw-filter-seleted';
                else :
                    $class = '';
                endif;
            ?>
            <a class="<?php echo $class ?>" href="<?php echo $element['url'] ?>"><?php echo $element['name'] ?></a>&nbsp;
            <?php endforeach ?>
        </div>
        <?php 
            endforeach;
        else :
        ?>
        <div class="qw-filter-content"><?php echo $lang['LBL_NO_FILTER_FIELD'] ?></div>
        <?php
        endif;
        ?>
    </div>
</div>
<div class="qw-c"></div>

