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

	public static function findByType($strType, array $arrOptions=array())
	{
		if (empty($strType))
		{
			return null;
		}

		$t = static::$strTable;
		$arrColumns = array("$t.type = ?");

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order']  = "$t.title DESC";
		}

		$arrValues = array($strType);

		return static::findBy($arrColumns, $arrValues, $arrOptions);

	}
}