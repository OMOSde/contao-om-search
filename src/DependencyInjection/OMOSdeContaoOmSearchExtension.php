<?php

/**
 * Contao bundle contao-om-search
 *
 * @copyright OMOS.de 2024 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 * @link      https://github.com/OMOSde/contao-om-search
 * @license   LGPL 3.0+
 */

declare(strict_types=1);

namespace OMOSde\ContaoOmSearchBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class OMOSdeContaoOmSearchExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));

        $loader->load('listener.yml');
        $loader->load('services.yml');
    }
}
