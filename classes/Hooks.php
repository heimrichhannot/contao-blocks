<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2015 Heimrich & Hannot GmbH
 * @package calendar_dav
 * @author Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

namespace HeimrichHannot\Blocks;


class Hooks extends \Controller
{

	public function generateBreadcrumbHook($arrItems, $objModule)
	{
        global $objPage;

        $pages = array($objPage->row());

        if(\Input::get('auto_item') && $objPage->alias != \Input::get('auto_item'))
        {
            array_insert($arrItems, count($arrItems) - 1, array(
                array_merge($pages, array
                (
                    'isRoot' => false,
                    'isActive' => false,
                    'href' => $this->generateFrontendUrl($objPage->row()),
                    'link' => $pages[0]['title'],
                    'data' => $pages[0],
                    'class'    => ''
                ))
            ));

            // set pointer to last element
            end($arrItems);

            // get key for last item
            $idxLastItem = key($arrItems);

            $arrItems[$idxLastItem]['href'] = \Environment::get('request');

			// hide news, event itself …
			if($objModule->hideAutoItem && is_array($arrItems))
			{
				$arrItems = array_slice($arrItems, 0, count($arrItems) - 1);
			}

        }

        return $arrItems;
	}
}