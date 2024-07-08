<?php

namespace DesLynx\AlertBundle;

use DesLynx\AlertBundle\DependencyInjection\DesLynxAlertExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DesLynxAlertBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        if (null === $this->extension) {
            $this->extension = new DesLynxAlertExtension();
        }

        return $this->extension ?: null;
    }
}
