<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

/**
 * A widget send file download response
 *
 * @author      Twin Huang <twinhuang@qq.com>
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
     * The type of disposition, could be "attachment" or "inline"
     *
     * With inline, the browser will try to open file within the browser, while attachment will force it to download
     *
     * @var string
     * @link http://stackoverflow.com/questions/1395151/content-dispositionwhat-are-the-differences-between-inline-and-attachment
     */
    protected $disposition = 'attachment';

    /**
     * The file name to display in download dialog
     *
     * @var string
     */
    protected $filename;

    /**
     * The server and execution environment parameters, equals to $_SERVER on default
     *
     * @var array
     */
    protected $server;

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        if (!$this->server) {
            $this->server = $_SERVER;
        }
    }

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
     * @return Download
     * @throws \RuntimeException When file not found
     */
    public function send($file = null, $options = array())
    {
        $options && $this->setOption($options);

        if (!is_file($file)) {
            throw new \RuntimeException('File not found', 404);
        }

        $name = $this->filename ?: basename($file);

        // For IE
        $userAgent = isset($this->server['HTTP_USER_AGENT']) ? $this->server['HTTP_USER_AGENT'] : '';
        if (preg_match('/MSIE ([\w.]+)/', $userAgent)) {
            $filename = '=' . rawurlencode($name);
        } else {
            $filename = "*=UTF-8''" . rawurlencode($name);
        }

        $this->header->set(array(
            'Content-Description'       => 'File Transfer',
            'Content-Type'              => $this->type,
            'Content-Disposition'       => $this->disposition . ';filename' . $filename,
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
