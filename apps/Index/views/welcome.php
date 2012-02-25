<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->options['charset'] ?>" />
<title></title>
<?php
$minify->add(array(
    $jQuery->getTheme($this->options['theme']),
    $this->getFile('views/style.css'),
));
?>
</head>
<body>
<div class="ui-widget">
<ul class="ui-updatelog">
    <p><?php echo $lang['Hello, wellcome to Qwin!'] ?></p>
</ul>
</div>
</body>
</html>
