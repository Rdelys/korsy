<?php

return [

    // Une seule devise pour l'instant.
    'currency' => [
        'code'   => 'MGA',
        'label'  => 'Ariary',
        'symbol' => 'Ar',
    ],

    // Opérateurs gérés. Le logo est relatif à public/.
    'operators' => [
        'mvola' => [
            'name'     => 'MVola (Telma)',
            'prefixes' => ['34', '36', '38'],
            'hint'     => '+261 34 / 36 / 38',
            'logo'     => 'mvola.png',
            'desc_c'   => 'Achetez instantanément vos cours et fascicules sans carte bancaire.',
            'desc_v'   => 'Recevez vos gains directement sur votre compte MVola.',
        ],
        'orange' => [
            'name'     => 'Orange Money',
            'prefixes' => ['32', '37'],
            'hint'     => '+261 32 / 37',
            'logo'     => 'orange.png',
            'desc_c'   => 'Activez le débit 1-clic pour vos achats de fascicules.',
            'desc_v'   => 'Recevez vos reversements sur votre compte Orange Money.',
        ],
    ],
];