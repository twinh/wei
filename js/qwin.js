/**
 * qwin
 *
 * Copyright (c) 2008-2011 Twin Huang. All rights reserved.
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
 * @since       2011-04-05 03:20:19
 */

var Qwin = {
    App: {},
    Lang: {}
};

// JavaScript Document 中文 2010 1 20 - 30
Qwin.urlSeparator = {
    0: '&',
    1: '='
};
Qwin.url = {
    createUrl : function(array1, array2)
    {
        // TODO: 合并数组1和2
        if ('undefined' != typeof(array2)) {
            for(var i in array2) {
                array1[i] = array2[i];
            }
        }
        return '?' + this.arrayKey2Url(array1);
    },
    arrayKey2Url : function(arr) {
        var url = '';
        for (var i in arr) {
            url += this.array2Url(arr[i], i) + Qwin.urlSeparator[0];
        }
        return url.slice(0, -1);
    },
    array2Url : function(arr, name)
    {
        var url = '';
        if ('object' == typeof(arr)) {
            for (var key in arr) {
                if ('object' == typeof(arr[key])) {
                    url += this.array2Url(arr[key], name + '[' + key + ']') + Qwin.urlSeparator[0];
                } else if(name) {
                    url += name + '[' + key + ']' + Qwin.urlSeparator[1] + arr[key] + Qwin.urlSeparator[0];

                } else {
                    url += name + Qwin.urlSeparator[1] + arr[key] + Qwin.urlSeparator[0];
                }
            }
        } else {
            return name + Qwin.urlSeparator[1] + arr;
        }
        return url.slice(0, -1);
    }
};