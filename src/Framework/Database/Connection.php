<?php

namespace Framework\Database;

use Psr\Container\ContainerInterface;

class Connection implements ConnectionInterface
{

    /**
     * @var ContainerInterface
     */
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }



}