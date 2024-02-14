<?php

namespace HeimrichHannot\Blocks\Backend;

use Contao\ArticleModel;
use Contao\Backend;
use Contao\BackendUser;
use Contao\CoreBundle\Exception\AccessDeniedException;
use Contao\Database;
use Contao\DataContainer;
use Contao\Input;
use Contao\StringUtil;
use Contao\System;
use Contao\Versions;
use HeimrichHannot\Blocks\BlockModel;

class BlockModuleBackend extends Backend
{
    /**
     * Import the back end user object
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
        $this->loadLanguageFile('tl_content');
    }

    public function

    getContentBlockModulesAsOptions(DataContainer $dc)
    {
        $options = [];

        $blockModules = $this->Database->prepare(
            "SELECT m.id, m.title, t.name AS 'theme' FROM tl_block_module m INNER JOIN tl_block b ON m.pid = b.id INNER JOIN tl_theme t ON t.id = b.pid WHERE m.type=? ORDER BY t.name, m.title"
        )->execute(
            'content'
        );

        if ($blockModules->numRows > 0) {
            while ($blockModules->next()) {
                $options[$blockModules->theme][$blockModules->id] = $blockModules->title . ' (ID ' . $blockModules->id . ')';
            }
        }

        return $options;
    }

    public function setFeatureCookieName($varValue, DataContainer $dc)
    {
        if ($varValue == '') {
            $varValue = 'block_feature_' . $dc->id;
        }

        return $varValue;
    }

    public function setFeatureCookieExpire($varValue, DataContainer $dc)
    {
        if ($varValue == '') {
            $varValue = (43200 * 60); // 30 Tage
        }

        return $varValue;
    }

    public function editModule(DataContainer $dc)
    {
        return ($dc->value < 1)
            ? ''
            : ' <a href="contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $dc->value . '&amp;rt=' . REQUEST_TOKEN . '" title="'
            . sprintf(specialchars($GLOBALS['TL_LANG']['tl_content']['editalias'][1]), $dc->value) . '" style="padding-left:3px">'
            . $this->generateImage('alias.gif', $GLOBALS['TL_LANG']['tl_content']['editalias'][0], 'style="vertical-align:top"') . '</a>';
    }

    public function invokeI18nl10n(DataContainer $dc)
    {
        if (in_array('i18nl10n', $this->Config->getActiveModules())) {
            $this->loadLanguageFile('languages');
            $GLOBALS['TL_DCA']['tl_block_module']['palettes']['default'] =
                str_replace('keywords', 'keywords, language', $GLOBALS['TL_DCA']['tl_block_module']['palettes']['default']);
        }
    }

    public function getI18nl10nLanguages()
    {
        $arrLanguages = [];
        if (in_array('i18nl10n', $this->Config->getActiveModules())) {
            $arrLanguages = version_compare(VERSION, '4.0', '<') ? deserialize($GLOBALS['TL_CONFIG']['i18nl10n_languages'],
                true) : StringUtil::deserialize($GLOBALS['TL_CONFIG']['i18nl10n_languages'], true);;
            array_unshift($arrLanguages, '');
        }

        return $arrLanguages;
    }

    /**
     * Get all modules and return them as array
     *
     * @return array
     */
    public function getModules()
    {
        $arrModules = [];
        $objModules =
            $this->Database->execute("SELECT m.id, m.name, t.name AS theme FROM tl_module m LEFT JOIN tl_theme t ON m.pid=t.id WHERE type != 'block' ORDER BY t.name, m.name");

        while ($objModules->next()) {
            $arrModules[$objModules->theme][$objModules->id] = $objModules->name . ' (ID ' . $objModules->id . ')';
        }

        return $arrModules;
    }

    /**
     * Get all articles and return them as array (article alias)
     *
     * @return array
     */
    public function getArticleAlias(DataContainer $dc)
    {
        $arrPids  = [];
        $arrAlias = [];

        if (!$this->User->isAdmin) {
            foreach ($this->User->pagemounts as $id) {
                $arrPids[] = $id;
                $arrPids   = array_merge($arrPids, $this->Database->getChildRecords($id, 'tl_page'));
            }

            if (empty($arrPids)) {
                return $arrAlias;
            }

            $objAlias =
                $this->Database->prepare("SELECT a.id, a.pid, a.title, a.inColumn, p.title AS parent FROM tl_article a LEFT JOIN tl_page p ON p.id=a.pid WHERE a.pid IN("
                    . implode(',', array_map('intval', array_unique($arrPids))) . ") ORDER BY parent, a.sorting")
                    ->execute($dc->id);
        } else {
            $objAlias =
                $this->Database->prepare("SELECT a.id, a.pid, a.title, a.inColumn, p.title AS parent FROM tl_article a LEFT JOIN tl_page p ON p.id=a.pid ORDER BY parent, a.sorting")
                    ->execute($dc->id);
        }

        if ($objAlias->numRows) {
            System::loadLanguageFile('tl_article');

            while ($objAlias->next()) {
                $key                           = $objAlias->parent . ' (ID ' . $objAlias->pid . ')';
                $arrAlias[$key][$objAlias->id] =
                    $objAlias->title . ' (' . ($GLOBALS['TL_LANG']['tl_article'][$objAlias->inColumn] ?? $objAlias->inColumn) . ', ID '
                    . $objAlias->id . ')';
            }
        }

        return $arrAlias;
    }

    /**
     * Add the type and name of module element
     *
     * @param array
     *
     * @return string
     */
    public function addModuleInfo($arrRow)
    {
        $output = $arrRow['id'];

        if ($arrRow['type'] == 'section') {
            $output = '<div style="float:left">';
            $output .= '<img alt="" src="system/themes/' . $this->getTheme()
                . '/images/layout.gif" style="vertical-align:text-bottom; margin-right: 4px;"/>';
            $output .= $arrRow['section'] . ' <span style="color:#b3b3b3;padding-left:3px">[' . $GLOBALS['TL_LANG']['tl_block_module']['section'][0]
                . ']</span>' . "</div>\n";

            return $output;
        } elseif ($arrRow['type'] == 'article') {
            $objArticle = ArticleModel::findByPk($arrRow['articleAlias']);

            $output = '<div style="float:left">';
            $output .= '<img alt="" src="system/themes/' . $this->getTheme()
                . '/images/article.gif" style="vertical-align:text-bottom; margin-right: 4px;"/>';
            $output .= $objArticle->title . ' <span style="color:#b3b3b3;padding-left:3px">['
                . $GLOBALS['TL_LANG']['tl_block_module']['articleAlias'][0] . ']</span>' . "</div>\n";

            return $output;
        } elseif ($arrRow['type'] == 'content') {
            $output = '<div style="float:left">';
            $output .= '<img alt="" src="system/themes/' . $this->getTheme()
                . '/images/published.gif" style="vertical-align:text-bottom; margin-right: 4px;"/>';
            $output .= $arrRow['title'] . ' <span style="color:#b3b3b3;padding-left:3px">['
                . $GLOBALS['TL_LANG']['tl_block_module']['contentElements'] . ']</span>' . "</div>\n";

            return $output;
        } elseif ($arrRow['type'] == 'included_content') {
            $module = Database::getInstance()->prepare('SELECT * FROM tl_block_module WHERE tl_block_module.id=?')->limit(1)->execute(
                $arrRow['contentBlockModuleAlias']
            );

            if ($module->numRows > 0)
            {
                $arrRow = $module->row();

                $output = '<div style="float:left">';
                $output .= '<img alt="" src="system/themes/' . $this->getTheme()
                    . '/images/published.gif" style="vertical-align:text-bottom; margin-right: 4px;"/>';
                $output .= $arrRow['title'] . ' <span style="color:#b3b3b3;padding-left:3px">['
                    . $GLOBALS['TL_LANG']['tl_block_module']['includedContentElements'] . ']</span>' . "</div>\n";

                return $output;
            } else {
                $output = '<div style="float:left">';
                $output .= $GLOBALS['TL_LANG']['tl_block_module']['type_reference'][$arrRow['type']] ?: $arrRow['type'];
                $output .= '</div>';
            }
        } else {
            if ($arrRow['type'] == 'default') {
                $objModule = $this->Database->prepare('SELECT name,type FROM tl_module WHERE id = ?')->execute($arrRow['module']);

                if ($objModule->numRows) {
                    $output = '<div style="float:left">';
                    $output .= '<img alt="" src="system/themes/' . $this->getTheme()
                        . '/images/modules.gif" style="vertical-align:text-bottom; margin-right: 4px;"/>';
                    $output .= $objModule->name . ' <span style="color:#b3b3b3;padding-left:3px">['
                        . (isset($GLOBALS['TL_LANG']['FMD'][$objModule->type][0]) ? $GLOBALS['TL_LANG']['FMD'][$objModule->type][0] : $objModule->type)
                        . '] - ID:' . $arrRow['module'] . '</span>' . "</div>\n";
                }
            } else {
                $output = '<div style="float:left">';
                $output .= $GLOBALS['TL_LANG']['tl_block_module']['type_reference'][$arrRow['type']] ?: $arrRow['type'];
                $output .= '</div>';
            }
        }


        return $output;
    }

    public function getTypes(DataContainer $dc)
    {
        $options = ['default', 'section'];

        $objBlock = BlockModel::findByPk($dc->activeRecord->pid);

        if ($objBlock->carousel) {
            return ['article'];
        }

        return $options;
    }

    public function getCustomSections(DataContainer $dc)
    {
        $objRow = $this->Database->prepare("SELECT * FROM tl_layout WHERE pid=?")->limit(1)->execute($dc->activeRecord->pid);

        return trimsplit(',', $objRow->sections);
    }

    public function editContent($row, $href, $label, $title, $icon, $attributes)
    {
        if ($row['type'] != 'content') {
            return '';
        }

        return '<a href="' . $this->addToUrl($href . '&amp;id=' . $row['id']) . '" title="' . specialchars($title) . '"' . $attributes . '>'
            . Image::getHtml($icon, $label) . '</a> ';
    }

    /**
     * Return all block wrapper templates as array
     *
     * @return array
     */
    public function getWrapperTemplates()
    {
        return $this->getTemplateGroup('blocks_wrapper_');
    }

    /**
     * Return all block templates as array
     *
     * @return array
     */
    public function getBlockTemplates()
    {
        return $this->getTemplateGroup('block_');
    }

    public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
    {
        $user = BackendUser::getInstance();

        if (strlen(Input::get('tid'))) {
            $this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1), (@func_get_arg(12) ?: null));
            $this->redirect($this->getReferer());
        }

        // Check permissions AFTER checking the tid, so hacking attempts are logged
        if (!$user->hasAccess('tl_block_module::published', 'alexf')) {
            return '';
        }

        $href .= '&amp;tid=' . $row['id'] . '&amp;state=' . ($row['published'] ? '' : 1);

        if (!$row['published']) {
            $icon = 'invisible.svg';
        }

        return '<a href="' . $this->addToUrl($href) . '" title="' . specialchars($title) . '"' . $attributes . '>' . \Image::getHtml($icon, $label,
                'data-state="' . ($row['published'] ? 1 : 0) . '"') . '</a> ';
    }

    public function toggleVisibility($intId, $blnVisible, \DataContainer $dc = null)
    {
        $user     = BackendUser::getInstance();
        $database = Database::getInstance();

        // Set the ID and action
        Input::setGet('id', $intId);
        Input::setGet('act', 'toggle');

        if ($dc) {
            $dc->id = $intId; // see #8043
        }

        // Trigger the onload_callback
        if (is_array($GLOBALS['TL_DCA']['tl_block_module']['config']['onload_callback'])) {
            foreach ($GLOBALS['TL_DCA']['tl_block_module']['config']['onload_callback'] as $callback) {
                if (is_array($callback)) {
                    $this->import($callback[0]);
                    $this->{$callback[0]}->{$callback[1]}($dc);
                } elseif (is_callable($callback)) {
                    $callback($dc);
                }
            }
        }

        // Check the field access
        if (!$user->hasAccess('tl_block_module::published', 'alexf')) {
            throw new AccessDeniedException('Not enough permissions to publish/unpublish quiz item ID ' . $intId . '.');
        }

        // Set the current record
        if ($dc) {
            $objRow = $database->prepare("SELECT * FROM tl_block_module WHERE id=?")->limit(1)->execute($intId);

            if ($objRow->numRows) {
                $dc->activeRecord = $objRow;
            }
        }

        $objVersions = new Versions('tl_block_module', $intId);
        $objVersions->initialize();

        // Trigger the save_callback
        if (is_array($GLOBALS['TL_DCA']['tl_block_module']['fields']['published']['save_callback'])) {
            foreach ($GLOBALS['TL_DCA']['tl_block_module']['fields']['published']['save_callback'] as $callback) {
                if (is_array($callback)) {
                    $this->import($callback[0]);
                    $blnVisible = $this->{$callback[0]}->{$callback[1]}($blnVisible, $dc);
                } elseif (is_callable($callback)) {
                    $blnVisible = $callback($blnVisible, $dc);
                }
            }
        }

        $time = time();

        // Update the database
        $database->prepare("UPDATE tl_block_module SET tstamp=$time, published='" . ($blnVisible ? '1' : '') . "' WHERE id=?")->execute($intId);

        if ($dc) {
            $dc->activeRecord->tstamp    = $time;
            $dc->activeRecord->published = ($blnVisible ? '1' : '');
        }

        // Trigger the onsubmit_callback
        if (is_array($GLOBALS['TL_DCA']['tl_block_module']['config']['onsubmit_callback'])) {
            foreach ($GLOBALS['TL_DCA']['tl_block_module']['config']['onsubmit_callback'] as $callback) {
                if (is_array($callback)) {
                    $this->import($callback[0]);
                    $this->{$callback[0]}->{$callback[1]}($dc);
                } elseif (is_callable($callback)) {
                    $callback($dc);
                }
            }
        }

        $objVersions->create();
    }
}

class_alias(BlockModuleBackend::class, 'tl_block_module');
