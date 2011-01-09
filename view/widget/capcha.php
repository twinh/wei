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
 * @version   2010-02-26 22:44
 * @since     2010-02-26 22:44
 */
?>
<script type="text/javascript">
jQuery(function($){
    $('#icon-info-<?php echo $asc['name']?> div.ui-form-tip-content ul').prepend('<li><img id="button-capcha-<?php echo $asc['name']?>" src="" style="display:block;cursor:pointer; margin-left:4px;" /></li>');
    var capcha = $('#button-capcha-<?php echo $asc['name']?>');
	$('#<?php echo $asc['name']?>').one('focus', function(){
		loadCapcha();
	});
	// 加载验证码之后显示图片
	capcha.one('load', function(){
			$(this).show('slow');
		})
		.click(function(){
			loadCapcha();
		});
	
	var loadCapcha = function()
	{
		capcha.attr('src', 'captcha.php?t=' + new Date());
	}

    $('#<?php echo $asc['name']?>').focus(function(){
        $('#ui-tip-<?php echo $asc['name']?>').tip('open');
    });
});
</script>
