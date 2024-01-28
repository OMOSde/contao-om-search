<?php

/**
 * Contao bundle contao-om-search
 *
 * @copyright OMOS.de 2024 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 * @link      https://github.com/OMOSde/contao-om-search
 * @license   LGPL 3.0+
 */

namespace OMOSde\ContaoOmSearchBundle\Controller;

use Contao\BackendModule;
use Contao\Environment;
use Contao\Input;
use Contao\PageModel;
use Contao\System;
use Doctrine\DBAL\Connection;

class ModuleKeywords extends BackendModule
{
    protected $strTemplate = 'mod_om_search_keywords';

    private ?Connection $connection;

    private ?object $tokenManager;

    public function __construct()
    {
        parent::__construct();

        // Get services
        $container = System::getContainer();
        $this->connection = $container->get('database_connection');
        $this->tokenManager = $container->get('contao.csrf.token_manager');
    }

    protected function compile(): void
    {
        // Truncate table
        if (Input::post('FORM_SUBMIT') == 'FORM_OM_SEARCH_KEYWORDS' && Input::post('truncateTable')) {
            $this->truncateTable();
        }

        // Export data
        if (Input::post('FORM_SUBMIT') == 'FORM_OM_SEARCH_KEYWORDS' && Input::post('exportData')) {
            $this->exportData();
        }

        // Set template vars
        $this->Template->button = $GLOBALS['TL_LANG']['MSC']['backBT'];
        $this->Template->title = htmlspecialchars($GLOBALS['TL_LANG']['MSC']['backBT']);
        $this->Template->url = Environment::get('base') . Environment::get('request');

        // Generate output
        $this->generateOutput();
    }

    public function generateOutput(): void
    {
        // Set defaults
        $where = '';

        // Check for selected root page
        if (Input::post('FORM_SUBMIT') == 'FORM_OM_SEARCH_KEYWORDS' && Input::post('rootPage') != 0) {
            $where .= ' WHERE rootPage=' . Input::post('rootPage');
            $this->Template->selectedRootPage = (int)Input::post('rootPage');
        }

        // Get statistics
        $this->Template->total = $this->connection->fetchAssociative("SELECT count(*) as total FROM tl_om_search_keywords" . $where)['total'];
        $this->Template->monthly = $this->connection->fetchAssociative("SELECT ROUND(AVG(count)) AS monthly FROM (SELECT COUNT(*) AS count FROM tl_om_search_keywords" . $where . " GROUP BY MONTH(FROM_UNIXTIME(tstamp))) as temp LIMIT 1")['monthly'];
        $this->Template->weekly = $this->connection->fetchAssociative("SELECT ROUND(AVG(count)) AS weekly FROM (SELECT COUNT(*) AS count FROM tl_om_search_keywords" . $where . " GROUP BY WEEK(FROM_UNIXTIME(tstamp))) as temp LIMIT 1")['weekly'];
        $this->Template->daily = $this->connection->fetchAssociative("SELECT ROUND(AVG(count)) AS daily FROM (SELECT COUNT(*) AS count FROM tl_om_search_keywords" . $where . " GROUP BY DAY(FROM_UNIXTIME(tstamp))) as temp LIMIT 1")['daily'];

        // Set template vars
        $this->Template->lastKeywords = $this->connection->fetchAllAssociative("SELECT * FROM tl_om_search_keywords" . $where . " ORDER BY tstamp DESC LIMIT 40");
        $this->Template->topKeywords = $this->connection->fetchAllAssociative("SELECT id,keyword,COUNT(keyword) as count FROM tl_om_search_keywords " . $where . " GROUP BY keyword ORDER BY count DESC,keyword ASC LIMIT 30;");
        $this->Template->rootPages = PageModel::findByPid(0)?->getModels();
        $this->Template->i18n = $GLOBALS['TL_LANG']['om_search'];
        $this->Template->requestToken = $this->tokenManager->getDefaultTokenValue();
    }

    public function truncateTable(): void
    {
        $this->connection->executeQuery("TRUNCATE TABLE tl_om_search_keywords;");
    }

    public function exportData(): void
    {
        // Send header
        header('Content-Type: text/comma-separated-values');
        header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Content-Disposition: attachment; filename="Keywords_' . date('d.m.Y_Hi') . '.utf8.csv"');
        header('Pragma: no-cache');

        // Create column titles
        $titles = [
            $GLOBALS['TL_LANG']['om_search']['time'],
            $GLOBALS['TL_LANG']['om_search']['keyword'],
            $GLOBALS['TL_LANG']['om_search']['results'],
            $GLOBALS['TL_LANG']['om_search']['relevance'],
            $GLOBALS['TL_LANG']['om_search']['queryType'],
            $GLOBALS['TL_LANG']['om_search']['fuzzyType'],
            $GLOBALS['TL_LANG']['om_search']['rootPage']
        ];

        // Send data
        $keywords = $this->connection->fetchAssociative("SELECT tstamp,keyword,results,relevance,queryType,fuzzy,rootPage FROM tl_om_search_keywords ORDER BY tstamp DESC");

        $resource = fopen('php://output', 'w');
        fputcsv($resource, $titles);
        foreach ($keywords as $keyword) {
            fputcsv($resource, $keyword);
        }

        fclose($resource);
        exit;
    }
}
