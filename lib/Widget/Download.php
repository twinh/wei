<?php

/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */
namespace Widget;

/**
 * Description of Download
 *
 * @author twinhuang
 * @property \Widget\Browser $browser The browser widget
 * @todo refactor
 */
class Download extends WidgetProvider
{
    /**
     * The http content type
     * 
     * @var string
     */
    protected $type;
    
    /**
     * Download a file form server
     * 
     * @param string $file The file path
     * @param array $options The property options
     * @return \Widget\Download The current object
     * @throws Exception When file not found
     */
    public function __invoke($file, array $options = array())
    {
        $this->option($options);
        
        if (!is_file($file)) {
            throw new Exception('File not found');
        }

        $this->header('Content-Description', 'File Transfer');
        
        if ($this->type) {
            $this->header('Content-Type', $this->type);
        }

        $name = basename($file);
        $this->browser->msie && $name = urlencode($name);
        $this->header('Content-Disposition', 'attachment;filename="' . $name);
        $this->header('Content-Transfer-Encoding', 'binary');
        $this->header('Expires', '0');
        $this->header('Cache-Control', 'must-revalidate');
        $this->header('Pragma', 'public');
        $this->header('Content-Length', filesize($file));
        
        $this->response->send();
        
        readfile($file);
        
        return $this;
    }
}