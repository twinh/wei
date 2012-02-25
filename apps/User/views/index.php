<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->options['charset'] ?>" />
<title></title>
<?php
$minify->add(array(
    $jQuery->getTheme($this->options['theme']),
    $jQuery->get('jquery, ui, effects, draggable, dialog, datepicker, form, qui, button, metadata, jqGrid'),
    $jQuery->getDir() . '/jqGrid/i18n/grid.locale-cn.js',
    $this->getFile('views/style.js'),
    $this->getFile('views/style.css'),
    $this->getFile('views/form/default.css'),
));
?>
</head>
<body>
<table id="user-grid"></table>
<div id="user-grid-pager"></div>
</body>
</html>
