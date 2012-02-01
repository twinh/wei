<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo $message ?></title>
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
                font-family: Tahoma, Helvetica, Arial, sans-serif;
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
            .message-box {
                background-color: #ffffdd;
                border: 1px solid #ccc;
                margin-top: 16px;
                padding: 2px 4px;
            }
            .qw-message p {
                padding: 2px 0;
            }
            .qw-message p span{
                float: left;
            }
            .qw-message-box h1 {
                font-size: 26px;
                font-weight: bold;
            }
            .qw-message-box h2 {
                font-size: 16px;
                font-weight: bold;
            }
            .qw-message-content {
                padding: 15px 20px 20px 20px;
                clear: both;
            }
            .qw-message-content th, .qw-message-content td{
                padding: 2px 3px;
                vertical-align: top;
            }
            .qw-message-content th {
                min-width: 150px;
                text-align: right;
                color: #666;
            }
            .qw-message-content pre {
                line-height: 18px;
            }
        </style>
    </head>
    <body>
        <div class="qw-message-box">
            <div class="qw-message-content">
                <h1><?php echo $code ?><?php echo $message ?></h1>
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
                            <th>Request Method</th>
                            <td><?php echo $requestMethod ?></td>
                        </tr>
                        <tr>
                            <th>Request URL</th>
                            <td><?php echo $requestUrl ?></td>
                        </tr>
                        <tr>
                            <th>Qwin Version</th>
                            <td><?php echo Qwin::VERSION ?></td>
                        </tr>
                        <tr>
                            <th>PHP Version</th>
                            <td><?php echo phpversion() ?></td>
                        </tr>
                        <tr>
                            <th>Server Time</th>
                            <td><?php echo $serverTime ?></td>
                        </tr>
                        <tr>
                            <th>Include Paths</th>
                            <td><?php echo $includePath ?></td>
                        </tr>
                    </table>
                </div>
                <div class="message-box">
                    <h2>Request Information</h2>
                    <table>
                        <tr>
                            <th>GET</th>
                            <td>
                                <?php
                                if (empty($get)):
                                    ?>
                                    <em>empty</em>
                                    <?php
                                else:
                                    foreach ($get as $key => $value):
                                        ?>
                                        <?php echo $key ?>: <?php echo $value ?><br />
                                        <?php
                                    endforeach;
                                endif;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th>POST</th>
                            <td>
                                <?php
                                if (empty($post)):
                                    ?>
                                    <em>empty</em>
                                    <?php
                                else:
                                    foreach ($post as $key => $value):
                                        ?>
                                        <?php echo $key ?>: <?php echo $value ?><br />
                                        <?php
                                    endforeach;
                                endif;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th>COOKIE</th>
                            <td>
                                <?php
                                if (empty($cookie)):
                                    ?>
                                    <em>empty</em>
                                    <?php
                                else:
                                    foreach ($cookie as $key => $value):
                                        ?>
                                        <?php echo $key ?>: <?php echo $value ?><br />
                                        <?php
                                    endforeach;
                                endif;
                                ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="message-box">
                    <h2>Session Information</h2>
                    <table>
                        <?php
                        if (empty($session)):
                            ?>
                            <tr><td clospan="2"><em>empty</em></td></tr>
                            <?php
                        else:
                            foreach ($session as $key => $value):
                                ?>
                                <tr>
                                    <th><?php echo $key ?></th>
                                    <td><?php echo $value ?></td>
                                </tr>
                                <?php
                            endforeach;
                        endif;
                        ?>
                    </table>
                </div>
                <div class="message-box">
                    <h2>Server Environment</h2>
                    <table>
                        <?php
                        foreach ($server as $key => $value) :
                            ?>
                            <tr>
                                <th><?php echo $key ?></th>
                                <td><?php echo $value ?></td>
                            </tr>
                            <?php
                        endforeach;
                        ?>
                    </table>
                </div>
                <div class="message-box">
                    Tip: To disable debug message output, just set <code>debug = false</code>
                    in your Qwin config file or use <code>$this-&gt;config(&#039;debug&#039;, false);</code>
                    in any widgets.
                </div>
            </div>
        </div>
    </body>
</html>

