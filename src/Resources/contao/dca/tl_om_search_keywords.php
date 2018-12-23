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
 * DCA tl_om_search_keywords
 */
$GLOBALS['TL_DCA']['tl_om_search_keywords'] = [
    // Config
    'config' => [
        'dataContainer' => 'Table',
        'sql'           => [
            'keys' => [
                'id' => 'primary'
            ]
        ]
    ],

    // Fields
    'fields' => [
        'id'        => [
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ],
        'tstamp'    => [
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ],
        'keyword' => [
            'sql' => "varchar(64) NOT NULL default ''"
        ],
        'member'    => [
            'sql' => "int(11) unsigned NOT NULL default '0'"
        ],
        'pages'     => [
            'sql' => "int(11) unsigned NOT NULL default '0'"
        ],
        'fuzzy'     => [
            'sql' => "char(1) NOT NULL default ''"
        ],
        'results'     => [
            'sql' => "int(11) unsigned NOT NULL default '0'"
        ],
        'relevance'     => [
            'sql' => "int(11) unsigned NOT NULL default '0'"
        ],
        'queryType'     => [
            'sql' => "varchar(4) NOT NULL default ''"
        ],
        'rootPage'  => [
            'sql' => "int(11) unsigned NOT NULL default '0'"
        ],
        'referer'   => [
            'sql' => "varchar(255) NOT NULL default ''"
        ],
        'user'      => [
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ]
    ]
];
