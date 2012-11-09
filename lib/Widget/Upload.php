<?php
/**
 * Controller
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
 * @since       2010-05-27 07:56:33
 */

namespace Widget;

class Upload extends WidgetProvider
{
    public $options = array(
        'name' => null,
        'maxSize' => 0,
        'exts' => array(),
        'savedName' => null,
        'savedDir' => './uploads',
        //to
    );

    public function __invoke(array $options = array())
    {
        $options = (array) $options + $this->options;
        $this->options = &$options;

        $this->option($options);

        if (!isset($_FILES[$options['name']])) {
            return array(
                'code' => -1,
                'message' => 'No file uploaded',
            );
        }

        $tmpFile = $_FILES[$options['name']]['tmp_name'];

        if (!is_uploaded_file($tmpFile)) {
            return array(
                'code' => -2,
                'message' => 'No file uploaded',
            );
        }

        if ($options['maxSize'] && $_FILES[$options['name']]['size'] > $options['maxSize']) {
            return array(
                'code' => -3,
                'message' => 'File too big',
            );
        }

        $ext = pathinfo($_FILES[$options['name']]['name'], PATHINFO_EXTENSION);
        if ($options['exts'] && !in_array($ext, (array) $options['exts'])) {
            return array(
                'code' => -4,
                'message' => 'Invalid File Extension',
            );
        }

        // todo: callable
        if ($options['savedName']) {
            $savedName = $options['savedName'] . '.' . $ext;
        } else {
            $savedName = $_FILES[$options['name']]['name'];
        }

        if (!is_dir($options['savedDir'])) {
            mkdir($options['savedDir'], 0700, true);
        }

        if (@!move_uploaded_file($tmpFile, $options['savedDir'] . '/' . $savedName)) {
            return array(
                'code' => -5,
                'message' => 'Can not move uploaded file',
            );
        }

        return array(
            'code' => 0,
            'message' => 'File uploaded!',
        );
    }

    public function setNameOption($name)
    {
        $this->options['name'] = $name ? $name : key($_FILES);

        return $this;
    }
}
