<?php
/**
 * Qwin Framework
 * 
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Qwin;

/**
 * Get full url
 *
 * @package     Qwin
 * @author      Twin Huang <twinh@yahoo.cn>
 * @see         http://snipplr.com/view.php?codeview&id=2734
 */
class FullUrl extends Widget
{
    public function __invoke()
    {
        $s = $this->server['HTTPS'] == 'on' ? 's' : '';
        $protocol = substr(strtolower($this->server['SERVER_PROTOCOL']), 0, strpos(strtolower($this->server['SERVER_PROTOCOL']), '/')) . $s;
        $port = ($this->server['SERVER_PORT'] == '80') ? '' : (':' . $this->server['SERVER_PORT']);
	return $protocol . '://' . $this->server('SERVER_NAME') . $port . $this->server['REQUEST_URI'];
    }
}