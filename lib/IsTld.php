<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is a valid top-level domain
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsTld extends IsIn
{
    public const VALID_TYPE = 'string';

    protected $notInMessage = '%name% must be a valid top-level domain';

    protected $negativeMessage = '%name% must not a valid top-level domain';

    /**
     * A list of all valid top-level domains
     *
     * @var array
     * @link http://www.icann.org/en/resources/registries/tlds
     */
    protected $array = [
        'ac', 'ad', 'ae', 'aero', 'af', 'ag', 'ai', 'al', 'am', 'an', 'ao',
        'aq', 'ar', 'arpa', 'as', 'asia', 'at', 'au', 'aw', 'ax', 'az', 'ba',
        'bb', 'bd', 'be', 'bf', 'bg', 'bh', 'bi', 'biz', 'bj', 'bm', 'bn', 'bo',
        'br', 'bs', 'bt', 'bv', 'bw', 'by', 'bz', 'ca', 'cat', 'cc', 'cd', 'cf',
        'cg', 'ch', 'ci', 'ck', 'cl', 'cm', 'cn', 'co', 'com', 'coop', 'cr',
        'cu', 'cv', 'cw', 'cx', 'cy', 'cz', 'de', 'dj', 'dk', 'dm', 'do', 'dz',
        'ec', 'edu', 'ee', 'eg', 'er', 'es', 'et', 'eu', 'fi', 'fj', 'fk', 'fm',
        'fo', 'fr', 'ga', 'gb', 'gd', 'ge', 'gf', 'gg', 'gh', 'gi', 'gl', 'gm',
        'gn', 'gov', 'gp', 'gq', 'gr', 'gs', 'gt', 'gu', 'gw', 'gy', 'hk', 'hm',
        'hn', 'hr', 'ht', 'hu', 'id', 'ie', 'il', 'im', 'in', 'info', 'int',
        'io', 'iq', 'ir', 'is', 'it', 'je', 'jm', 'jo', 'jobs', 'jp', 'ke',
        'kg', 'kh', 'ki', 'km', 'kn', 'kp', 'kr', 'kw', 'ky', 'kz', 'la', 'lb',
        'lc', 'li', 'lk', 'lr', 'ls', 'lt', 'lu', 'lv', 'ly', 'ma', 'mc', 'md',
        'me', 'mg', 'mh', 'mil', 'mk', 'ml', 'mm', 'mn', 'mo', 'mobi', 'mp',
        'mq', 'mr', 'ms', 'mt', 'mu', 'museum', 'mv', 'mw', 'mx', 'my', 'mz',
        'na', 'name', 'nc', 'ne', 'net', 'nf', 'ng', 'ni', 'nl', 'no', 'np',
        'nr', 'nu', 'nz', 'om', 'org', 'pa', 'pe', 'pf', 'pg', 'ph', 'pk', 'pl',
        'pm', 'pn', 'post', 'pr', 'pro', 'ps', 'pt', 'pw', 'py', 'qa', 're',
        'ro', 'rs', 'ru', 'rw', 'sa', 'sb', 'sc', 'sd', 'se', 'sg', 'sh', 'si',
        'sj', 'sk', 'sl', 'sm', 'sn', 'so', 'sr', 'st', 'su', 'sv', 'sx', 'sy',
        'sz', 'tc', 'td', 'tel', 'tf', 'tg', 'th', 'tj', 'tk', 'tl', 'tm', 'tn',
        'to', 'tp', 'tr', 'travel', 'tt', 'tv', 'tw', 'tz', 'ua', 'ug', 'uk',
        'us', 'uy', 'uz', 'va', 'vc', 've', 'vg', 'vi', 'vn', 'vu', 'wf', 'ws',
        'xn--0zwm56d', 'xn--11b5bs3a9aj6g', 'xn--3e0b707e', 'xn--45brj9c',
        'xn--80akhbyknj4f', 'xn--80ao21a', 'xn--90a3ac', 'xn--9t4b11yi5a',
        'xn--clchc0ea0b2g2a9gcd', 'xn--deba0ad', 'xn--fiqs8s', 'xn--fiqz9s',
        'xn--fpcrj9c3d', 'xn--fzc2c9e2c', 'xn--g6w251d', 'xn--gecrj9c',
        'xn--h2brj9c', 'xn--hgbk6aj7f53bba', 'xn--hlcj6aya9esc7a',
        'xn--j6w193g', 'xn--jxalpdlp', 'xn--kgbechtv', 'xn--kprw13d',
        'xn--kpry57d', 'xn--lgbbat1ad8j', 'xn--mgb9awbf', 'xn--mgbaam7a8h',
        'xn--mgbayh7gpa', 'xn--mgbbh1a71e', 'xn--mgbc0a9azcg',
        'xn--mgberp4a5d4ar', 'xn--mgbx4cd0ab', 'xn--o3cw4h', 'xn--ogbpf8fl',
        'xn--p1ai', 'xn--pgbs0dh', 'xn--s9brj9c', 'xn--wgbh1c', 'xn--wgbl6a',
        'xn--xkc2al3hye2a', 'xn--xkc2dl3a5ee0h', 'xn--yfro4i67o',
        'xn--ygbi2ammx', 'xn--zckzah', 'xxx', 'ye', 'yt', 'za', 'zm', 'zw',
    ];

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        is_string($input) && $input = strtolower($input);

        return parent::doValidate($input);
    }
}
