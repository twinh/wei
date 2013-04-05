<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * The download widget
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 * @property    \Widget\Browser    $browser The browser widget
 * @property    \Widget\Header     $header The header widget
 */
class Download extends Response
{
    /**
     * The HTTP content type
     *
     * @var string
     */
    protected $type = 'application/x-download';

    /**
     * Send file download response
     */
    public function __invoke($file = null, $options = array())
    {
        return $this->send($file, $options);
    }
    
    /**
     * Send file download response
     * 
     * @param string $file The path of file
     * @param array $options The widget options
     * @return \Widget\Download
     * @throws Exception\NotFoundException When file not found
     */
    public function send($file = null, $options = array())
    {
        $options && $this->setOption($options);
        
        if (!is_file($file)) {
            throw new Exception\NotFoundException('File not found');
        }

        $name = basename($file);
        $this->browser->msie && $name = urlencode($name);
        
        $this->header->set(array(
            'Content-Description'       => 'File Transfer',
            'Content-Type'              => $this->type,
            'Content-Disposition'       => 'attachment;filename="' . $name,
            'Content-Transfer-Encoding' => 'binary',
            'Expires'                   => '0',
            'Cache-Control'             => 'must-revalidate',
            'Pragma'                    => 'public',
            'Content-Length'            => filesize($file),
        ));
        
        // Send headers
        parent::send();
        
        // Send file content
        readfile($file);
        
        return $this;
    }
}
