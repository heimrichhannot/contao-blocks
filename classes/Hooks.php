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

use Contao\Controller;
use Contao\Environment;
use Contao\Input;
use Contao\PageModel;
use Contao\StringUtil;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class Hooks extends Controller
{
    public function generateBreadcrumbHook($arrItems, $objModule)
    {
        /** @var PageModel $objPage */
        global $objPage;

        $pages = [$objPage->row()];

        if ($objPage->is)

        if (Input::get('auto_item', false, true) && $objPage->alias != Input::get('auto_item', false, true)) {
            $addItem = true;
            try {
                if ($objPage->requireItem) {
                    $url = $objPage->getFrontendUrl('/'.Input::get('auto_item', false, true));
                } else {
                    $url = $objPage->getFrontendUrl();
                }
            } catch (\Exception $exception) {
                if (class_exists(RouteNotFoundException::class) && $exception instanceof RouteNotFoundException) {
                    $addItem = false;
                } else {
                    throw $exception;
                }
            }

            if ($addItem) {
                array_insert($arrItems, count($arrItems) - 1, [
                        [
                            'isRoot'   => false,
                            'isActive' => false,
                            'href'     => $url,
                            'title'    => StringUtil::specialchars($pages[0]['pageTitle'] ?: $pages[0]['title'], true),
                            'link'     => $pages[0]['title'],
                            'data'     => $pages[0],
                            'class'    => ''
                        ]
                    ]
                );
            }


            // set pointer to last element
            end($arrItems);

            // get key for last item
            $idxLastItem = key($arrItems);

            $arrItems[$idxLastItem]['href'] = Environment::get('request');

            // hide news, event itself …
            if ($objModule->hideAutoItem && is_array($arrItems)) {
                // remove last element (news, event, ..)
                $arrItems = array_slice($arrItems, 0, count($arrItems) - 1);
                // set new last element active
                $arrItems[count($arrItems) - 1]['isActive'] = true;
            }
        }

        return $arrItems;
    }
}

class_alias(Hooks::class, 'HeimrichHannot\Blocks\Hooks');
