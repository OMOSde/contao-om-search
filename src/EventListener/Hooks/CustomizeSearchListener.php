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

namespace OMOSde\ContaoOmSearchBundle\EventListener\Hooks;

use Contao\BackendUser;
use Contao\FrontendUser;
use Contao\Module;
use Contao\Search;
use Exception;
use OMOSde\ContaoOmSearchBundle\OmSearchKeywordsModel;
use Symfony\Component\Security\Core\Security;

class CustomizeSearchListener
{
    public function __construct(private readonly Security $security) {}

    /**
     * @throws Exception
     */
    public function __invoke(array $pageIds, string $keywords, string $queryType, bool $fuzzy, Module $module): void
    {
        // Get current page for root page id
        global $objPage;

        // Get search results
        $searchResults = Search::query($keywords, ($queryType == 'or'), $pageIds, $fuzzy)->getResults();

        // Create dataset
        $keyword = new OmSearchKeywordsModel();
        $row = [
            'tstamp' => time(),
            'keyword' => $keywords,
            'rootPage' => $objPage->rootId,
            'pages' => count($pageIds),
            'fuzzy' => $fuzzy,
            'results' => count($searchResults),
            'relevance' => (!empty($searchResults)) ? $searchResults[0]['relevance'] : 0,
            'queryType' => $queryType,
            'user' => (($user = $this->security->getUser()) instanceof BackendUser) ? $user->id : 0,
            'member' => (($user = $this->security->getUser()) instanceof FrontendUser) ? $user->id : 0
        ];

        // Save new keyword
        $keyword->setRow($row);
        $keyword->save();
    }
}
