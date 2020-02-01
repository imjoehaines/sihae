<?php declare(strict_types=1);

namespace Sihae;

use ArrayAccess;
use Psr\Container\ContainerInterface;
use Pimple\Container as PimpleContainer;

final class Container extends PimpleContainer implements ContainerInterface
{
    public function get($id)
    {
        return $this->offsetGet($id);
    }

    public function has($id)
    {
        return $this->offsetExists($id);
    }
}
