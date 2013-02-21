<?php

/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * Memcache
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 * @todo        add index column
 * @todo        add index total row
 * @todo        add memory column
 */
class Marker extends WidgetProvider
{
    /**
     * Maker index
     *
     * @var int
     */
    protected $index = 0;

    /**
     * Markers data
     *
     * @var array
     */
    protected $data = array();

    /**
     * Whether auto start record when marker constructed
     * 
     * @var bool
     */
    protected $auto = false;

    /**
     * Constructor
     * 
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);
        
        $this->auto && $this('Start');
    }

    /**
     * Set a marker
     *
     * @param  string|null      $name marker's name
     * @return Marker
     */
    public function __invoke($name = null)
    {
        !$name && $name = ++$this->index;

        $this->data[$name] = array(
            'time' => microtime(true),
            'memory' => memory_get_usage()
        );

        return $this;
    }

    /**
     * Returns the marker data
     *
     * @return array<*,string>
     */
    public function getMarkers()
    {
        return $this->data;
    }

    /**
     * Display profiling data
     *
     * @param  boolean|string $print
     * @return Marker|string
     */
    public function display($print = true)
    {
        reset($this->data);
        $start = current($this->data);
        $end = end($this->data);
        $total = bcsub($end['time'], $start['time'], 8);

        $code = '<table class="table table-bordered table-hover">'
              . '<thead><tr>'
              . '<th>Marker</th>'
              . '<th>Time</th>'
              . '<th>Elapsed Time</th>'
              . '<th>%</th>'
              . '<th>Memory Usage</th>'
              . '</tr></thead><tbody>';
        foreach ($this->data as $name => $data) {
            if (isset($preTime)) {
                $elapsedTime = bcsub($data['time'], $preTime, 4);
                $percentage = bcmul(bcdiv($elapsedTime, $total, 4), 100, 2) . '%';
            } else {
                $elapsedTime = '-';
                $percentage = '-';
            }
            $preTime = $data['time'];
            if (isset($preMemory)) {
                $memoryDiff = '<span style="color:green; font-size: 0.8em">(+' . $this->isFile->fromBytes($data['memory'] - $preMemory) . ')</span>';
            } else {
                $memoryDiff = '';
            }
            $preMemory = $data['memory'];

            $code .= '<tr>'
                   . '<th>' . $name . '</th>'
                   . '<td>' . $data['time'] . '</td>'
                   . '<td>' . $elapsedTime . '</td>'
                   . '<td>' . $percentage . '</td>'
                   . '<td>' . $this->isFile->fromBytes($data['memory']) . $memoryDiff . '</td>'
                   . '</tr>';
        }
        $code .= '</tbody></table>';

        if ($print) {
            echo $code;

            return $this;
        } else {
            return $code;
        }
    }
}
