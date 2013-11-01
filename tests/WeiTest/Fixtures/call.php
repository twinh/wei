<?php

require '../../../lib/Wei/Wei.php';

$wei     = wei();
$request    = $wei->request;
$dataType   = $request->getQuery('type');
$test       = $request->getQuery('test');
$statusCode = $request->getQuery('code', 200);
$wait       = (float)$request->getQuery('wait');
$result     = null;

switch ($test) {
    case 'headers':
        $wei->response->setHeader('customHeader', 'value');
        $result = json_encode($wei->request->getHeaders());
        break;

    case 'post':
        $result = json_encode($_POST);
        break;

    case 'user-agent':
        $result = $wei->request->getServer('HTTP_USER_AGENT');
        break;

    case 'referer':
        $result = $wei->request->getServer('HTTP_REFERER');
        break;

    case 'cookie':
        $result = json_encode($wei->cookie->toArray());
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

$wei->response($result, $statusCode);
