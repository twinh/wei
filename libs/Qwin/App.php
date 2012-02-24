<?php
/**
 * Qwin Framework
 *
 * Copyright (c) 2008-2012 Twin Huang. All rights reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 */

/**
 * App
 *
 * @package     Qwin
 * @subpackage  Application
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2009-11-24 20:45:11
 */
class Qwin_App extends Qwin_Widget
{
    /**
     * @var array           options
     *
     *       dirs           the root directories of the applications
     *
     *       module         the default module name
     *
     *       action         the default action name
     */
    public $options = array(
        'dirs'      => array(),
        'module'    => null,
        'action'    => null,
    );

    /**
     * Startup application
     *
     * @param array $options options
     * @return Qwin_App
     */
    public function __invoke(array $options = array())
    {
        // merge options
        $this->option($options);
        $options = &$this->options;

        // get request & action
        $module = isset($options['module']) ? $options['module']: $this->module();
        $action = isset($options['action']) ? $options['action']: $this->action();

        // execute controller action
        $result = $this->controller(array(
            'module' => $module,
            'action' => $action,
        ));

        // display view
        $this->view(array(
            'module' => $module,
            'action' => $action,
            'result' => $result,
        ));

        return $this;
    }

    /**
     * Set application directories
     *
     * @param array $dirs
     * @return Qwin_App
     */
    public function setDirsOption($dirs)
    {
        if (empty($dirs)) {
            $this->options['dirs'] = array(dirname(dirname(dirname(__FILE__))) . '/apps');
        } else {
            $this->options['dirs'] = (array)$dirs;
        }
        return $this;
    }
}