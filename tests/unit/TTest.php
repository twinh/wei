<?php

namespace WeiTest;

/**
 * @property \Wei\T $t The translator wei
 *
 * @internal
 */
final class TTest extends TestCase
{
    public function testLoadFromArray()
    {
        $this->t->loadFromArray([
            'key' => 'value',
        ]);

        $this->assertEquals('value', $this->t('key'));
        $this->assertEquals('value', $this->t->trans('key'));
    }

    public function testLoad()
    {
        $this->t->load(function () {
            return [
                'key1' => 'value1',
            ];
        });

        $this->assertEquals('value1', $this->t('key1'));
        $this->assertEquals('value1', $this->t->trans('key1'));
    }

    public function testLoadFromFile()
    {
        $this->t->setLocale('en');

        $this->t->loadFromFile(__DIR__ . '/Fixtures/i18n/%s.php');

        $this->assertEquals('value', $this->t('key'));

        // load again
        $this->t->loadFromFile(__DIR__ . '/Fixtures/i18n/%s.php');
    }

    public function testLoadFromDefaultLocale()
    {
        $this->t->setLocale('zh-CN');

        $this->t->loadFromFile(__DIR__ . '/Fixtures/i18n/%s.php');

        $this->assertEquals('value', $this->t('key'));
    }

    public function testDefaultLocale()
    {
        $this->assertEquals('en', $this->t->getDefaultLocale());

        $this->t->setDefaultLocale('zh-CN');

        $this->assertEquals('zh-CN', $this->t->getDefaultLocale());
    }

    public function testLocale()
    {
        $this->assertEquals('en', $this->t->getLocale());

        $this->t->setLocale('zh-CN');

        $this->assertEquals('zh-CN', $this->t->getLocale());
    }

    public function testFileNotFound()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->t->loadFromFile('not found file for locale %s');
    }
}
