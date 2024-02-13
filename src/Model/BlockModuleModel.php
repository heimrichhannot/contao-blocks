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

use Contao\ContentModel;
use Contao\Controller;
use Contao\Model;

/**
 * Class BlockModuleModel
 *
 * @property int $id
 * @property int $pid
 * @property int $sorting
 * @property int $tstamp
 * @property string $type
 * @property string $title
 * @property int $module
 * @property int $articleAlias
 * @property string $imgSRC
 * @property string $addVisibility
 * @property string $pages
 * @property string $keywords
 * @property string $keywordPages
 */
class BlockModuleModel extends Model
{

    protected static $strTable = 'tl_block_module';

    public static function findByType($strType, array $arrOptions = [])
    {
        if (empty($strType)) {
            return null;
        }

        $t          = static::$strTable;
        $arrColumns = ["$t.type = ?"];

        if (!isset($arrOptions['order'])) {
            $arrOptions['order'] = "$t.title DESC";
        }

        $arrValues = [$strType];

        return static::findBy($arrColumns, $arrValues, $arrOptions);

    }

    public static function generateContent($intBlockModule)
    {
        if (($objBlock = BlockModuleModel::findByPk($intBlockModule)) === null) {
            return '';
        }

        $block = new BlockChild($objBlock);
        return $block->generate();
    }

    /**
     * Find published block elements by primary key
     *
     * @param integer $pid
     * @param array   $options
     *
     * @return \Contao\Model\Collection|\Contao\Model[]|\Contao\Model|null A collection of models or null if there are no news
     */
    public static function findPublishedByPid($pid, $options = [])
    {
        $t         = static::$strTable;
        $columns[] = "$t.pid=" . $pid;

        if (isset($arrOptions['ignoreFePreview']) || !BE_USER_LOGGED_IN) {
            $time      = \Date::floorToMinute();
            $columns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.published='1'";
        }

        return static::findBy($columns, null, $options);
    }
}

class_alias(BlockModuleModel::class, 'HeimrichHannot\Blocks\BlockModuleModel');
