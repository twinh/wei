<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * A wrapper class for password hashing functions
 *
 * If you needs these original functions, please checkout
 * https://github.com/ircmaxell/password_compat instead.
 *
 * - password_hash
 * - password_get_info
 * - password_needs_rehash
 * - password_verify
 *
 * @author      Anthony Ferrara <ircmaxell@php.net>
 * @author      https://github.com/ircmaxell/password_compat/graphs/contributors
 * @author      Twin Huang <twinhuang@qq.com>
 * @link        https://github.com/ircmaxell/password_compat/blob/master/lib/password.php
 */
class Password extends Base
{
    /**
     * The cost parameter for bcrypt
     *
     * @var int
     */
    protected $cost = 10;

    /**
     * Constructor
     *
     * @param array $options
     * @throws \Exception
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        if (!defined('PASSWORD_BCRYPT')) {
            define('PASSWORD_BCRYPT', 1);
            define('PASSWORD_DEFAULT', PASSWORD_BCRYPT);
        }
    }

    /**
     * Hash the password using the specified algorithm
     *
     * @param string $password The password to hash
     * @param string $salt The salt string for the algorithm to use
     * @throws \InvalidArgumentException
     * @return string|false The hashed password, or false on error.
     */
    public function hash($password, $salt = null)
    {
        $hash_format = sprintf("$2y$%02d$", $this->cost);

        !$salt && $salt = $this->generateSalt();
        if (strlen($salt) < 22) {
            throw new \InvalidArgumentException(sprintf("Provided salt is too short: %d expecting %d", strlen($salt), 22));
        }

        $hash = $hash_format . $salt;
        $ret = crypt($password, $hash);

        if (!is_string($ret) || strlen($ret) <= 13) {
            return false;
        }

        return $ret;
    }

    /**
     * Get information about the password hash. Returns an array of the information
     * that was used to generate the password hash.
     *
     * array(
     *    'algo' => 1,
     *    'algoName' => 'bcrypt',
     *    'options' => array(
     *        'cost' => 10,
     *    ),
     * )
     *
     * @param string $hash The password hash to extract info from
     *
     * @return array The array of information about the hash.
     */
    public function getInfo($hash)
    {
        $return = array(
            'algo' => 0,
            'algoName' => 'unknown',
            'options' => array(),
        );
        if (substr($hash, 0, 4) == '$2y$' && strlen($hash) == 60) {
            $return['algo'] = PASSWORD_BCRYPT;
            $return['algoName'] = 'bcrypt';
            list($cost) = sscanf($hash, "$2y$%d$");
            $return['options']['cost'] = $cost;
        }
        return $return;
    }

    /**
     * Determine if the password hash needs to be rehashed according to the options provided
     *
     * If the answer is true, after validating the password using password_verify, rehash it.
     *
     * @param string $hash The hash to test
     * @param int $algo The algorithm used for new password hashes
     * @param array $options The options array passed to password_hash
     *
     * @return boolean True if the password needs to be rehashed.
     */
    public function needsRehash($hash, $algo, array $options = array())
    {
        $info = $this->getInfo($hash);
        if ($info['algo'] != $algo) {
            return true;
        }
        switch ($algo) {
            case PASSWORD_BCRYPT:
                $cost = isset($options['cost']) ? $options['cost'] : 10;
                if ($cost != $info['options']['cost']) {
                    return true;
                }
                break;
        }
        return false;
    }

    /**
     * Verify a password against a hash using a timing attack resistant approach
     *
     * @param string $password The password to verify
     * @param string $hash     The hash to verify against
     *
     * @return boolean If the password matches the hash
     */
    public function verify($password, $hash)
    {
        $ret = crypt($password, $hash);
        if (!is_string($ret) || strlen($ret) != strlen($hash) || strlen($ret) <= 13) {
            return false;
        }

        $status = 0;
        for ($i = 0; $i < strlen($ret); $i++) {
            $status |= (ord($ret[$i]) ^ ord($hash[$i]));
        }

        return $status === 0;
    }

    /**
     * Set the cost parameter for bcrypt
     *
     * @param int $cost
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setCost($cost)
    {
        if ($cost < 4 || $cost > 31) {
            throw new \InvalidArgumentException(sprintf("Invalid bcrypt cost parameter specified: %s", $cost));
        }
        $this->cost = $cost;
        return $this;
    }

    /**
     * Generate a 22 bytes salt string
     *
     * @return string
     */
    public function generateSalt()
    {
        // The length of salt to generate
        $raw_salt_len = 16;
        // The length required in the final serialization
        $required_salt_len = 22;

        $buffer = mcrypt_create_iv($raw_salt_len, MCRYPT_DEV_URANDOM);
        $salt = str_replace('+', '.', base64_encode($buffer));
        $salt = substr($salt, 0, $required_salt_len);

        return $salt;
    }
}
