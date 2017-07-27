<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2016 Heimrich & Hannot GmbH
 *
 * @author  Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

namespace HeimrichHannot\Submissions\Creator;


use HeimrichHannot\Haste\Util\StringUtil;

class InsertTags extends \Controller
{
	
	public function replace($strTag, $blnCache, $strCache, $flags, $tags, $arrCache, $index, $count)
	{
		$arrRelations = $GLOBALS['SUBMISSION_RELATIONS'];
		
		if(!is_array($arrRelations))
		{
			return false;
		}
		
		foreach ($arrRelations as $strKey => $arrRelation)
		{
			if(($strReplace = static::replaceRelation($arrRelation, $strTag, 'insertTagActive')) !== false)
			{
				return $strReplace;
			}

			if(($strReplace = static::replaceRelation($arrRelation, $strTag, 'insertTagLink')) !== false)
			{
				return $strReplace;
			}
		}
		
		return false;
	}
	
	
	public static function replaceRelation(array $arrRelation, $strTag, $strRelationTag)
	{
		$params = preg_split('/::/', $strTag);

		if(!isset($arrRelation[$strRelationTag]) || !isset($arrRelation['table']))
		{
			return false;
		}

		$relParams = str_replace(array('{', '}'), '', $arrRelation[$strRelationTag]);
		$relParams = preg_split('/::/', $relParams);

		// check if given relation inserttag is provided
		if ($relParams[0] != $params[0])
		{
			return false;
		}

		$pageId = null;
		$moduleId = null;
		$entityId = null;

		if(($pageIdx = array_search('PAGE_ID', $relParams)) !== false)
		{
			$pageId = $params[$pageIdx];
		}

		if(($entityIdx = array_search('ENTITY_ID', $relParams)) !== false)
		{
			$entityId = $params[$entityIdx];
		}

		if(($moduleIdx = array_search('MODULE_ID', $relParams)) !== false)
		{
			$moduleId = $params[$moduleIdx];
		}

		if($moduleId === null || ($objModule = \ModuleModel::findByPk($moduleId)) === null)
		{
			return false;
		}

		if($entityId === null || ($objEntity = SubmissionCreator::findRelatedEntity($entityId, $arrRelation, $objModule->current())) === null)
		{
			return false;
		}

		switch ($strRelationTag)
		{
			case 'insertTagLink':
				if(StringUtil::endsWith($params[0], '_link'))
				{
					if($pageId === null || ($objPage = \PageModel::findPublishedByIdOrAlias($pageId)) === null)
					{
						return false;
					}

					return SubmissionCreator::getRelationLink($objPage->current(), $objEntity->current(), $arrRelation);
				}
			break;
			case 'insertTagActive':
				if(StringUtil::endsWith($params[0], '_active'))
				{

					if(($objRelation = SubmissionCreator::findRelatedEntity($entityId, $arrRelation, $objModule->current)) === null)
					{
						return false;
					}

					$time = \Date::floorToMinute();
					$intStart = null;
					$intStop = null;

					// overwrite start from related entity, but only if selected entity period is between
					if($objRelation !== null && $objRelation->limitSubmissionPeriod)
					{
						$intStart = $objRelation->submissionStart;
						$intStop = $objRelation->submissionStop;
					}

					if($objModule->limitSubmissionPeriod)
					{
						if($objModule->submissionStart != '')
						{
							$intStart = ($intStart != '' && $intStart >= $objModule->submissionStart) ? $intStart : $objModule->submissionStart;
						}

						if($objModule->submissionStop != '')
						{
							$intStop = ($intStop != '' && $intStop <= $objModule->submissionStop) ? $intStop : $objModule->submissionStop;
						}
					}

					$blnInPeriod = false;

					if(($intStart == '' || $intStart <= $time) && ($intStop == '' || ($time + 60) <= $intStop))
					{
						$blnInPeriod = true;
					}

					return $blnInPeriod;

				}
			break;
		}

		return false;
	}
}