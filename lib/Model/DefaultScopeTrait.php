<?php

namespace Wei\Model;

/**
 * Add default scope functions to the model
 *
 * @internal Expected to be used only by ModelTrait
 */
trait DefaultScopeTrait
{
    /**
     * @var array
     */
    protected static $defaultScopes = [];

    /**
     * @var bool
     */
    protected $applyDefaultScope = false;

    /**
     * @var array|true
     */
    protected $withoutScopes = [];

    /**
     * @param string $method
     * @return $this
     */
    public function addDefaultScope($method): self
    {
        $class = static::class;

        static::$defaultScopes[$class][$method] = true;

        return $this;
    }

    public static function getDefaultScopes(): array
    {
        return static::$defaultScopes[static::class] ?? [];
    }

    /**
     * @param array|string|true $scopes
     * @return $this
     * @svc
     */
    protected function unscoped($scopes = []): self
    {
        if (!$scopes) {
            $this->withoutScopes = true;
            return $this;
        }

        if (true === $this->withoutScopes) {
            $this->withoutScopes = [];
        }
        $this->withoutScopes += (array) $scopes;
        return $this;
    }

    protected function applyDefaultScope(): void
    {
        if ($this->applyDefaultScope) {
            return;
        }
        $this->applyDefaultScope = true;

        if (true === $this->withoutScopes) {
            return;
        }

        if (!$defaultScopes = $this->getDefaultScopes()) {
            return;
        }

        $scopes = array_diff(array_keys($defaultScopes), $this->withoutScopes);
        foreach ($scopes as $scope) {
            $this->{$scope}();
        }
    }

    /**
     * @param string $queryPartName
     */
    protected function applyDefaultScopeBeforeAddQueryPart(string $queryPartName): void
    {
        // Ignore `setTable` called `from` on init
        if ('from' !== $queryPartName) {
            $this->applyDefaultScope();
        }
    }
}
