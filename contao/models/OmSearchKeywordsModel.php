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

namespace OMOSde\ContaoOmSearchBundle;

use Contao\Model;

class OmSearchKeywordsModel extends Model
{
    protected static $strTable = 'tl_om_search_keywords';
}
