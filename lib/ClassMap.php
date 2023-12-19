<?php

namespace Wei;

/**
 * Generate class map from specified directory and pattern
 */
class ClassMap extends Base
{
    /**
     * 重复类的对应表
     *
     * @var array
     */
    protected $duplicates = [];

    /**
     * Generate class map
     *
     * @param string|array $dirs
     * @param string|array $pattern
     * @param string|array $type
     * @param bool $ignoreProject
     * @param bool $ignoreType
     * @return array
     * @throws \Exception
     */
    public function generate($dirs, $pattern, $type, $ignoreProject = true, $ignoreType = false)
    {
        $dirs = (array) $dirs;
        $patterns = (array) $pattern;
        $types = (array) $type;

        $map = [];
        foreach ($patterns as $i => $pattern) {
            $files = $this->globByDirs($dirs, $pattern);
            foreach ($files as $file) {
                $class = $this->guessClassName($file, $ignoreProject);
                if (!$class) {
                    continue;
                }

                $reflection = new \ReflectionClass($class);
                if ($reflection->isAbstract()) {
                    continue;
                }

                // @experimental may be change to @wei-ignored
                if (false !== strpos($reflection->getDocComment(), '@ignored')) {
                    continue;
                }

                if ($type) {
                    $name = $this->getShortName($class, $types[$i], $ignoreType);
                } else {
                    $name = $class;
                }

                $this->addDuplicates($map, $name, $class);
                $map[$name] = $class;
            }
        }

        ksort($map);

        // Display first type name
        return $this->filterDuplicates($map, lcfirst(is_array($type) ? current($type) : $type));
    }

    /**
     * Guest class name by file name
     *
     * @param string $file
     * @param bool $ignoreProject
     * @return string
     * @throws \Exception
     */
    protected function guessClassName($file, $ignoreProject = false)
    {
        // 假设为根目录
        if ('.' === $file[0]) {
            $file = '\\' . ltrim($file, './');
        }

        list($dir, $className) = explode('src/', $file);

        $composerJson = ($dir ? ($dir . '/') : '') . 'composer.json';
        if (!is_file($composerJson)) {
            throw new \Exception(sprintf('Composer file "%s" not found', $composerJson));
        }

        $json = json_decode(file_get_contents($composerJson), true);
        if ($ignoreProject && isset($json['type']) && 'project' == $json['type']) {
            return false;
        }

        if (!isset($json['autoload']['psr-4']) || !$json['autoload']['psr-4']) {
            throw new \Exception('Missing psr-4 autoload config');
        }

        $namespace = key($json['autoload']['psr-4']);
        $className = rtrim($namespace, '\\') . '\\' . $className;

        // 移除结尾的.php扩展名,并替换目录为命名空间分隔符
        return strtr(substr($className, 0, -4), ['/' => '\\']);
    }

    /**
     * Return short name of class by specified type
     *
     * @param string $class
     * @param string $type
     * @param bool $ignoreType
     * @return string
     */
    protected function getShortName($class, $type, $ignoreType = false)
    {
        // 获取类名中,类型之后的半段
        // 如Miaoxing\User\Controller\Admins\User返回Admin\User
        $name = explode('\\' . $type . '\\', $class, 2)[1];

        // 将名称转换为小写
        $pos = strrpos($name, '\\');
        $pos = false === $pos ? 0 : $pos + 1;
        $name[$pos] = lcfirst($name[$pos]);
        $name = lcfirst($name);

        // 忽略结尾的类型
        if ($ignoreType) {
            $pos = strrpos($name, $type);
            if (false !== $pos) {
                $name = substr($name, 0, $pos);
            }
        }

        return $name;
    }

    /**
     * Find files matching a pattern in specified directories
     *
     * @param array $dirs
     * @param string $pattern
     * @return array
     */
    protected function globByDirs(array $dirs, $pattern)
    {
        $dirs = implode(',', $dirs);
        $pattern = '{' . $dirs . '}' . $pattern;

        return glob($pattern, \GLOB_BRACE | \GLOB_NOSORT);
    }

    /**
     * 记录重复的类名
     *
     * @param array $map 类名和短名称的映射表
     * @param string $name 类名对应的短名称
     * @param string $class 完整类名
     */
    protected function addDuplicates(array $map, $name, $class)
    {
        if (isset($map[$name])) {
            $this->duplicates[$name][$map[$name]] = true;
            $this->duplicates[$name][$class] = true;
        }
    }

    /**
     * 通过继承关系,过滤重复的子类
     * 如果还剩重复的类,抛出异常
     *
     * @param array $map
     * @param string $mapName
     * @return array
     */
    protected function filterDuplicates(array $map, $mapName)
    {
        foreach ($this->duplicates as $name => $classes) {
            foreach ($classes as $class => $flag) {
                // Remove parent classes until there is only one class
                $parent = get_parent_class($class);
                if ($parent && isset($classes[$parent])) {
                    unset($classes[$parent]);
                }
            }

            if (1 === count($classes)) {
                $map[$name] = key($classes);
                unset($this->duplicates[$name]);
            } else {
                $this->duplicates[$name] = $classes;
            }
        }

        foreach ($this->duplicates as $name => $classes) {
            throw new \RuntimeException(sprintf(
                'Duplicate class for %s "%s", the classes are %s',
                $mapName,
                $name,
                implode(', ', array_keys($classes))
            ));
        }

        return $map;
    }
}
