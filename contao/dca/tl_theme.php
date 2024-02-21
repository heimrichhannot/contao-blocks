<?php

array_splice(
    $GLOBALS['TL_DCA']['tl_theme']['list']['operations'],
    5, 0,
    [
        'blocks' => [
            'label' => &$GLOBALS['TL_LANG']['tl_theme']['blocks'],
            'href'  => 'table=tl_block',
            'icon'  => 'bundles/heimrichhannotblocks/assets/icon.png',
        ],
    ]
);

$GLOBALS['TL_DCA']['tl_theme']['config']['ctable'][] = 'tl_block';
