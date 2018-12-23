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
 * Backend modules
 */
$GLOBALS['BE_MOD']['system']['keywords'] = [
    'callback'   => 'OMOSde\ContaoOmSearchBundle\ModuleOmSearchKeywords',
    'stylesheet' => 'bundles/omosdecontaoomsearch/css/contao-om-search.css',
];


/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['customizeSearch'][] = [
    'contao_om_search.listener.customize_search',
    'onCustomizeSearch'
];


/**
 * Models
 */
$GLOBALS['TL_MODELS']['tl_om_search_keywords'] = 'OMOSde\ContaoOmSearchBundle\OmSearchKeywordsModel';
