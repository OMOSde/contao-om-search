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
namespace OMOSde\ContaoOmSearchBundle;


/**
 * Use
 */
use Contao\BackendModule;


/**
 * Class ModuleOmSearch
 */
class ModuleOmSearchKeywords extends BackendModule
{

    /**
     * Template
     *
     * @var string
     */
    protected $strTemplate = 'mod_om_search_keywords';


    /**
     * Generate module
     */
    protected function compile()
    {
        // truncate table
        if (\Input::post('FORM_SUBMIT') == 'FORM_OM_SEARCH_KEYWORDS' && \Input::post('truncateTable'))
        {
            $this->truncateTable();
        }

        // export data
        if (\Input::post('FORM_SUBMIT') == 'FORM_OM_SEARCH_KEYWORDS' && \Input::post('exportData'))
        {
            $this->exportData();
        }

        // set template vars
        $this->Template->button = $GLOBALS['TL_LANG']['MSC']['backBT'];
        $this->Template->title = specialchars($GLOBALS['TL_LANG']['MSC']['backBT']);
        $this->Template->url = \Environment::get('base') . \Environment::get('request');

        // generate output
        $this->generateOutput();
    }


    /**
     * generate Output
     */
    public function generateOutput()
    {
        // defaults
        $strWhere = '';
        $objDatabase = \Database::getInstance();

        // check for selected root page
        if (\Input::post('FORM_SUBMIT') == 'FORM_OM_SEARCH_KEYWORDS' && \Input::post('rootPage') != 0)
        {
            $strWhere .= ' WHERE rootPage=' . \Input::post('rootPage');
            $this->Template->selectedRootPage = \Input::post('rootPage');
        }

        // get all backend user
        $objUser = $objDatabase->prepare("SELECT id,name,username FROM tl_user WHERE disable<>1")->execute();
        while ($objUser->next())
        {
            $arrUser[$objUser->id] = $objUser->row();
        }

        // get all root pages
        $arrRootPages = $objDatabase->prepare("SELECT id,title,language FROM tl_page WHERE pid=0")->execute()->fetchAllAssoc();

        // get statistics
        $this->Template->total = $objDatabase->prepare("SELECT count(*) as total FROM tl_om_search_keywords" . $strWhere)->execute()->fetchAssoc()['total'];
        $this->Template->monthly = $objDatabase->prepare("SELECT ROUND(AVG(count)) AS monthly FROM (SELECT COUNT(*) AS count FROM tl_om_search_keywords" . $strWhere . " GROUP BY MONTH(FROM_UNIXTIME(tstamp))) as temp")->limit(1)->execute()->fetchAssoc()['monthly'];
        $this->Template->weekly = $objDatabase->prepare("SELECT ROUND(AVG(count)) AS weekly FROM (SELECT COUNT(*) AS count FROM tl_om_search_keywords" . $strWhere . " GROUP BY WEEK(FROM_UNIXTIME(tstamp))) as temp")->limit(1)->execute()->fetchAssoc()['weekly'];
        $this->Template->daily = $objDatabase->prepare("SELECT ROUND(AVG(count)) AS daily FROM (SELECT COUNT(*) AS count FROM tl_om_search_keywords" . $strWhere . " GROUP BY DAY(FROM_UNIXTIME(tstamp))) as temp")->limit(1)->execute()->fetchAssoc()['daily'];

        // set template vars
        $this->Template->lastKeywords = $objDatabase->prepare("SELECT * FROM tl_om_search_keywords" . $strWhere . " ORDER BY tstamp DESC LIMIT 30")->execute()->fetchAllAssoc();
        $this->Template->topKeywords = $objDatabase->prepare("SELECT DISTINCT id,keyword,COUNT(*) as count FROM tl_om_search_keywords" . $strWhere . " GROUP BY id,keyword ORDER BY count DESC")->execute()->fetchAllAssoc();
        $this->Template->rootPages = $arrRootPages;
        $this->Template->lang = $GLOBALS['TL_LANG']['om_search'];
        $this->Template->requestToken = \RequestToken::get();
    }


    /**
     * Truncate table tl_om_search_keywords
     */
    public function truncateTable()
    {
        $this->Database->prepare("TRUNCATE TABLE tl_om_search_keywords;")->execute();
    }


    /**
     * export to csv
     */
    public function exportData()
    {
        // variables
        $strLogVersion = '';
        $strUserAgent = \Environment::get('agent')->string;

        // check for IE or other
        $strIE = (preg_match('@MSIE ([0-9].[0-9]{1,2})@', $strUserAgent, $strLogVersion)) ? 'IE' : 'NOIE';

        // send header
        header('Content-Type: text/comma-separated-values');
        header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Content-Disposition: attachment; filename="Keywords_' . date('d.m.Y_Hi') . '.utf8.csv"');

        if ($strIE == 'IE')
        {
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
        }
        else
        {
            header('Pragma: no-cache');
        }

        // create column titles
        $arrTitles = [
            $GLOBALS['TL_LANG']['om_search']['time'],
            $GLOBALS['TL_LANG']['om_search']['keyword'],
            $GLOBALS['TL_LANG']['om_search']['results'],
            $GLOBALS['TL_LANG']['om_search']['relevance'],
            $GLOBALS['TL_LANG']['om_search']['queryType'],
            $GLOBALS['TL_LANG']['om_search']['fuzzyType']
        ];

        // send data
        $objDatabase = \Database::getInstance();
        $arrKeywords = $objDatabase->prepare("SELECT tstamp,keyword,results,relevance,queryType,fuzzy FROM tl_om_search_keywords ORDER BY tstamp DESC")->execute()->fetchAllAssoc();

        $resOutput = fopen('php://output', 'w');
        fputcsv($resOutput, $arrTitles, ',');
        foreach ($arrKeywords as $strKeyword)
        {
            fputcsv($resOutput, $strKeyword, ',');
        }

        fclose($resOutput);
        exit;
    }
}
