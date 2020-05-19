Http
====

A service handles HTTP request which inspired by [jQuery Ajax](http://api.jquery.com/jQuery.ajax/)

Example
-------

### A complete example: Get public gists

```php
$http = wei()->http(array(
    // Set the request URL
    'url' => 'https://api.github.com/gists',
    // The default request method is `get`, could be set to `post`, `put`, or any other valid methods
    'method' => 'get',
    // Parse the response data from JSON string to array
    'dataType' => 'json',
    // Set the request parameters
    'data' => array(
        'time' => time(),
    ),
));

if ($http->isSuccess()) {
    // Output the first URL in response data, such as https://api.github.com/gists/xxxxxxx
    var_dump($response[0]['url']);
} else {
    // Output the error status, such as `curl`, for all error statuses, please refer to the `Option` section
    var_dump($http->getErrorStatus());

    // Output the exception message, such as `Couldn't resolve host '404.php.net'`
    var_dump($http->getErrorException()->getMessage());
}
```

### Using callbacks to record cURL request log

`http` service provides four callbacks, including `beforeSend`, `success`, `error` and `complete`, 
which could be use for recording request log, reporting request time, etc.

```php
wei(array(
    'http' => array(
        // The callback triggered after prepared the data and before the process the request,
        // could be use to recod the request URL, method and parameters.
        'beforeSend' => function (\Wei\Http $http, $ch) {
            wei()->logger->debug(array(
                'Request URL'       => $http->getUrl(),
                'Request Method'    => $http->getMethod(),
                'Parameters'        => $http->getData(),
            ));
        },
        // The callback triggered after the request is called success
        'success' => function ($data, \Wei\Http $http) {
            // Record it on demand
        },
        // The callback triggered when the request fails,
        // could be use to record the error status and exception message.
        'error' => function (\Wei\Http $http) {
            wei()->logger->error(array(
                'Error status'  => $http->getErrorStatus(),
                'Exception'     => (string)$http->getErrorException(),
            ));
        },
        // The callback triggered when request finishes
        // could be use to record response status code, and the cost time.
        'complete' => function (\Wei\Http $http, $ch) {
            $curlInfo = curl_getinfo($ch);

            wei()->logger->debug(array(
                'Status Code' => $curlInfo['http_code'],
                'Server IP'     => $http->getIp() ?: '(Not specified)',
                'Total Time'    => $curlInfo['total_time'] . 's',
                'Response Body' => $http->getResponse(),
            ));
        }
    )
));
```
