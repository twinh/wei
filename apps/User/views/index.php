<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->options['charset'] ?>" />
<title></title>
<?php
$minify->add(array(
    $jQuery->getTheme($this->options['theme']),
    $jQuery->getCore(),
    $jQuery->getDir() . '/jqGrid/i18n/grid.locale-cn.js',
    $jQuery->get('ui.core, effects, qui, ui.button, metadata, jqGrid'),
    $this->getFile('views/style.css'),
    $this->getFile('views/style.js'),
));
?>
</head>
<body>
<div id="qw-jqgrid-top" class="qw-toolbar ui-state-default">
    <a href="#">添加</a> | <a href="#">编辑</a> | <a href="#">删除</a>
</div>
<table id="qw-jqgrid"></table>
<div id="qw-jqgrid-pager"></div>
</body>
</html>
