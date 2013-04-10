<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $code ?><?php echo $message ?></title>
<style type="text/css">
body, div, ul, ol, li, h1, h2, h3, h4, h5, h6, p, th, td, pre {
    margin: 0;
    padding: 0;
}
body {
    font-size: 12px;
    color: #363636;
    background: #fafafa;
}
body, pre, code {
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif, "\5fae\8f6f\96c5\9ed1", "\5b8b\4f53";
}
table {
    border-spacing: 0;
}
a {
    text-decoration: none;
    outline: 0;
}
ol, ul, li {
    list-style: none;
}
.ui-state-error {
    color: #ee0a0a;
}
.message-wrapper h1 {
    font-size: 26px;
    font-weight: bold;
}
.message-wrapper h2 {
    font-size: 16px;
    font-weight: bold;
}
.message-content {
    padding: 15px 20px 20px 20px;
    clear: both;
}
.message-box {
    background-color: #ffffdd;
    border: 1px solid #ccc;
    margin-top: 16px;
    padding: 2px 4px;
}
.message-box th, .message-box td{
    padding: 2px 3px;
    vertical-align: top;
    font-size: 12px;
}
.message-box th {
    min-width: 150px;
    text-align: right;
    color: #666;
}
.message-box pre {
    line-height: 18px;
}
</style>
<!--<link href="http://php/bootstrap/docs/assets/css/bootstrap.css" rel="stylesheet">-->
</head>
<body>
<div class="message-wrapper">
    <div class="message-content">
        <h1><?php echo $code ?><?php echo $message ?></h1>
        <?php if ($debug) : ?>
        <div class="message-box">
            <h2>Call Stack</h2>
            <p class="ui-state-error"><?php echo $stackInfo ?></p>
            <p>
                <pre><?php echo $trace ?></pre>
            </p>
        </div>
        <div class="message-box">
            <h2>File Infomation</h2>
            <p class="ui-state-error"><?php echo $file ?> modified at <?php echo $mtime ?></p>
            <p>
                <pre><?php echo $fileInfo ?></pre>
            </p>
        </div>
        <div class="message-box">
            <h2>System Information</h2>
            <table>
                <tr>
                    <th>Widget Version</th>
                    <td><?php echo \Widget\Widget::VERSION ?></td>
                </tr>
                <tr>
                    <th>PHP Version</th>
                    <td><?php echo phpversion() ?></td>
                </tr>
            </table>
        </div>
        <div class="message-box">
            Tip: To disable debug message output, just set <code>debug = false</code>
            in your Widget config file or use <code>$this-&gt;widget-&gt;config(&#039;debug&#039;, false);</code>
            in any widgets.
        </div>
        <?php endif ?>
    </div>
</div>
</body>
</html>