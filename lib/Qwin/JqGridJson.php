<?php
/**
 * Qwin Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Qwin;

/**
 * JqGridJson
 *
 * @package     Qwin
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class JqGridJson extends Widget
{
    public $options = array(
        'page' => 1,
        'rows' => 1,
        'total' => 1,
        'data' => array(),
        'columns' => array(),
    );

    public function __invoke(array $options = array())
    {
        $options = $options + $this->options;

        extract($options);

        $json = array();
        foreach ($data as $row) {
            $cell = array();
            foreach ($columns as $column) {
                if (isset($row[$column])) {
                    if ($row[$column] instanceof \DateTime) {
                        $date = (array)$row[$column];
                        $cell[] = $date['date'];
                    } else {
                        $cell[] = $row[$column];
                    }
                } else {
                    $cell[] = null;
                }
            }
            $json[] = array(
                'id' => $row['id'],
                'cell' => $cell,
            );
        }

        return json_encode(array(
            'page'      => $page,
            'total'     => ceil($total / $rows),
            'records'   => $total,
            'rows'      => $json,
        ));
    }
}