<?php
/**
 * form 的名称
 *
 * form 的简要介绍
 *
 * Copyright (c) 2009 Twin. All rights reserved.
 * 
 * LICENSE:
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author    Twin Huang <twinh@yahoo.cn>
 * @copyright Twin Huang
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   2009-10-31 01:19:12
 * @since     2009-11-24 18:47:32
 */

// 防止直接访问导致错误
!defined('QWIN_PATH') && exit('Forbidden');
//qw('-rsc')->load('jquery/plugin/tip');
//qw('-rsc')->load('jquery/plugin/validate');
// TODO
!isset($validator_rule) && $validator_rule = '""';
?>
<script type="text/javascript">
var validator_rule = <?php echo $validator_rule?>;
</script>
<?php
//qw('-rsc')->load('js/other/form');
//qw('-rsc')->load('jquery/ui/tabs');
?>
<div class="ui-form-content ui-box-content ui-widget-content">
        <div class="ui-operation-field">
          <input type="submit" id="submit" value="Submit" />
          <input type="reset" value="Reset" />
          <input type="button" class="action-return" value="Return" />
        </div>
        <?php foreach($groupList as $group => $fieldList): ?>
        <fieldset id="ui-fieldset-3" class="ui-widget-content ui-corner-all">
          <legend>More More Data</legend>
          <table class="ui-table" width="100%">
            <tr>
              <td width="12.5%"></td>
              <td width="37.5%"></td>
              <td width="12.5%"></td>
              <td width="37.5%"></td>
            </tr>
            <tr>
              <td class="ui-label-common"><label for="title">Title:</label></td>
              <td class="ui-field-text" colspan="3"><input type="text" value="今年灏逸的新产品发布类型" name="title" id="title" class=" ui-widget-content ui-corner-all" /></td>
            </tr>
            <tr>
              <td class="ui-label-common"><label for="title">Title:</label></td>
              <td class="ui-field-textarea" colspan="3"><textarea name="content_preview" id="content-preview" class=" ui-widget-content ui-corner-all" ></textarea></td>
            </tr>
            <tr>
              <td class="ui-label-common"><label for="title">Basic Data:</label></td>
              <td class="ui-field-select" colspan="3"><select name="category_id" id="category-id" class=" ui-widget-content ui-corner-all" >
                  <option value="a741ed15-0273-4742-a31e-462d28a235ab">单页面</option>
                  <option value="21ef9f4c-878f-46b2-ba45-78b1a80614f2" selected="selected" >默认</option>
                </select></td>
            </tr>
            <tr>
              <td class="ui-label-common"><label for="title">Title:</label></td>
              <td class="ui-field-checkbox" colspan="3"><input type="checkbox" id="Bold3" />
                <label for="Bold3">Bold</label>
                <input type="checkbox" id="Italic3" checked="checked" />
                <label for="Italic3">Italic</label>
                <input type="checkbox" id="Underline3" />
                <label for="Underline3">Underline</label></td>
            </tr>
            <tr>
              <td class="ui-label-common"><label for="title">Title:</label></td>
              <td class="ui-field-radio" colspan="3"><input type="radio" id="Red5" name="color2" />
                <label for="Red5">Red</label>
                <input type="radio" id="Blue5" name="color2" />
                <label for="Blue5">Blue</label>
                <input type="radio" id="Green5" name="color2" />
                <label for="Green5">Green</label></td>
            </tr>
          </table>
        </fieldset>
        <?php endforeach; ?>
        <div class="ui-operation-field">
          <input type="submit" id="submit" value="Submit" />
          <input type="reset" value="Reset" />
          <input type="button" class="action-return" value="Return" />
        </div>
      </form>
    </div>
<script type="text/javascript" src="<?php echo QWIN_RESOURCE_PATH?>/js/other/form.js"></script>
