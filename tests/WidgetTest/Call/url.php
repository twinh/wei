<?php

require '../../../lib/Widget/Widget.php';

$widget = Widget\Widget::create();

$dataType = $widget->query('type');
$test = $widget->query('test');
$statusCode = $widget->query->getInt('code', 200);
$result = null;

switch ($test) {
    case 'headers':
        $widget->header('customHeader', 'value');
        $result = json_encode($widget->server->getHeaders());
        break;

    case 'post':
        $result = json_encode($_POST);
        break;

    default:
        switch ($dataType) {
            case 'json':
                $result = json_encode(array(
                    'code' => 0,
                    'message' => 'success'
                ));
                break;
            default:
                $result = 'default text';
        }
}

$widget->response($result, $statusCode);