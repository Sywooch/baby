<?php
return [
    'product' => [
        'preview' => [
            'action' => 'copy',
            'width' => 290,
            'height' => 290,
        ],
        'frontPreview' => [
            'action' => 'adaptiveThumbnail',
            'width' => 600,
            'height' => 600,
        ],
        'mainPagePreview' => [
            'action' => 'adaptiveThumbnail',
            'width' => 209,
            'height' => 179,
        ],
        'smallPreview' => [
            'action' => 'adaptiveThumbnail',
            'width' => 84,
            'height' => 84,
        ],
        'mainPreview' => [
            'action' => 'thumbnail',
            'width' => 420,
            'height' => 365,
        ],
        'big' => [
            'action' => 'adaptiveThumbnail',
            'width' => 500,
            'height' => 500,
        ],
    ],
    'banner' => [
        'adminPreview' => [
            'action' => 'adaptiveThumbnail',
            'width' => 420,
            'height' => 100,
        ],
        'front' => [
            'action' => 'adaptiveThumbnail',
            'width' => 892,
            'height' => 409,
        ],
    ],
    'sales' => [
        'adminPreview' => [
            'action' => 'thumbnail',
            'width' => 255,
            'height' => 290,
        ],
        'big' => [
            'action' => 'thumbnail',
            'width' => 960,
            'height' => 630,
        ]
    ],
    'profile' => [
        'avatar' => [
            'action' => 'adaptiveThumbnail',
            'width' => 120,
            'height' => 120,
        ]
    ],
    'seo' => [
        'default' => [
            'action' => 'thumbnail',
            'width' => 1200,
            'height' => 630,
        ]
    ]
];
