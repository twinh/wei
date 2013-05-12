<?php

try {
    $server = new SoapServer(null, array(
      'uri' => 'http://php/widget/tests/WidgetTest/Call/soap.php'
    ));
    $server->setClass('MyClass');
    $server->handle();
} catch (SoapFault $f) {
    echo $f->faultstring;
}