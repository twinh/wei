<?php

require '../../lib/Widget/Widget.php';

$widget = \Widget\Widget::create();

if ($widget->isCallback(10, function($input) {
    return 0 === $input % 3;
})) {
    echo 'success';
} else {
    echo 'failure';
}