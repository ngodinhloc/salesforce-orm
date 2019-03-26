<?php

namespace Salesforce\Event;

interface EventDispatcherInterface
{
    const ENTITY_AFTER_SAVE_EVENT = 'Entity.afterSave';

    /**
     * @param string $name Name of the event.
     * @param array|null $data Any value you wish to be transported with this event to it can be read by listeners.
     * @param object|null $subject The object that this event applies to ($this by default).
     * @return mixed Event
     */
    public function dispatchEvent(string $name = null, array $data = null, $subject = null);
}
