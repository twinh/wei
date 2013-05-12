<?php
$dataType = isset($_GET['type']) ? $_GET['type'] : null;
$result = null;

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

echo $result;