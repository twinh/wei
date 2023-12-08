<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace WeiTest;

use Wei\E as Escaper;

/**
 * The test case derived from https://raw.github.com/zendframework/zf2/master/tests/ZendTest/Escaper/EscaperTest.php
 *
 * @internal
 */
final class ETest extends TestCase
{
    /**
     * All character encodings supported by htmlspecialchars()
     */
    protected $supportedEncodings = [
        'iso-8859-1',   'iso8859-1',    'iso-8859-5',   'iso8859-5',
        'iso-8859-15',  'iso8859-15',   'utf-8',        'cp866',
        'ibm866',       '866',          'cp1251',       'windows-1251',
        'win-1251',     '1251',         'cp1252',       'windows-1252',
        '1252',         'koi8-r',       'koi8-ru',      'koi8r',
        'big5',         '950',          'gb2312',       '936',
        'big5-hkscs',   'shift_jis',    'sjis',         'sjis-win',
        'cp932',        '932',          'euc-jp',       'eucjp',
        'eucjp-win',    'macroman',
    ];

    protected $htmlSpecialChars = [
        '\'' => '&#039;',
        '"' => '&quot;',
        '<' => '&lt;',
        '>' => '&gt;',
        '&' => '&amp;',
    ];

    protected $htmlAttrSpecialChars = [
        '\'' => '&#x27;',
        /* Characters beyond ASCII value 255 to unicode escape */
        'Ā' => '&#x0100;',
        /* Immune chars excluded */
        ',' => ',',
        '.' => '.',
        '-' => '-',
        '_' => '_',
        /* Basic alnums exluded */
        'a' => 'a',
        'A' => 'A',
        'z' => 'z',
        'Z' => 'Z',
        '0' => '0',
        '9' => '9',
        /* Basic control characters and null */
        "\r" => '&#x0D;',
        "\n" => '&#x0A;',
        "\t" => '&#x09;',
        "\0" => '&#xFFFD;', // should use Unicode replacement char
        /* Encode chars as named entities where possible */
        '<' => '&lt;',
        '>' => '&gt;',
        '&' => '&amp;',
        '"' => '&quot;',
        /* Encode spaces for quoteless attribute protection */
        ' ' => '&#x20;',
    ];

    protected $jsSpecialChars = [
        /* HTML special chars - escape without exception to hex */
        '<' => '\\x3C',
        '>' => '\\x3E',
        '\'' => '\\x27',
        '"' => '\\x22',
        '&' => '\\x26',
        /* Characters beyond ASCII value 255 to unicode escape */
        'Ā' => '\\u0100',
        /* Immune chars excluded */
        ',' => ',',
        '.' => '.',
        '_' => '_',
        /* Basic alnums exluded */
        'a' => 'a',
        'A' => 'A',
        'z' => 'z',
        'Z' => 'Z',
        '0' => '0',
        '9' => '9',
        /* Basic control characters and null */
        "\r" => '\\x0D',
        "\n" => '\\x0A',
        "\t" => '\\x09',
        "\0" => '\\x00',
        /* Encode spaces for quoteless attribute protection */
        ' ' => '\\x20',
    ];

    protected $urlSpecialChars = [
        /* HTML special chars - escape without exception to percent encoding */
        '<' => '%3C',
        '>' => '%3E',
        '\'' => '%27',
        '"' => '%22',
        '&' => '%26',
        /* Characters beyond ASCII value 255 to hex sequence */
        'Ā' => '%C4%80',
        /* Punctuation and unreserved check */
        ',' => '%2C',
        '.' => '.',
        '_' => '_',
        '-' => '-',
        ':' => '%3A',
        ';' => '%3B',
        '!' => '%21',
        /* Basic alnums excluded */
        'a' => 'a',
        'A' => 'A',
        'z' => 'z',
        'Z' => 'Z',
        '0' => '0',
        '9' => '9',
        /* Basic control characters and null */
        "\r" => '%0D',
        "\n" => '%0A',
        "\t" => '%09',
        "\0" => '%00',
        /* PHP quirks from the past */
        ' ' => '%20',
        '~' => '~',
        '+' => '%2B',
    ];

    protected $cssSpecialChars = [
        /* HTML special chars - escape without exception to hex */
        '<' => '\\3C ',
        '>' => '\\3E ',
        '\'' => '\\27 ',
        '"' => '\\22 ',
        '&' => '\\26 ',
        /* Characters beyond ASCII value 255 to unicode escape */
        'Ā' => '\\100 ',
        /* Immune chars excluded */
        ',' => '\\2C ',
        '.' => '\\2E ',
        '_' => '\\5F ',
        /* Basic alnums exluded */
        'a' => 'a',
        'A' => 'A',
        'z' => 'z',
        'Z' => 'Z',
        '0' => '0',
        '9' => '9',
        /* Basic control characters and null */
        "\r" => '\\D ',
        "\n" => '\\A ',
        "\t" => '\\9 ',
        "\0" => '\\0 ',
        /* Encode spaces for quoteless attribute protection */
        ' ' => '\\20 ',
    ];

    /**
     * @var Escaper
     */
    private $escaper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->escaper = new Escaper([
            'encoding' => 'utf-8',
        ]);
    }

    public function testSettingEncodingToEmptyStringShouldThrowException()
    {
        $this->expectException(\InvalidArgumentException::class);

        $escaper = new Escaper([
            'encoding' => '',
        ]);
    }

    public function testSettingValidEncodingShouldNotThrowExceptions()
    {
        foreach ($this->supportedEncodings as $value) {
            $escaper = new Escaper([
                'encoding' => $value,
            ]);
        }
        // make sure have test
        $this->assertNull(null);
    }

    public function testSettingEncodingToInvalidValueShouldThrowException()
    {
        $this->expectException(\InvalidArgumentException::class);

        $escaper = new Escaper([
            'encoding' => 'invalid-encoding',
        ]);
    }

    public function testReturnsEncodingFromGetter()
    {
        $this->assertEquals('utf-8', $this->escaper->getEncoding());
    }

    public function testHtmlEscapingConvertsSpecialChars()
    {
        foreach ($this->htmlSpecialChars as $key => $value) {
            $this->assertEquals(
                $value,
                $this->escaper->html($key),
                'Failed to escape: ' . $key
            );

            // call by wei
            $this->assertEquals(
                $value,
                $this->e($key, 'html')
            );
        }
    }

    public function testHtmlAttributeEscapingConvertsSpecialChars()
    {
        foreach ($this->htmlAttrSpecialChars as $key => $value) {
            $this->assertEquals(
                $value,
                $this->escaper->attr($key),
                'Failed to escape: ' . $key
            );

            // call by wei
            $this->assertEquals(
                $value,
                $this->e($key, 'attr')
            );
        }
    }

    public function testJavascriptEscapingConvertsSpecialChars()
    {
        foreach ($this->jsSpecialChars as $key => $value) {
            $this->assertEquals(
                $value,
                $this->escaper->js($key),
                'Failed to escape: ' . $key
            );
        }

        // call by wei
        $this->assertEquals(
            $value,
            $this->e($key, 'js')
        );
    }

    public function testJavascriptEscapingReturnsStringIfZeroLength()
    {
        $this->assertEquals('', $this->escaper->js(''));
    }

    public function testJavascriptEscapingReturnsStringIfContainsOnlyDigits()
    {
        $this->assertEquals('123', $this->escaper->js('123'));
    }

    public function testCssEscapingConvertsSpecialChars()
    {
        foreach ($this->cssSpecialChars as $key => $value) {
            $this->assertEquals(
                $value,
                $this->escaper->css($key),
                'Failed to escape: ' . $key
            );

            // call by wei
            $this->assertEquals(
                $value,
                $this->e($key, 'css')
            );
        }
    }

    public function testCssEscapingReturnsStringIfZeroLength()
    {
        $this->assertEquals('', $this->escaper->css(''));
    }

    public function testCssEscapingReturnsStringIfContainsOnlyDigits()
    {
        $this->assertEquals('123', $this->escaper->css('123'));
    }

    public function testUrlEscapingConvertsSpecialChars()
    {
        foreach ($this->urlSpecialChars as $key => $value) {
            $this->assertEquals(
                $value,
                $this->escaper->url($key),
                'Failed to escape: ' . $key
            );

            // call by wei
            $this->assertEquals(
                $value,
                $this->e($key, 'url')
            );
        }
    }

    /**
     * Range tests to confirm escaped range of characters is within OWASP recommendation
     */

    /**
     * Only testing the first few 2 ranges on this prot. function as that's all these
     * other range tests require
     */
    public function testUnicodeCodepointConversionToUtf8()
    {
        $expected = ' ~ޙ';
        $codepoints = [0x20, 0x7E, 0x799];
        $result = '';
        foreach ($codepoints as $value) {
            $result .= $this->codepointToUtf8($value);
        }
        $this->assertEquals($expected, $result);
    }

    public function testJavascriptEscapingEscapesOwaspRecommendedRanges()
    {
        $immune = [',', '.', '_']; // Exceptions to escaping ranges
        for ($chr = 0; $chr < 0xFF; ++$chr) {
            if (
                $chr >= 0x30 && $chr <= 0x39
                || $chr >= 0x41 && $chr <= 0x5A
                || $chr >= 0x61 && $chr <= 0x7A
            ) {
                $literal = $this->codepointToUtf8($chr);
                $this->assertEquals($literal, $this->escaper->js($literal));
            } else {
                $literal = $this->codepointToUtf8($chr);
                if (in_array($literal, $immune, true)) {
                    $this->assertEquals($literal, $this->escaper->js($literal));
                } else {
                    $this->assertNotEquals(
                        $literal,
                        $this->escaper->js($literal),
                        $literal . ' should be escaped!'
                    );
                }
            }
        }
    }

    public function testHtmlAttributeEscapingEscapesOwaspRecommendedRanges()
    {
        $immune = [',', '.', '-', '_']; // Exceptions to escaping ranges
        for ($chr = 0; $chr < 0xFF; ++$chr) {
            if (
                $chr >= 0x30 && $chr <= 0x39
                || $chr >= 0x41 && $chr <= 0x5A
                || $chr >= 0x61 && $chr <= 0x7A
            ) {
                $literal = $this->codepointToUtf8($chr);
                $this->assertEquals($literal, $this->escaper->attr($literal));
            } else {
                $literal = $this->codepointToUtf8($chr);
                if (in_array($literal, $immune, true)) {
                    $this->assertEquals($literal, $this->escaper->attr($literal));
                } else {
                    $this->assertNotEquals(
                        $literal,
                        $this->escaper->attr($literal),
                        $literal . ' should be escaped!'
                    );
                }
            }
        }
    }

    public function testCssEscapingEscapesOwaspRecommendedRanges()
    {
        $immune = []; // CSS has no exceptions to escaping ranges
        for ($chr = 0; $chr < 0xFF; ++$chr) {
            if (
                $chr >= 0x30 && $chr <= 0x39
                || $chr >= 0x41 && $chr <= 0x5A
                || $chr >= 0x61 && $chr <= 0x7A
            ) {
                $literal = $this->codepointToUtf8($chr);
                $this->assertEquals($literal, $this->escaper->css($literal));
            } else {
                $literal = $this->codepointToUtf8($chr);
                $this->assertNotEquals(
                    $literal,
                    $this->escaper->css($literal),
                    $literal . ' should be escaped!'
                );
            }
        }
    }

    public function testInvokeUnsupportedTypeShouldThrowException()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->e('string', 'unsupport-type');
    }

    public function providerForEmptyVar()
    {
        return [
            [''],
            [null],
            //array(0),
            ['0'],
        ];
    }

    /**
     * @dataProvider providerForEmptyVar
     * @param mixed $value
     */
    public function testEmptyVar($value)
    {
        foreach (['html', 'js', 'css', 'url', 'attr'] as $method) {
            $this->assertSame($value, $this->escaper->{$method}($value));
        }
    }

    /**
     * Convert a Unicode Codepoint to a literal UTF-8 character.
     *
     * @param int Unicode codepoint in hex notation
     * @param mixed $codepoint
     * @return string UTF-8 literal string
     */
    protected function codepointToUtf8($codepoint)
    {
        if ($codepoint < 0x80) {
            return chr($codepoint);
        }
        if ($codepoint < 0x800) {
            return chr($codepoint >> 6 & 0x3F | 0xC0)
                . chr($codepoint & 0x3F | 0x80);
        }
        if ($codepoint < 0x10000) {
            return chr($codepoint >> 12 & 0x0F | 0xE0)
                . chr($codepoint >> 6 & 0x3F | 0x80)
                . chr($codepoint & 0x3F | 0x80);
        }
        if ($codepoint < 0x110000) {
            return chr($codepoint >> 18 & 0x07 | 0xF0)
                . chr($codepoint >> 12 & 0x3F | 0x80)
                . chr($codepoint >> 6 & 0x3F | 0x80)
                . chr($codepoint & 0x3F | 0x80);
        }
        throw new \Exception('Codepoint requested outside of Unicode range');
    }
}
