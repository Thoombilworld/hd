<?php
return [
    'home' => [
        'layout' => 'grid-flex-pro',
        'blocks' => [
            ['type' => 'hero', 'width' => 'full', 'props' => ['style' => 'glass']],
            ['type' => 'trending', 'width' => '2/3'],
            ['type' => 'latest', 'width' => '1/3'],
            ['type' => 'categories', 'width' => 'full'],
            ['type' => 'spotlight', 'width' => 'full']
        ]
    ],
    'article' => [
        'layout' => 'single-pro',
        'blocks' => [
            ['type' => 'article-body', 'width' => '2/3'],
            ['type' => 'sticky-sidebar', 'width' => '1/3']
        ]
    ]
];
