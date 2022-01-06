<?php

namespace services
{
    /**
     * The base class for all services, including database models
     */
    class Base extends \Wei\Base
    {
    }
}

namespace
{
    if (!function_exists('wei')) {
        /**
         * @return \services\Base
         */
        function wei()
        {
        }
    }
}
