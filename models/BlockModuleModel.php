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
namespace HeimrichHannot\Blocks;

/**
 * Class BlockModuleModel
 */
class BlockModuleModel extends \Model
{

    protected static $strTable = 'tl_block_module';

    public static function findByType($strType, array $arrOptions = [])
    {
        if (empty($strType))
        {
            return null;
        }

        $t          = static::$strTable;
        $arrColumns = ["$t.type = ?"];

        if (!isset($arrOptions['order']))
        {
            $arrOptions['order'] = "$t.title DESC";
        }

        $arrValues = [$strType];

        return static::findBy($arrColumns, $arrValues, $arrOptions);

    }

    public static function generateContent($intBlockModule)
    {
        if (($objBlock = BlockModuleModel::findByPk($intBlockModule)) === null)
        {
            return '';
        }

        $arrContent = [];

        if (($objElement = \ContentModel::findPublishedByPidAndTable($intBlockModule, 'tl_block_module')) !== null)
        {
            while ($objElement->next())
            {
                $arrContent[] = \Controller::getContentElement($objElement->current());
            }
        }

        $strReturn = implode('', $arrContent);

        if ($objBlock->addWrapper)
        {
            $strReturn = ModuleBlock::createBlockWrapper($objBlock, $strReturn);
        }

        return $strReturn;
    }
}