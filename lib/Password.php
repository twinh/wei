<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * A wrapper class for password hashing functions
 *
 * @author      Anthony Ferrara <ircmaxell@php.net>
 * @author      https://github.com/ircmaxell/password_compat/graphs/contributors
 * @author      Twin Huang <twinhuang@qq.com>
 * @link        https://github.com/ircmaxell/password_compat/blob/master/lib/password.php
 */
class Password extends Base
{
    /**
     * The algorithm used for new password hashes
     *
     * @var string
     */
    protected $algo = PASSWORD_DEFAULT;

    /**
     * The options array passed to password_hash
     *
     * @var array
     */
    protected $options = [
        'cost' => 10,
    ];

    /**
     * Hash the password using the specified algorithm
     *
     * @param string $password The password to hash
     * @return string|false The hashed password, or false on error.
     * @throws \InvalidArgumentException
     * @svc
     */
    protected function hash($password)
    {
        return password_hash($password, $this->algo, $this->options);
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
     * @return array The array of information about the hash.
     * @svc
     */
    protected function getInfo($hash)
    {
        return password_get_info($hash);
    }

    /**
     * Determine if the password hash needs to be rehashed according to the options provided
     *
     * If the answer is true, after validating the password using password_verify, rehash it.
     *
     * @param string $hash The hash to test
     * @return boolean True if the password needs to be rehashed.
     * @svc
     */
    protected function needsRehash($hash)
    {
        return password_needs_rehash($hash, $this->algo, $this->options);
    }

    /**
     * Verify a password against a hash using a timing attack resistant approach
     *
     * @param string $password The password to verify
     * @param string $hash The hash to verify against
     * @return boolean If the password matches the hash
     * @svc
     */
    protected function verify($password, $hash)
    {
        return password_verify($password, $hash);
    }

    /**
     * Set the cost parameter for bcrypt
     *
     * @param int $cost
     * @return $this
     * @throws \InvalidArgumentException
     * @deprecated
     */
    public function setCost($cost)
    {
        if ($cost < 4 || $cost > 31) {
            throw new \InvalidArgumentException(sprintf("Invalid bcrypt cost parameter specified: %s", $cost));
        }
        $this->options['cost'] = $cost;
        return $this;
    }

    /**
     * Generate a 22 bytes salt string
     *
     * @return string
     * @deprecated
     */
    public function generateSalt()
    {
        // The length of salt to generate
        $raw_salt_len = 16;
        // The length required in the final serialization
        $required_salt_len = 22;

        $buffer = function_exists('random_bytes') ? random_bytes($raw_salt_len) :
            mcrypt_create_iv($raw_salt_len, MCRYPT_DEV_URANDOM);
        $salt = str_replace('+', '.', base64_encode($buffer));
        $salt = substr($salt, 0, $required_salt_len);

        return $salt;
    }
}
