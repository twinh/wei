<?php

namespace WidgetTest\Call;

class Soap
{
    public function add($left, $right)
    {
        return $left + $right;
    }
}

try {
    $server = new \SoapServer(null, array(
      'uri' => 'http://php/widget/tests/WidgetTest/Call/soap.php'
    ));
    $server->setClass('WidgetTest\Call\Soap');
    $server->handle();
} catch (\SoapFault $f) {
    echo $f->faultstring;
}