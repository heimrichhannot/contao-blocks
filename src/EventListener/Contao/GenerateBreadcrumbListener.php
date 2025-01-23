<?php
/**
 * @copyright Heimrich & Hannot GmbH, 2025
 * @license LGPL-3.0-or-later
 */
namespace HeimrichHannot\Blocks\EventListener\Contao;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\Environment;
use Contao\Input;
use Contao\Module;
use Contao\PageModel;
use Contao\StringUtil;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

#[AsHook('generateBreadcrumb')]
class GenerateBreadcrumbListener
{
    public function __invoke(array $items, Module $module): array
    {
        if (!\count($items)) {
            return $items;
        }

        if (!$autoItem = Input::get('auto_item', false, true)) {
            return $items;
        }

        /** @var PageModel $objPage */
        global $objPage;

        if ($objPage->alias === $autoItem) {
            return $items;
        }

        $endIdx = \array_key_last($items);

        try {
            $url = $objPage->requireItem
                ? $objPage->getFrontendUrl('/'.Input::get('auto_item', false, true))
                : $objPage->getFrontendUrl();

            $page = $objPage->row();

            array_splice($items, $endIdx, 0, [
                [
                    'isRoot'   => false,
                    'isActive' => false,
                    'href'     => $url,
                    'title'    => StringUtil::specialchars($page['pageTitle'] ?: $page['title'], true),
                    'link'     => $page['title'],
                    'data'     => $page,
                    'class'    => ''
                ]
            ]);
        } catch (RouteNotFoundException) {}

        $items[$endIdx]['href'] = Environment::get('request');

        if ($module->hideAutoItem)
            // hide the auto_item, i.e. the news or event itself
        {
            // remove last element (news, event, etc.)
            unset($items[$endIdx]);
            $endIdx = \array_key_last($items);
            // set new last element active
            $items[$endIdx]['isActive'] = true;
        }

        return $items;
    }
}