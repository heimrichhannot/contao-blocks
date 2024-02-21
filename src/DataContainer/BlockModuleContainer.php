<?php

namespace HeimrichHannot\Blocks\DataContainer;

use Contao\ArticleModel;
use Contao\Backend;
use Contao\BackendUser;
use Contao\Controller;
use Contao\CoreBundle\Csrf\ContaoCsrfTokenManager;
use Contao\CoreBundle\Exception\AccessDeniedException;
use Contao\CoreBundle\Exception\RedirectResponseException;
use Contao\Database;
use Contao\DataContainer;
use Contao\Image;
use Contao\Input;
use Contao\ModuleModel;
use Contao\StringUtil;
use Contao\System;
use Contao\Versions;
use HeimrichHannot\Blocks\Model\BlockModel;
use HeimrichHannot\Blocks\Module\BlockModule;

class BlockModuleContainer
{
    public function __construct() {
        System::loadLanguageFile('tl_content');
    }

    public function onLoadCallback(DataContainer $dc = null): void
    {
        if (null === $dc || !$dc->id || 'edit' !== Input::get('act') || 'themes' !== Input::get('do')) {
            return;
        }

        $module = ModuleModel::findByPk($dc->id);

        if (null === $module || BlockModule::TYPE === $module->type) {
            return;
        }

        unset($GLOBALS['FE_MOD']['miscellaneous'][BlockModule::TYPE]);
    }

    /**
     * tl_module blocks can not exist without tl_block items
     *
     * @param DataContainer $dc
     */
    public function cleanup(DataContainer $dc): void
    {
        $objModules = Database::getInstance()
            ->prepare('SELECT m.id FROM tl_module m LEFT JOIN tl_block b ON b.module = m.id WHERE m.block > 0 AND m.type = ? and b.id IS NULL')
            ->execute('block');

        if ($objModules->numRows < 1) {
            return;
        }

        $in = implode(",", $objModules->fetchEach('id'));
        Database::getInstance()->prepare("DELETE FROM tl_module WHERE id IN ($in)")->execute();
    }

    public function checkBlockPermission(): void
    {
        // Check current action
        $act = Input::get('act');
        if (!$act) {
            return;
        }

        $database = Database::getInstance();

        // single actions
        if (in_array($act, ['edit', 'copy', 'cut', 'delete'])) {
            $objModule = $database
                ->prepare("SELECT block FROM tl_module WHERE id = ? and type='block'")
                ->execute(Input::get('id'));

            /** @var ContaoCsrfTokenManager $csrfTokenManager */
            $csrfTokenManager = System::getContainer()->get('contao.csrf.token_manager');
            $requestToken = $csrfTokenManager->getDefaultTokenValue();

            if ($objModule->numRows) {
                throw new RedirectResponseException(
                    'contao?do=themes&amp;table=tl_block_module&amp;id=' . $objModule->block . '&amp;popup=1&amp;nb=1&amp;rt=' . $requestToken
                );
            }

            return;
        }

        // batch actions
        if (in_array($act, ['editAll', 'copyAll', 'deleteAll', 'cutAll', 'showAll']))
        {
            $session = System::getContainer()->get('request_stack')->getSession();
            $sessionBag = $session->all();

            $ids = $sessionBag['CURRENT']['IDS'];

            if (is_array($ids) && count($ids) > 0)
            {
                $objModules = $database
                    ->prepare("SELECT * FROM tl_module WHERE id IN (" . implode(',', $ids) . ") and type='block'")
                    ->execute(Input::get('id'));

                while ($objModules->next()) {
                    $index = array_search($objModules->id, $ids);
                    unset($ids[$index]);
                }

                $sessionBag['CURRENT']['IDS'] = $ids;

                // $this->Session->setData($session);
                // ... replaced by ...
                foreach ($sessionBag as $key => $value) {
                    $session->set($key, $value);
                }
            }
        }
    }

    public function getContentBlockModulesAsOptions(DataContainer $dc): array
    {
        $options = [];

        $blockModules = Database::getInstance()
            ->prepare("SELECT m.id, m.title, t.name AS 'theme' FROM tl_block_module m INNER JOIN tl_block b ON m.pid = b.id INNER JOIN tl_theme t ON t.id = b.pid WHERE m.type=? ORDER BY t.name, m.title")
            ->execute('content');

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

    public function getImageSizeOptions()
    {
        return System::getContainer()->get('contao.image.sizes')->getAllOptions();
    }

    public function editModule(DataContainer $dc): string
    {
        if ($dc->value < 1) {
            return '';
        }

        $requestToken = System::getContainer()->get('contao.csrf.token_manager')->getDefaultTokenValue();
        $title = sprintf(StringUtil::specialchars($GLOBALS['TL_LANG']['tl_content']['editalias'][1] ?? '%s'), $dc->value);
        $image = Image::getHtml('alias.gif', $GLOBALS['TL_LANG']['tl_content']['editalias'][0], 'style="vertical-align:top"');

        return sprintf(' <a href="contao?do=themes&amp;table=tl_module&amp;act=edit&amp;id=%s&amp;rt=%s" title="%s" style="padding-left:3px">%s</a>',
            $dc->value,
            $requestToken,
            $title,
            $image
        );
    }

    public function editContent($row, $href, $label, $title, $icon, $attributes): string
    {
        if ($row['type'] !== 'content') {
            return '';
        }

        return sprintf('<a href="%s" title="%s"%s>%s</a> ',
            Controller::addToUrl($href . '&amp;id=' . $row['id']),
            StringUtil::specialchars($title),
            $attributes,
            Image::getHtml($icon, $label)
        );
    }

    /**
     * @return array
     * @deprecated This is a polyfill of Contao 4's {@see \Contao\ModuleLoader::getActive()} method for Contao 5.
     */
    protected static function legacyPolyfill_getActiveModules(): array
    {
        $bundles = array_keys(System::getContainer()->getParameter('kernel.bundles'));

        $legacy = [
            'ContaoCoreBundle'       => 'core',
            'ContaoCalendarBundle'   => 'calendar',
            'ContaoCommentsBundle'   => 'comments',
            'ContaoFaqBundle'        => 'faq',
            'ContaoListingBundle'    => 'listing',
            'ContaoNewsBundle'       => 'news',
            'ContaoNewsletterBundle' => 'newsletter'
        ];

        foreach ($legacy as $bundleName => $module)
        {
            if (in_array($bundleName, $bundles))
            {
                $bundles[] = $module;
            }
        }

        return $bundles;
    }

    public function invokeI18nl10n(DataContainer $dc): void
    {
        if (in_array('i18nl10n', $this->legacyPolyfill_getActiveModules())) {
            System::loadLanguageFile('languages');
            $GLOBALS['TL_DCA']['tl_block_module']['palettes']['default'] =
                str_replace('keywords', 'keywords, language', $GLOBALS['TL_DCA']['tl_block_module']['palettes']['default']);
        }
    }

    public function getI18nl10nLanguages()
    {
        $arrLanguages = [];
        if (in_array('i18nl10n', $this->legacyPolyfill_getActiveModules())) {
            $arrLanguages = StringUtil::deserialize($GLOBALS['TL_CONFIG']['i18nl10n_languages'], true);;
            array_unshift($arrLanguages, '');
        }
        return $arrLanguages;
    }


    /**
     * Get all modules and return them as array
     *
     * @return array
     */
    public function getModules(): array
    {
        $database = Database::getInstance();
        $arrModules = [];
        $objModules = $database->execute("SELECT m.id, m.name, t.name AS theme FROM tl_module m LEFT JOIN tl_theme t ON m.pid=t.id WHERE type != 'block' ORDER BY t.name, m.name");

        while ($objModules->next()) {
            $arrModules[$objModules->theme][$objModules->id] = $objModules->name . ' (ID ' . $objModules->id . ')';
        }

        return $arrModules;
    }

    /**
     * @deprecated This is not used anywhere, apparently.
     */
    public function getCustomSections(DataContainer $dc): array
    {
        $objRow = Database::getInstance()
            ->prepare("SELECT * FROM tl_layout WHERE pid=?")
            ->limit(1)
            ->execute($dc->activeRecord->pid);

        return StringUtil::trimsplit(',', $objRow->sections);
    }

    /**
     * @deprecated This is not used anywhere, apparently.
     */
    public function getTypes(DataContainer $dc): array
    {
        $options = ['default', 'section'];
        $objBlock = BlockModel::findByPk($dc->activeRecord->pid);

        if ($objBlock->carousel) {
            return ['article'];
        }

        return $options;
    }

    /**
     * Return all block wrapper templates as array
     */
    public function getWrapperTemplates(): array
    {
        return Controller::getTemplateGroup('blocks_wrapper_');
    }

    /**
     * Return all block templates as array
     */
    public function getBlockTemplates(): array
    {
        return Controller::getTemplateGroup('block_');
    }

    /**
     * Get all articles and return them as array (article alias)
     */
    public function getArticleAlias(DataContainer $dc): array
    {
        $pIds  = [];
        $alias = [];

        $database = Database::getInstance();
        $user = BackendUser::getInstance();

        if (!$user->isAdmin)
        {
            foreach ($user->pagemounts as $id) {
                $pIds[] = $id;
                $pIds   = array_merge($pIds, $database->getChildRecords($id, 'tl_page'));
            }

            if (empty($pIds)) {
                return $alias;
            }

            $in = implode(',', array_map('intval', array_unique($pIds)));
            $objAlias = $database
                ->prepare("SELECT a.id, a.pid, a.title, a.inColumn, p.title AS parent FROM tl_article a LEFT JOIN tl_page p ON p.id=a.pid WHERE a.pid IN($in) ORDER BY parent, a.sorting")
                ->execute($dc->id);
        }
        else
        {
            $objAlias = $database
                ->prepare("SELECT a.id, a.pid, a.title, a.inColumn, p.title AS parent FROM tl_article a LEFT JOIN tl_page p ON p.id=a.pid ORDER BY parent, a.sorting")
                ->execute($dc->id);
        }

        if ($objAlias->numRows) {
            System::loadLanguageFile('tl_article');

            while ($objAlias->next()) {
                $key = sprintf('%s (ID %s)', $objAlias->parent, $objAlias->pid);
                $alias[$key][$objAlias->id] = sprintf('%s (%s, ID %s)',
                    $objAlias->title,
                    $GLOBALS['TL_LANG']['tl_article'][$objAlias->inColumn] ?? $objAlias->inColumn,
                    $objAlias->id
                );
            }
        }

        return $alias;
    }

    /**
     * Add the type and name of module element
     */
    public function addModuleInfo(array $row): string
    {
        $output = $row['id'];

        switch ($row['type'])
        {
            case 'section':
                $output = '<img alt="" src="system/themes/' . Backend::getTheme()
                    . '/icons/layout.svg" style="vertical-align:text-bottom; margin-right: 4px;"/>';
                $output .= $row['section'] . ' <span style="color:#b3b3b3;padding-left:3px">['
                    . $GLOBALS['TL_LANG']['tl_block_module']['section'][0] . ']</span>';

                return "<div style=\"float:left\">$output</div>\n";

            case 'article':
                $article = ArticleModel::findByPk($row['articleAlias']);

                $output = '<div style="float:left">';
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

                return "<div style=\"float:left\">$output</div>\n";

            case 'default':
                $module = Database::getInstance()
                    ->prepare('SELECT name,type FROM tl_module WHERE id = ?')
                    ->execute($row['module']);

                if ($module->numRows) {
                    $output = '<div style="float:left">';
                    $output .= '<img alt="" src="system/themes/' . Backend::getTheme()
                        . '/icons/modules.svg" style="vertical-align:text-bottom; margin-right: 4px;"/>';
                    $output .= $module->name . ' <span style="color:#b3b3b3;padding-left:3px">['
                        . (isset($GLOBALS['TL_LANG']['FMD'][$module->type][0]) ? $GLOBALS['TL_LANG']['FMD'][$module->type][0] : $module->type)
                        . '] - ID:' . $row['module'] . '</span>' . "</div>\n";
                }

                return $output;

            default:
                $type = $GLOBALS['TL_LANG']['tl_block_module']['type_reference'][$row['type']] ?: $row['type'];
                return "<div>$type</div>\n";
        }
    }

    public function toggleIcon($row, $href, $label, $title, $icon, $attributes): string
    {
        $user = BackendUser::getInstance();

        if (strlen(Input::get('tid'))) {
            $this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1), (@func_get_arg(12) ?: null));
            throw new RedirectResponseException(System::getReferer());
        }

        // Check permissions AFTER checking the tid, so hacking attempts are logged
        if (!$user->hasAccess('tl_block_module::published', 'alexf')) {
            return '';
        }

        $href .= '&amp;tid=' . $row['id'] . '&amp;state=' . ($row['published'] ? '' : 1);

        if (!$row['published']) {
            $icon = 'invisible.svg';
        }

        $dataState = $row['published'] ? 1 : 0;

        return sprintf('<a href="%s" title="%s"%s>%s</a> ',
            Controller::addToUrl($href),
            StringUtil::specialchars($title),
            $attributes,
            Image::getHtml($icon, $label, "data-state=\"$dataState\"")
        );
    }

    public function toggleVisibility(int|string $intId, bool $blnVisible, DataContainer $dc = null): void
    {
        $user = BackendUser::getInstance();
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
                    System::importStatic($callback[0])->{$callback[0]}->{$callback[1]}($dc);
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
                    $blnVisible = System::importStatic($callback[0])->{$callback[1]}($blnVisible, $dc);
                } elseif (is_callable($callback)) {
                    $blnVisible = $callback($blnVisible, $dc);
                }
            }
        }

        $time = time();

        $published = $blnVisible ? '1' : '';

        // Update the database
        $database
            ->prepare("UPDATE tl_block_module SET tstamp=$time, published='$published' WHERE id=?")
            ->execute($intId);

        if ($dc) {
            $dc->activeRecord->tstamp    = $time;
            $dc->activeRecord->published = ($blnVisible ? '1' : '');
        }

        // Trigger the onsubmit_callback
        if (is_array($GLOBALS['TL_DCA']['tl_block_module']['config']['onsubmit_callback'])) {
            foreach ($GLOBALS['TL_DCA']['tl_block_module']['config']['onsubmit_callback'] as $callback) {
                if (is_array($callback)) {
                    System::importStatic($callback[0])->{$callback[0]}->{$callback[1]}($dc);
                } elseif (is_callable($callback)) {
                    $callback($dc);
                }
            }
        }

        $objVersions->create();
    }
}