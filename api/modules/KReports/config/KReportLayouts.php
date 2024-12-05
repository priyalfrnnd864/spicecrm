<?php
/*********************************************************************************
 * This file is part of SpiceCRM. SpiceCRM is an enhancement of SugarCRM Community Edition
 * and is developed by aac services k.s.. All rights are (c) 2016 by aac services k.s.
 * You can contact us at info@spicecrm.io
 * 
 * SpiceCRM is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version
 * 
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 * 
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by SugarCRM".
 * 
 * SpiceCRM is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 ********************************************************************************/

$kreportLayouts = [
    '1x1' => [
        'items' => [
            [
                'top' => '0%',
                'left' => '0%',
                'width' => '100%',
                'height' => '100%'
            ]
        ]
    ],
    '1x2' => [
        'items' => [
            [
                'top' => '0%',
                'left' => '0%',
                'width' => '50%',
                'height' => '100%'
            ],
            [
                'top' => '0%',
                'left' => '50%',
                'width' => '50%',
                'height' => '100%'
            ],
        ]
    ],
    '1x3' => [
        'items' => [
            [
                'top' => '0%',
                'left' => '0%',
                'width' => '33%',
                'height' => '100%'
            ],
            [
                'top' => '0%',
                'left' => '33%',
                'width' => '34%',
                'height' => '100%'
            ],
            [
                'top' => '0%',
                'left' => '67%',
                'width' => '33%',
                'height' => '100%'
            ]
        ]
    ],
    '1x4' => [
        'items' => [
            [
                'top' => '0%',
                'left' => '0%',
                'width' => '25%',
                'height' => '100%'
            ],
            [
                'top' => '0%',
                'left' => '25%',
                'width' => '25%',
                'height' => '100%'
            ],
            [
                'top' => '0%',
                'left' => '50%',
                'width' => '25%',
                'height' => '100%'
            ],
            [
                'top' => '0%',
                'left' => '75%',
                'width' => '25%',
                'height' => '100%'
            ]
        ]
    ],
    '2x2' => [
        'items' => [
            [
                'top' => '0%',
                'left' => '0%',
                'width' => '50%',
                'height' => '50%'
            ],
            [
                'top' => '0%',
                'left' => '50%',
                'width' => '50%',
                'height' => '50%'
            ],
            [
                'top' => '50%',
                'left' => '0%',
                'width' => '50%',
                'height' => '50%'
            ],
            [
                'top' => '50%',
                'left' => '50%',
                'width' => '50%',
                'height' => '50%'
            ]
        ]
    ],
    '2x2wide' => [
        'items' => [
            [
                'top' => '0%',
                'left' => '0%',
                'width' => '33%',
                'height' => '50%'
            ],
            [
                'top' => '0%',
                'left' => '33%',
                'width' => '67%',
                'height' => '50%'
            ],
            [
                'top' => '50%',
                'left' => '0%',
                'width' => '33%',
                'height' => '50%'
            ],
            [
                'top' => '50%',
                'left' => '33%',
                'width' => '67%',
                'height' => '50%'
            ]
        ]
    ],
    '1x3x2' => [
        'items' => [
            [
                'top' => '0%',
                'left' => '0%',
                'width' => '67%',
                'height' => '100%'
            ],
            [
                'top' => '0%',
                'left' => '67%',
                'width' => '33%',
                'height' => '50%'
            ],
            [
                'top' => '50%',
                'left' => '67%',
                'width' => '33%',
                'height' => '50%'
            ]
        ]
    ],
    '1x2x1' => [
        'items' => [
            [
                'top' => '0%',
                'left' => '0%',
                'width' => '67%',
                'height' => '100%'
            ],
            [
                'top' => '0%',
                'left' => '67%',
                'width' => '33%',
                'height' => '100%'
            ]
        ]
    ],
    '1+1+2' => [
        'items' => [
            [
                'top' => '0%',
                'left' => '0%',
                'width' => '33%',
                'height' => '100%'
            ],
            [
                'top' => '0%',
                'left' => '33%',
                'width' => '33%',
                'height' => '100%'
            ],
            [
                'top' => '0%',
                'left' => '66%',
                'width' => '34%',
                'height' => '50%'
            ],
            [
                'top' => '50%',
                'left' => '66%',
                'width' => '34%',
                'height' => '50%'
            ]
        ]
    ],
    '1x2x2' => [
        'items' => [
            [
                'top' => '0%',
                'left' => '0%',
                'width' => '50%',
                'height' => '100%'
            ],
            [
                'top' => '0%',
                'left' => '50%',
                'width' => '25%',
                'height' => '100%'
            ],
            [
                'top' => '0%',
                'left' => '75%',
                'width' => '25%',
                'height' => '100%'
            ]
        ]
    ],
    '2x1x4' => [
        'items' => [
            [
                'top' => '0%',
                'left' => '0%',
                'width' => '100%',
                'height' => '50%'
            ],
            [
                'top' => '50%',
                'left' => '0%',
                'width' => '25%',
                'height' => '50%'
            ],
            [
                'top' => '50%',
                'left' => '25%',
                'width' => '25%',
                'height' => '50%'
            ],
            [
                'top' => '50%',
                'left' => '50%',
                'width' => '25%',
                'height' => '50%'
            ],
            [
                'top' => '50%',
                'left' => '75%',
                'width' => '25%',
                'height' => '50%'
            ]
        ]
    ]
];

if(file_exists('custom/modules/KReports/config/KReportLayouts.php'))
    include('custom/modules/KReports/config/KReportLayouts.php');

