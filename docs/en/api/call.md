Call
====

A service handles HTTP request which inspired by [jQuery Ajax](http://api.jquery.com/jQuery.ajax/)

Example
-------

### Using callbacks to record cURL request log

`call` service provides four callbacks, including `beforeSend`, `success`, `error` and `complete`, 
which could be use for recording request log, reporting request time, etc.

```php
wei(array(
    'call' => array(
        // The callback triggered after prepared the data and before the process the request,
        // could be use to recod the request URL, method and parameters.
        'beforeSend' => function (\Widget\Call $call, $ch) {
            wei()->logger->debug(array(
                'Request URL'       => $call->getUrl(),
                'Request Method'    => $call->getMethod(),
                'Parameters'        => $call->getData(),
            ));
        },
        // The callback triggered after the request is called success
        'success' => function ($data, \Widget\Call $call) {
            // Record it on demand
        },
        // The callback triggered when the request fails,
        // could be use to record the error status and exception message.
        'error' => function (\Widget\Call $call) {
            wei()->logger->error(array(
                'Error status'  => $call->getErrorStatus(),
                'Exception'     => (string)$call->getErrorException(),
            ));
        },
        // The callback triggered when request finishes
        // could be use to record response status code, and the cost time.
        'complete' => function (\Widget\Call $call, $ch) {
            $curlInfo = curl_getinfo($ch);

            wei()->logger->debug(array(
                'Status Code' => $curlInfo['http_code'],
                'Server IP'     => $call->getIp() ?: '(Not specified)',
                'Total Time'    => $curlInfo['total_time'] . 's',
                'Response Body' => $call->getResponse(),
            ));
        }
    )
));
```