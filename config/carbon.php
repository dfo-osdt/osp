<?php

// For information on this array please refer to the Cmixin/BusinessDay package documentation
// https://github.com/kylekatarnls/business-day/tree/master
// Federal holidays: https://www.canada.ca/en/services/jobs/workplace/federal-labour-standards/vacations-holidays.html#h2.2
return [
    'holidays' => [
        'region' => 'ca-national',
        'with' => [
            'New Year Day' => '= 01-01 if weekend then next monday',
            'National Day for Truth and Reconciliation' => '= 09-30 if weekend then next monday',
            'Christmas Day' => '= 12-25 if weekend then next monday',
            'Boxing Day' => '= 12-26 if weekend then next monday',
            'Canada Day' => '= 07-01 if weekend then next monday',
            'Easter Monday' => '= easter 1',
            'St Jean Baptiste Day' => '= 06-24 if weekend then next monday',
        ],
    ],
];
