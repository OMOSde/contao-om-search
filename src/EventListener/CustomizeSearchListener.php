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
namespace OMOSde\ContaoOmSearchBundle\EventListener;


/**
 * Use
 */
use Contao\BackendUser;
use Contao\FrontendUser;
use Contao\Search;
use OMOSde\ContaoOmSearchBundle\OmSearchKeywordsModel;



/**
 * @copyright OMOS.de 2018 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 */
class CustomizeSearchListener
{
    /**
     * @param $arrPages
     * @param $strKeywords
     * @param $strQueryType
     * @param $blnFuzzy
     *
     * @throws \Exception
     */
    public function onCustomizeSearch($arrPages, $strKeywords, $strQueryType, $blnFuzzy)
    {
        // user and member
        $objBackendUser = BackendUser::getInstance();
        $objFrontendUser = FrontendUser::getInstance();

        // get current page for root page id
        global $objPage;

        // get search results
        $arrResult = Search::searchFor($strKeywords, ($strQueryType == 'or'), $arrPages, 0, 0, $blnFuzzy)->fetchAllAssoc();

        // create dataset
        $objKeyword = new OmSearchKeywordsModel();
        $arrData = [
            'tstamp'    => time(),
            'keyword'   => $strKeywords,
            'rootPage'  => $objPage->rootId,
            'pages'     => count($arrPages),
            'fuzzy'     => $blnFuzzy,
            'results'   => count($arrResult),
            'relevance' => (!empty($arrResult)) ? $arrResult[0]['relevance'] : 0,
            'queryType' => $strQueryType,
            'user'      => ($objBackendUser->id) ?: 0,
            'member'    => ($objFrontendUser->id) ?: 0
        ];

        // save new keyword
        $objKeyword->setRow($arrData);
        $objKeyword->save();
    }
}
