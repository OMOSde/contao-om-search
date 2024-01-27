<?php

/**
 * Contao bundle contao-om-search
 *
 * @copyright OMOS.de 2024 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 * @link      http://www.omos.de
 * @license   LGPL 3.0+
 */

declare(strict_types=1);

namespace OMOSde\ContaoOmSearchBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class OMOSdeContaoOmSearchBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
