<?php

if (version_compare(VERSION, '4.8', '>=')) {
    $GLOBALS['TL_LANG']['tl_theme']['blocks'] = 'Edit the blocks of theme ID %s';
} else {
    $GLOBALS['TL_LANG']['tl_theme']['blocks'] = ['Blocks', 'Edit the blocks of theme ID %s'];
}
