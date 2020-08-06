<?php

require '../../../lib/Wei.php';

$wei = wei();
$request = $wei->req;
$dataType = $request->getQuery('type');
$test = $request->getQuery('test');
$statusCode = $request->getQuery('code', 200);
$wait = (float) $request->getQuery('wait');
$result = null;

switch ($test) {
    case 'headers':
        $wei->res->setHeader('customHeader', 'value');
        $result = json_encode($wei->req->getHeaders());
        break;

    case 'post':
        // phpcs:ignore MySource.PHP.GetRequestData.SuperglobalAccessed
        $result = json_encode($_POST + ['method' => $request->getMethod()] + ['files' => $_FILES]);
        break;

    case 'user-agent':
        $result = $wei->req->getServer('HTTP_USER_AGENT');
        break;

    case 'referer':
        $result = $wei->req->getServer('HTTP_REFERER');
        break;

    case 'cookie':
        $result = json_encode($wei->cookie->toArray());
        break;

    case 'responseCookies':
        foreach ($wei->cookie->toArray() as $name => $value) {
            $wei->res->setCookie($name, $value);
        }
        $result = json_encode($wei->cookie->toArray());
        break;

    case 'methods':
        $result = [
            'method' => $request->getMethod(),
            'data' => [],
        ];
        parse_str($request->getContent(), $result['data']);
        $result = json_encode($result);
        break;

    case 'get':
        $result = json_encode($request->getParameterReference('get'));
        break;

    default:
        switch ($dataType) {
            case 'json':
                $result = json_encode([
                    'code' => 0,
                    'message' => 'success',
                    'method' => $request->getMethod(),
                ]);
                break;

            case 'query':
                $result = http_build_query([
                    'code' => 0,
                    'message' => 'success',
                ]);
                break;

            case 'serialize':
                $result = serialize([
                    'code' => 0,
                    'message' => 'success',
                ]);
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

$wei->res($result, $statusCode);
