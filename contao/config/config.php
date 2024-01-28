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

use OMOSde\ContaoOmSearchBundle\Controller\ModuleKeywords;

// Backend modules
$GLOBALS['BE_MOD']['system']['keywords'] = [
    'callback' => ModuleKeywords::class,
    'stylesheet' => 'bundles/omosdecontaoomsearch/css/contao-om-search.css',
];

// Models
$GLOBALS['TL_MODELS']['tl_om_search_keywords'] = 'OMOSde\ContaoOmSearchBundle\OmSearchKeywordsModel';
