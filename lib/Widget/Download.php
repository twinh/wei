<?php

/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */
namespace Widget;

/**
 * Description of Download
 *
 * @author twinhuang
 * @property \Widget\Browser $browser The browser widget
 * @property \Widget\Header $header The header widget
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
     * @return Download The current object
     * @throws \Widget\Exception When file not found
     */
    public function __invoke($file, array $options = array())
    {
        $this->option($options);

        $header = $this->header;

        if (!is_file($file)) {
            throw new Exception('File not found');
        }

        $header('Content-Description', 'File Transfer');

        if ($this->type) {
            $header('Content-Type', $this->type);
        }

        $name = basename($file);
        $this->browser->msie && $name = urlencode($name);
        $header('Content-Disposition', 'attachment;filename="' . $name);
        $header('Content-Transfer-Encoding', 'binary');
        $header('Expires', '0');
        $header('Cache-Control', 'must-revalidate');
        $header('Pragma', 'public');
        $header('Content-Length', filesize($file));

        $this->response->send();

        readfile($file);

        return $this;
    }
}
