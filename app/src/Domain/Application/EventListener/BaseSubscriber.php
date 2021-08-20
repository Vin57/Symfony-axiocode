<?php
namespace App\Domain\Application\EventListener;

use Axiocode\ApiBundle\Event\PostProcessEvent;
use Axiocode\ApiBundle\Event\PreProcessEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpClient\Exception\InvalidArgumentException;

abstract class BaseSubscriber implements EventSubscriberInterface
{

    protected EventDispatcherInterface $eventDispatcher;
    private string $validationGroup;

    public function __construct(EventDispatcherInterface $eventDispatcher, string $validationGroup = "Default") {
        $this->eventDispatcher = $eventDispatcher;
        $this->validationGroup = $validationGroup;
    }

    /**
     * @return string
     */
    public function getValidationGroup(): string
    {
        return $this->validationGroup;
    }

    /**
     * @param string $validationGroup
     */
    public function setValidationGroup(string $validationGroup): void
    {
        $this->validationGroup = $validationGroup;
    }

    public function onPreProcess(PreProcessEvent $event, $eventName) {
        $this->eventDispatcher->dispatch($event, $this->extractParentEventName($eventName));
    }

    public function onPostProcess(PostProcessEvent $event, $eventName) {
        $event->validationGroup = $this->validationGroup;
        $this->eventDispatcher->dispatch($event, $this->extractParentEventName($eventName));
    }

    /**
     * From a string given as App\Entity\User_CREATE_ONE_POST_PROCESS
     * return the parent event name, which mean CREATE_ONE_POST_PROCESS.
     * @param string $eventName The base event name which
     * @throws InvalidArgumentException
     * @return string|null
     */
    private function extractParentEventName(string $eventName): ?string {
        preg_match_all("/(?<ENTITY>App\\\Entity\\\(?P<NAME>[A-Za-z]*))_(?<EVENT>\w*)/",  $eventName, $matches);
        if (empty($matches['EVENT'][0])) {
            throw new InvalidArgumentException("There is no parent event for $eventName. Did you call dispatch on a parent event ?");
        }
        return $matches['EVENT'][0];
    }
}