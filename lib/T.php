<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2015 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * A translator wei
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class T extends Base
{
    /**
     * The current locale
     *
     * @var string
     */
    protected $locale = 'en';

    /**
     * The default locale
     *
     * @var string
     */
    protected $defaultLocale = 'en';

    /**
     * The translator messages
     *
     * @var array
     */
    protected $data = array();

    /**
     * The loaded translation files
     *
     * @var array
     */
    protected $files = array();

    /**
     * Translate a message
     *
     * @param string $message
     * @param array $parameters
     * @return string
     */
    public function __invoke($message, array $parameters = array())
    {
        if (isset($this->data[$message])) {
            $message = $this->data[$message];
        }
        return $parameters ? strtr($message, $parameters) : $message;
    }

    /**
     * Translates a message
     *
     * @param string $message
     * @param array $parameters
     * @return string
     */
    public function trans($message, array $parameters = array())
    {
        return $this($message, $parameters);
    }

    /**
     * Sets the current locale
     *
     * @param string $locale
     * @return $this
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * Returns the default locale
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set the fallback locale
     *
     * @param string $locale
     * @return $this
     */
    public function setDefaultLocale($locale)
    {
        $this->defaultLocale = $locale;
        return $this;
    }

    /**
     * Returns the fallback locale
     *
     * @return string
     */
    public function getDefaultLocale()
    {
        return $this->defaultLocale;
    }

    /**
     * Loads translator messages from file
     *
     * @param string $pattern The file path, which can contains %s that would be convert the current locale or fallback locale
     * @return $this
     * @throws \InvalidArgumentException When file not found or not readable
     */
    public function loadFromFile($pattern)
    {
        if (isset($this->files[$pattern])) {
            return $this;
        }

        $file = sprintf($pattern, $this->locale);
        if (!is_file($file)) {
            $defaultFile = sprintf($pattern, $this->defaultLocale);
            if (!is_file($defaultFile)) {
                throw new \InvalidArgumentException(sprintf('File "%s" and "%s" not found or not readable', $file, $defaultFile));
            } else {
                $file = $defaultFile;
            }
        }

        $this->files[$pattern] = true;

        return $this->loadFromArray(require $file);
    }

    /**
     * Loads translator messages from array
     *
     * @param array $messages
     * @return $this
     */
    public function loadFromArray(array $messages)
    {
        $this->data = $messages + $this->data;
        return $this;
    }

    /**
     * Loads translator messages from closure
     *
     * @param \Closure $fn
     * @return $this
     */
    public function load(\Closure $fn)
    {
        return $this->loadFromArray($fn());
    }
}
