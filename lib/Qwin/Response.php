<?php
/**
 * Qwin Library
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Qwin;

/**
 * Response
 *
 * @package     Qwin
 * @author      Twin Huang <twinh@yahoo.cn>
 * @todo        Filterable ? filter response
 */
class Response extends Widget
{
    /**
     * Whether response content has been sent
     * 
     * @var bool
     */
    protected $isSent = false;
    
    /**
     * The response content
     * 
     * @var string
     */
    protected $content;
    
    /**
     * Send response header and content
     * 
     * @param string $content
     * @param int $status
     * @param array $options
     * @return \Qwin\Response
     */
    public function __invoke($content = '', $status = 200, array $options = array())
    {
        $this->isSent = true;
        
        $this->content = $this->filter('response', $content);
        
        $this->header->setStatusCode($status);
        
        $this->header->send();
        
        $this->sendContent();
        
        return $this;
    }
    
    /**
     * Set response content
     * 
     * @param mixed $content
     * @return \Qwin\Response
     */
    public function setContent($content)
    {
        $this->content = $content;
        
        return $this;
    }
    
    /**
     * Get response content
     * 
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Send response content
     */
    public function sendContent()
    {
        echo $this->content;
    }
    
    /**
     * @see \Qwin\Response::__invoke
     */
    public function send($content = '', $status = 200)
    {
        return $this->__invoke($content, $status);
    }
    
    /**
     * Check if response has been sent
     * 
     * @return bool
     */
    public function isSent()
    {
        return $this->isSent;
    }
    
    /**
     * Set response sent status
     * 
     * @param bool $bool
     * @return \Qwin\Response
     */
    public function setSentStatus($bool)
    {
        $this->isSent = (bool)$bool;
        
        return $this;
    }
}