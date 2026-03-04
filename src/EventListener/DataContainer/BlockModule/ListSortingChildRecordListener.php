<?php

namespace HeimrichHannot\Blocks\EventListener\DataContainer\BlockModule;

use Contao\ArticleModel;
use Contao\Backend;
use Contao\CoreBundle\ContaoCoreBundle;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\Database;
use Contao\Image;

#[AsCallback(table: 'tl_block_module', target: 'list.sorting.child_record')]
class ListSortingChildRecordListener
{
    /**
     * Add the type and name of module element
     */
    public function __invoke(array $row): string
    {
        $output = $row['id'];

        $attrib = \version_compare(ContaoCoreBundle::getVersion(), '5.0', '<')
            ? 'style="float:left;"'
            : 'class="tl_content_left"';

        switch ($row['type'])
        {
            case 'section':
                $output = '<img alt="" src="system/themes/' . Backend::getTheme()
                    . '/icons/layout.svg" style="vertical-align:text-bottom; margin-right: 4px;"/>';
                $output .= $row['section'] . ' <span style="color:#b3b3b3;padding-left:3px">['
                    . $GLOBALS['TL_LANG']['tl_block_module']['section'][0] . ']</span>';

                return "<div $attrib>$output</div>\n";

            case 'article':
                $article = ArticleModel::findByPk($row['articleAlias']);

                $output = "<div $attrib>";
                $output .= '<img alt="" src="system/themes/' . Backend::getTheme()
                    . '/icons/article.svg" style="vertical-align:text-bottom; margin-right: 4px;"/>';
                $output .= $article->title . ' <span style="color:#b3b3b3;padding-left:3px">['
                    . $GLOBALS['TL_LANG']['tl_block_module']['articleAlias'][0] . ']</span>' . "</div>\n";

                return $output;

            case 'included_content':
                $module = Database::getInstance()
                    ->prepare('SELECT * FROM tl_block_module WHERE tl_block_module.id=?')
                    ->limit(1)
                    ->execute($row['contentBlockModuleAlias']);

                if ($module->numRows)
                {
                    $row = $module->row();
                    $theme = Backend::getTheme();
                    $title = $row['title'];
                    $includedContentElements = $GLOBALS['TL_LANG']['tl_block_module']['includedContentElements'] ?? '';

                    $output = "<img alt=\"\" src=\"system/themes/$theme/icons/published.svg\" style=\"vertical-align:text-bottom;margin-right:4px;\"/>";
                    $output .= "$title <span style=\"color:#b3b3b3;padding-left:3px\">[$includedContentElements]</span>";
                }
                else
                {
                    $output = $GLOBALS['TL_LANG']['tl_block_module']['type_reference'][$row['type']] ?: $row['type'];
                }

                return "<div $attrib>$output</div>\n";

            case 'default':
                $module = Database::getInstance()
                    ->prepare('SELECT name,type FROM tl_module WHERE id = ?')
                    ->execute($row['module']);

                if ($module->numRows) {
                    $output = "<div $attrib>";
                    $output .= '<img alt="" src="system/themes/' . Backend::getTheme()
                        . '/icons/modules.svg" style="vertical-align:text-bottom; margin-right: 4px;"/>';
                    $output .= $module->name . ' <span style="color:#b3b3b3;padding-left:3px">['
                        . ($GLOBALS['TL_LANG']['FMD'][$module->type][0] ?? $module->type)
                        . '] - ID:' . $row['module'] . '</span>' . "</div>\n";
                }

                return $output;

            case 'content':
            default:
                $output = Image::getHtml('children.svg');
                if ($row['title'] ?? false) {
                    $output .= ' ' . $row['title'] . ' <span style="color:#b3b3b3;padding-left:3px">['
                        . ($GLOBALS['TL_LANG']['tl_block_module']['type_reference'][$row['type']] ?? $row['type'])
                        . ']</span>';
                } else {
                    $output .= ' ' . ($GLOBALS['TL_LANG']['tl_block_module']['type_reference'][$row['type']] ?? $row['type']);
                }

                return $output;
        }
    }
}