<?php

require '../../../lib/Widget/Widget.php';

$widget = Widget\Widget::create();
$request = $widget->request;
$dataType = $widget->query('type');
$test = $widget->query('test');
$statusCode = $widget->query->getInt('code', 200);
$wait = (float)$widget->query('wait');
$result = null;

switch ($test) {
    case 'headers':
        $widget->header('customHeader', 'value');
        $result = json_encode($widget->request->getHeaders());
        break;

    case 'post':
        $result = json_encode($_POST);
        break;

    case 'user-agent':
        $result = $widget->request->getServer('HTTP_USER_AGENT');
        break;

    case 'referer':
        $result = $widget->request->getServer('HTTP_REFERER');
        break;

    case 'cookie':
        $result = json_encode($widget->cookie->toArray());
        break;

    case 'methods':
        $result = array(
            'method' => $request->getMethod(),
            'data' => array()
        );
        parse_str($request->getContent(), $result['data']);
        $result = json_encode($result);
        break;

    case 'get':
        $result = json_encode($request->getParameterReference('get'));
        break;

    default:
        switch ($dataType) {
            case 'json':
                $result = json_encode(array(
                    'code' => 0,
                    'message' => 'success'
                ));
                break;

            case 'query':
                $result = http_build_query(array(
                    'code' => 0,
                    'message' => 'success'
                ));
                break;

            case 'serialize':
                $result = serialize(array(
                    'code' => 0,
                    'message' => 'success'
                ));
                break;

            case 'xml':
                $result = '<xml>
                <code><![CDATA[0]]></code>
                <message><![CDATA[success]]></message>
                </xml>';
                break;

            default:
                $result = 'default text';
        }
}

if ($wait) {
    usleep(1000000 * $wait);
}

$widget->response($result, $statusCode);