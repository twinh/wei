<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $lang->t('LBL_HTML_TITLE')?></title>
<!--{JS}-->
<!--{CSS}-->
<?php
/*$rsc = Qwin::run('-rsc');
$rsc->load('js/jquery/core/jquery')
    ->load('jquery/ui/core')
    ->load('jquery/theme/' . qw('Qwin_Hepler_Util')->getStyle())
    ->load('js/other/common')
    ->load('css/style')
    ->load('js/other/qwin')
    ->load('js/other/url')
    ->load('js/other/adjust_width')
    ->load('jquery/plugin/qui')
    ->load('jquery/ui/tabs');

$ses = Qwin::run('-ses');
$loginState = $ses->get('member');
$qurl = array(
    'nca' => qw('-url')->nca,
    'separator' => qw('-url')->separator,
    'extension' => qw('-url')->extension,
);*/
$qurl = null;
?>

<link rel="stylesheet" type="text/css" href="../resource/js/jquery/themes/redmond/jquery-ui-1.8.4.custom.css" />
<link rel="stylesheet" type="text/css" href="../resource/view/theme/qwin2/style.css" />
<link rel="stylesheet" type="text/css" href="../resource/js/jquery/plugin/jqgrid/jquery.jqgrid.css" />

<script type="text/javascript" src="../resource/js/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../resource/js/jquery/ui/minified/jquery-ui-1.8.4.custom.min.js"></script>
<script type="text/javascript" src="../resource/js/qwin/common.js"></script>
<script type="text/javascript" src="../resource/js/qwin/qwin.js"></script>
<script type="text/javascript" src="../resource/js/qwin/url.js"></script>
<script type="text/javascript" src="../resource/js/qwin/adjust_width.js"></script>
<script type="text/javascript" src="../resource/js/jquery/ui/jquery.ui.widget.js"></script>
<script type="text/javascript" src="../resource/js/jquery/ui/button/ui.button.js"></script>
<script type="text/javascript" src="../resource/js/jquery/plugin/qui/jquery.qui.js"></script>
<script type="text/javascript" src="../resource/js/jquery/ui/tabs/ui.tabs.js"></script>
<script type="text/javascript" src="../resource/js/jquery/plugin/jqgrid/i18n/grid.locale-en.js"></script>
<script type="text/javascript" src="../resource/js/jquery/plugin/jqgrid/jquery.jqgrid.js"></script>
<script type="text/javascript" src="../resource/view/theme/qwin2/style.js"></script>
<script type="text/javascript">
Qwin.Lang = {'LBL_ACTION_LIST':'列表','LBL_ACTION_ADD':'添加','LBL_ACTION_EDIT':'编辑','LBL_ACTION_DELETE':'删除','LBL_ACTION_SHOW':'查看','LBL_ACTION_COPY':'复制','LBL_ACTION_FILTER':'筛选','LBL_ACTION_RETURN':'返回','LBL_ACTION_RESET':'重置','LBL_ACTION_SUBMIT':'提交','LBL_ACTION_APPLY':'应用','LBL_DEFAULT':'默认','LBL_OPERATION':'操作','LBL_SWITCH_DISPLAY_MODEL':'切换显示模式','LBL_SHORTCUT':'快捷方式','LBL_STYLE':'风格','LBL_THEME':'主题','LBL_WELCOME':'欢迎您','LBL_LOGOUT':'注销','LBL_TOOL':'工具','LBL_LANG':'语言','LBL_FIELD_ID':'编号','LBL_FIELD_NAME':'名称','LBL_FIELD_NAMESPACE':'命名空间','LBL_FIELD_TITLE':'标题','LBL_FIELD_OPERATION':'操作','LBL_FIELD_DATE_CREATED':'创建时间','LBL_FIELD_DATE_MODIFIED':'修改时间','LBL_FIELD_CAPTCHA':'验证码','LBL_FIELD_DESCRIPTION':'描述','LBL_FIELD_CONTENT':'内容','MSG_CHOOSE_ONLY_ONE_ROW':'请只选择一行!','MSG_CHOOSE_AT_LEASE_ONE_ROW':'请选择至少一行!','MSG_CONFIRM_TO_DELETE':'删除后将无法还原,确认?','MSG_ERROR_FIELD':'错误域: ','MSG_ERROR_MSG':'错误信息: ','MSG_NO_RECORD':'该记录不存在或已经被删除.','MSG_ERROR_CAPTCHA':'验证码错误','LBL_GROUP_BASIC_DATA':'基本资料','LBL_HTML_TITLE':'Content Management System - Powered by QWin Framework','LBL_FOOTER_COPYRIGHT':'Powered by <a>Qwin Framework</a>. Copyright &copy; 2009-2010 <a>Twin</a>. All rights reserved.','LBL_FIELD_PARENT_ID':'父分类','LBL_FIELD_ANCESTOR_ID':'祖先分类','LBL_FIELD_META':'Meta数据','LBL_FIELD_TO_URL':'跳转','LBL_FIELD_HIT':'人气','LBL_FIELD_PAGE_NAME':'页面名称','LBL_FIELD_CONTENT_PREVIEW':'内容预览','LBL_FIELD_CATEGORY_ID':'分类编号','LBL_FIELD_CATEGORY':'分类','LBL_FIELD_TEMPLATE':'模板','LBL_FIELD_AUTHOR':'作者','LBL_FIELD_THUMB':'缩略图','LBL_FIELD_ORDER':'顺序','LBL_FIELD_IS_POSTED':'是否发布','LBL_FIELD_IS_INDEX':'是否显示在首页','LBL_FIELD_JUMP_TO_URL':'跳转到','LBL_GROUP_PAGE_DATA':'页面资料','LBL_GROUP_SETTING_DATA':'配置资料','LBL_MODULE_ARTICLE':'文章','LBL_MODULE_ARTICLE_CATEGORY':'文章分类','LBL_ACTION_CREATE_HTML':'生成静态页面','MSG_TEMPLATE_NOT_EXISTS':'文章模板不存在,请返回修改!','LBL_CREATE_ALL_HMTL':'生成所有的静态页面','LBL_FIELD_AREA':'地区'};
</script>


</head>
<body>
<script type="text/javascript">
    jQuery.noConflict();
	var _get = <?php echo $arrayHelper->toJsObject($_GET);?>;
    var qurl = <?php echo $arrayHelper->toJsObject($qurl);?>;
    jQuery(function($) {
		function getWidgetObject()
		{
			$('body').append('<div id="ui-temp-widget-tcontent" class="ui-widget-content ui-helper-hidden"></div>');
			return $('#ui-temp-widget-tcontent');
		}
		
		$widget = getWidgetObject();
		/**
		 * 设置背景颜色,让背景看起来更协调
		 * @todo 允许自定义背景
		 */
		$('body').css('background-color', $widget.css('background-color'));
		/**
		 * 为各浏览器设置阴影
		 * @todo IE浏览器下,阴影会造成盒子位移
		 */
		var $widgetShadowColor = $widget.css('border-top-color');
		$('#ui-main, #ui-top-floating-bar, #ui-header-shortcut').css({
			'-moz-box-shadow' : '0px 0px 10px ' + $widgetShadowColor,
			'-webkit-box-shadow' : '0px 0px 10px ' + $widgetShadowColor,
			'box-shadow' : '0px 0px 10px ' + $widgetShadowColor
			//'filter' : 'progid:DXImageTransform.Microsoft.Shadow(color=' + $widgetShadowColor + ', Direction=135, Strength=3)'
		});
		
		$('button, input:submit, input:reset, input:button, a.ui-anchor').button();
		$('td.ui-field-radio, td.ui-field-checkbox').buttonset();
		
		$('#ui-header-shortcut a').qui({
			click: true,
			focus: true
		});
		$('#ui-header-shortcut a:first').addClass('ui-corner-bl');
		$('#ui-header-shortcut a:last').addClass('ui-header-shortcut-last-anchor');
    });
</script>
<div id="ui-top-floating-bar" class="ui-top-floating-bar ui-widget ui-widget-header">
	<ul>
    	<li><a class="ui-anchor ui-state-active" href="#Qwin">Qwin</a></li>
        <li><a class="ui-anchor" href="#Article">Home</a></li>
        <li><a class="ui-anchor" href="#Article">Article</a></li>
        <li><a class="ui-anchor" href="#Picture">Picture</a></li>
        <li><a class="ui-anchor" href="#Product">Product</a></li>
        <li><a class="ui-anchor" href="#File Manager">File Manager</a></li>
        <li><a class="ui-anchor" href="#Administrator">Administrator</a></li>
        <li><a class="ui-anchor" href="#Administrator">Administrator</a></li>
        <li><a class="ui-anchor" href="#Article">Add Your Link</a></li>
        <li><a class="ui-anchor" href="#Article"><span class="ui-icon ui-icon-carat-1-w"></span></a></li>
        <li><a class="ui-anchor" href="#Article"><span class="ui-icon ui-icon-carat-1-e"></span></a></li>
        <li><a class="ui-anchor" href="#Article"><span class="ui-icon ui-icon-close"></span></a></li>
    </ul>
</div>
<div id="ui-bottom-floating-botton" class="ui-bottom-floating-botton"><a class="ui-anchor" href="#"><span class="ui-icon ui-icon-arrowthickstop-1-n"></span></a></div>
<div id="ui-main" class="ui-main ui-widget-content ui-corner-all">
  <div id="ui-header" class="ui-header ui-widget">
    <div class="ui-header-shortcut" id="ui-header-shortcut">
    	<a class="ui-state-default" href="#"><?php echo $lang->t('LBL_WELCOME')?>, twin!</a>
        <a class="ui-state-default" href="?namespace=Default&module=Member&controller=Log&action=Logout"><?php echo $lang->t('LBL_LOGOUT')?></a>
    </div>
    <div class="ui-header-logo ui-widget-content"> <a href="">Qwin<sup>3.0Beta</sup></a> </div>
  </div>
  <script type="text/javascript">
var validator_rule = {"title":{"required":true,"maxlength":200}};

</script>
<?php
require $this->getElement('content');
?>
  <div class="ui-footer ui-widget">
    <div class="ui-copyright ui-widget-content"><?php echo $lang->t('LBL_FOOTER_COPYRIGHT')?></div>
  </div>
</div>
</body>
</html>
