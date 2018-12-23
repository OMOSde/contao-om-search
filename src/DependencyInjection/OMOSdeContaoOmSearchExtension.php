<?php

/**
 * Contao bundle contao-om-search
 *
 * @copyright OMOS.de 2018 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 * @link      https://github.com/OMOSde/contao-om-search
 * @license   LGPL 3.0+
 */


/**
 * Namespace
 */
namespace OMOSde\ContaoOmSearchBundle\DependencyInjection;


/**
 * Use
 */
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;


/**
 * Adds the bundle services to the container.
 *
 * @copyright OMOS.de 2018 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 */
class OMOSdeContaoOmSearchExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $mergedConfig, ContainerBuilder $objContainer)
    {
        $objLoader = new YamlFileLoader($objContainer, new FileLocator(__DIR__ . '/../Resources/config'));

        $objLoader->load('listener.yml');
        $objLoader->load('services.yml');
    }
}
