<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (C) 2005-2013 Leo Feyer
 *
 * @package   blocks
 * @author    r.kaltofen@heimrich-hannot.de
 * @license   GNU/LGPL
 * @copyright Heimrich & Hannot GmbH
 */


/**
 * Namespace
 */

namespace HeimrichHannot\Blocks\Model;

use Contao\Date;
use Contao\Model;
use Contao\Model\Collection;
use Contao\System;

/**
 * Class BlockModel
 */
class BlockModel extends Model
{
    protected static $strTable = 'tl_block';

    /**
     * Find published block by primary key
     *
     * @param integer $pk
     * @param array $options
     *
     * @return Collection|Model[]|Model|null A collection of models or null if there are no news
     */
    public static function findPublishedByPk(int|string $pk, array $options = []): Collection|Model|array|null
    {
        $t         = static::$strTable;
        $columns[] = "$t.id=" . $pk;

        // Contao 4.13 gated this on BE_USER_LOGGED_IN, which the framework defines as
        // TokenChecker::isPreviewMode() - true only in the front-end preview. hasBackendUser() is
        // true whenever a back-end session exists at all, so on the normal front end every editor
        // with an open back-end tab was served unpublished blocks.
        $isPreviewMode = System::getContainer()->get('contao.security.token_checker')->isPreviewMode();

        if (isset($options['ignoreFePreview']) || !$isPreviewMode) {
            $time      = Date::floorToMinute();
            $columns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.published='1'";
        }

        return static::findBy($columns, null, $options);
    }
}