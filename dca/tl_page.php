<?php

if (version_compare(VERSION, '4.0', '>=') && \Contao\System::getContainer()->has('huh.utils.cache.database_tree')) {
    \Contao\System::getContainer()->get('huh.utils.cache.database_tree')->registerDcaToCacheTree('tl_page', ['tl_page.type = ?'], ['root'], ['order' => 'sorting']);
}
