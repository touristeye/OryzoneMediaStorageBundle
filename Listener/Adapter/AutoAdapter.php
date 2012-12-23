<?php

namespace Oryzone\Bundle\MediaStorageBundle\Listener\Adapter;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Doctrine\Common\EventArgs;

use Oryzone\Bundle\MediaStorageBundle\Exception\InvalidArgumentException;

class AutoAdapter implements AdapterInterface
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    protected $container;

    /**
     * @var array $adaptersMap
     */
    protected $adaptersMap;

    /**
     * Constructor
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param array $adaptersMap
     */
    public function __construct(ContainerInterface $container, $adaptersMap)
    {
        $this->container = $container;
        $this->adaptersMap = $adaptersMap;
    }

    /**
     * Get the correct adapter for the current event
     *
     * @param \Doctrine\Common\EventArgs $e
     * @throws \Oryzone\Bundle\MediaStorageBundle\Exception\InvalidArgumentException
     *
     * @return \Oryzone\Bundle\MediaStorageBundle\Listener\Adapter\AdapterInterface
     */
    protected function getAdapter(EventArgs $e)
    {
        $eventClass = get_class($e);
        if(!isset($this->adaptersMap[$eventClass]))
            throw new InvalidArgumentException(sprintf('Can\'t find appropriate adapter for event of class "%s". Probably you haven\'t added the adapters mapping record on the AutoAdapter configuration', $eventClass));

        return $this->container->get($this->adaptersMap[$eventClass]);
    }

    /**
     * {@inheritDoc}
     */
    public function getObjectFromArgs(EventArgs $e)
    {
        return $this->getAdapter($e)->getObjectFromArgs($e);
    }

    /**
     * {@inheritDoc}
     */
    public function getManagerFromArgs(EventArgs $e)
    {
        return $this->getAdapter($e)->getManagerFromArgs($e);
    }

    /**
     * {@inheritDoc}
     */
    public function recomputeChangeSet(EventArgs $e)
    {
        return $this->getAdapter($e)->recomputeChangeSet($e);
    }
}