<?php

/**
 * Contao bundle contao-om-search
 *
 * @copyright OMOS.de 2017 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 */


/**
 * Namespace
 */

namespace OMOSde\ContaoOmSearchBundle;


/**
 * Use
 */

use Contao\Model;


/**
 * Reads and writes search
 *
 * @copyright OMOS.de 2018 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 */
class OmSearchKeywordsModel extends Model
{

    /**
     * Table name
     *
     * @var string
     */
    protected static $strTable = 'tl_om_search_keywords';
}
