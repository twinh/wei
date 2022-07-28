<?php

namespace Wei\Model;

use Wei\Event;

/**
 * Add event functions to the model
 *
 * @internal Expected to be used only by ModelTrait
 */
trait EventTrait
{
    /**
     * The events of model
     *
     * @var array
     */
    protected static $modelEvents = [];

    /**
     * The event service
     *
     * NOTE: use `eventService` instead of `event` to avoid conflict with model relation
     *
     * @var Event|null
     */
    protected $eventService;

    /**
     * Add a handler to an event for current class
     *
     * @param string $name
     * @param string|callable $method
     */
    public static function onModelEvent(string $name, $method)
    {
        static::$modelEvents[static::class][$name][] = $method;
    }

    /**
     * Execute all handlers added to the class by the specified event name
     *
     * @param string $name
     * @param mixed $data
     * @return mixed
     */
    protected function triggerModelEvent(string $name, $data = [])
    {
        $class = static::class;
        $event = $this->getEventService();

        // Use a unique event name for every model class
        $modelEvent = lcfirst($this->getModelUniqueName()) . ucfirst($name);

        if (isset(static::$modelEvents[$class][$name])) {
            foreach (static::$modelEvents[$class][$name] as $callback) {
                // Prefer self class method
                if (is_string($callback) && method_exists($this, $callback)) {
                    $callback = function ($model, ...$args) use ($callback) {
                        return $model->{$callback}(...$args);
                    };
                }
                $event->on($modelEvent, $callback);
            }
            unset(static::$modelEvents[$class][$name]);
        }

        $result = $event->trigger($modelEvent, array_merge([$this], (array) $data));
        if ($result) {
            $result = end($result);
        }

        return $result;
    }

    /**
     * Trigger model event with self method
     *
     * @param string $name
     * @return false|mixed
     */
    protected function triggerModelEventWithMethod(string $name)
    {
        if (false === $this->{$name}()) {
            return false;
        }
        return $this->triggerModelEvent($name);
    }

    /**
     * Return the event service
     *
     * @return Event
     */
    protected function getEventService(): Event
    {
        if (!isset($this->eventService)) {
            // @phpstan-ignore-next-line $eventService (Wei\Event|null) does not accept Wei\Base.
            $this->eventService = $this->wei->get('event', [], $this->providers);
        }
        // @phpstan-ignore-next-line should return Wei\Event but returns Wei\Base.
        return $this->eventService;
    }

    /**
     * The method called after find a record
     */
    protected function afterFind()
    {
    }

    /**
     * The method called before save a record
     */
    protected function beforeSave()
    {
    }

    /**
     * The method called after save a record
     */
    protected function afterSave()
    {
    }

    /**
     * The method called before insert a record
     */
    protected function beforeCreate()
    {
    }

    /**
     * The method called after insert a record
     */
    protected function afterCreate()
    {
    }

    /**
     * The method called before update a record
     */
    protected function beforeUpdate()
    {
    }

    /**
     * The method called after update a record
     */
    protected function afterUpdate()
    {
    }

    /**
     * The method called before delete a record
     */
    protected function beforeDestroy()
    {
    }

    /**
     * The method called after delete a record
     */
    protected function afterDestroy()
    {
    }
}
