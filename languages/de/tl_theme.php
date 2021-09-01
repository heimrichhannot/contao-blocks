<?php

if (version_compare(VERSION, '4.8', '>=')) {
    $GLOBALS['TL_LANG']['tl_theme']['blocks'] = 'Die Blöcke des Theme ID %s bearbeiten';
} else {
    $GLOBALS['TL_LANG']['tl_theme']['blocks'] = ['Blöcke', 'Die Blöcke des Theme ID %s bearbeiten'];
}
