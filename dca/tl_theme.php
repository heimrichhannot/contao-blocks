<?php

array_insert(
    $GLOBALS['TL_DCA']['tl_theme']['list']['operations'],
    5,
    [
        'blocks' => [
            'label' => &$GLOBALS['TL_LANG']['tl_theme']['blocks'],
            'href'  => 'table=tl_block',
            'icon'  => 'system/modules/blocks/assets/icon.png',
        ],
    ]
);

$GLOBALS['TL_DCA']['tl_theme']['config']['ctable'][] = 'tl_block';
