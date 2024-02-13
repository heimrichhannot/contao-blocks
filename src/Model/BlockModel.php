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

use Contao\Model;

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
     * @param array   $options
     *
     * @return \Contao\Model\Collection|\Contao\Model[]|\Contao\Model|null A collection of models or null if there are no news
     */
    public static function findPublishedByPk($pk, $options = [])
    {
        $t         = static::$strTable;
        $columns[] = "$t.id=" . $pk;

        if (isset($arrOptions['ignoreFePreview']) || !BE_USER_LOGGED_IN) {
            $time      = \Date::floorToMinute();
            $columns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.published='1'";
        }

        return static::findBy($columns, null, $options);
    }
}

class_alias(BlockModel::class, 'HeimrichHannot\Blocks\BlockModel');
