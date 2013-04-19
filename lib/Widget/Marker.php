<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * Marker
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Marker extends AbstractWidget
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
     * @param  string|null      $name The name of marker
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
     * Returns the processed data
     * 
     * @return array
     */
    public function getProcessedData()
    {
        $start          = current($this->data);
        $end            = end($this->data);
        // If start time === end time, total time would be '0.0000'
        $totalTime      = bcsub($end['time'], $start['time'], 4);
        $totalMemory    = bcsub($end['memory'], $start['memory']);
        $prevData       = array();
        $result         = array();

        foreach ($this->data as $name => $data) {
            if ($prevData) {
                $data['fullName']           = $prevData['name'] . '~' . $name;
                $data['elapsedTime']        = bcsub($data['time'], $prevData['time'], 4);
                $data['timePercentage']     = '0.0000' === $totalTime ? 0 : bcmul(bcdiv($data['elapsedTime'], $totalTime, 4), 100, 2) . '%';
                $data['increasedMemory']    = $this->fromBytes($data['memory'] - $prevData['memory']);
                $data['memoryPercentage']   = bcmul(bcdiv(bcsub($data['memory'], $prevData['memory']), $totalMemory, 4), 100, 2) . '%';
            } else {
                $data['fullName']           = $name;
                $data['elapsedTime']        = 0;
                $data['timePercentage']     = '-';
                $data['increasedMemory']    = 0;
                $data['memoryPercentage']   = '-';
            }
            $data['name']                   = $name;
            $data['formatedTime']           = date('i:s', $data['time']) . '.' . substr($data['time'], 11) . 's';
            $data['formatedMemory']         = $this->fromBytes($data['memory']);
            $result[] = $prevData = $data;
        }
        return $result;
    }
    
    /**
     * Formats bytes to human readable file size (e.g. 1.2MB, 10KB)
     * 
     * @param int $bytes
     * @return string
     */
    protected function fromBytes($bytes)
    {
        $units = array('B', 'KB', 'MB', 'GB');
        for ($i=0; $bytes >= 1024 && $i < 8; $i++) {
            $bytes /= 1024;
        }
        return round($bytes, 2) . $units[$i];
    }
    

    /**
     * Display profiling data in html code
     *
     * @param  boolean|string $print Whether output or return the result
     * @return \Widget\Marker|string
     */
    public function display($print = true)
    {
        $data = $this->getProcessedData();

        $code = '<table class="table table-bordered table-hover marker-table">'
              . '<thead><tr>'
              . '<th>Marker</th>'
              . '<th>Time</th>'
              . '<th>%</th>'
              . '<th>Memory Usage</th>'
              . '<th>%</th>'
              . '</tr>'
              . '</thead>'
              . '<tbody>';
        
        foreach($data as $row) {
            $code .= '<tr>'
                   . '<th>' . $row['fullName'] . '</th>'
                   . '<td>' . $row['formatedTime'] . ($row['elapsedTime'] ? '<span style="color:green; font-size: 0.8em">(+' . $row['elapsedTime'] . 's)' : '') . '</span></td>'
                   . '<td>' . $row['timePercentage'] . '</td>'
                   . '<td>' . $row['formatedMemory'] . ($row['increasedMemory'] ? '<span style="color:green; font-size: 0.8em">(+' . $row['increasedMemory'] . ')' : '') . '</span></td>'
                   . '<td>' . $row['memoryPercentage'] . '</td>'
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
