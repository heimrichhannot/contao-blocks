<?php

namespace HeimrichHannot\Blocks\DataContainer;

use Contao\BackendUser;
use Contao\Controller;
use Contao\CoreBundle\Csrf\ContaoCsrfTokenManager;
use Contao\CoreBundle\Exception\RedirectResponseException;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\CoreBundle\Image\ImageSizes;
use Contao\Database;
use Contao\DataContainer;
use Contao\Image;
use Contao\Input;
use Contao\ModuleModel;
use Contao\StringUtil;
use Contao\System;
use HeimrichHannot\Blocks\Model\BlockModel;
use HeimrichHannot\Blocks\Module\BlockModule;
use Symfony\Component\HttpFoundation\RequestStack;

class BlockModuleContainer
{
    public function __construct(
        protected array                  $kernelBundles,
        protected ContaoCsrfTokenManager $csrfTokenManager,
        protected RequestStack           $requestStack,
        protected ImageSizes             $imageSizes,
        protected ContaoFramework        $framework
    ) {
        System::loadLanguageFile('tl_content');
    }

    public function onLoadCallback(?DataContainer $dc = null): void
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

            $requestToken = $this->csrfTokenManager->getDefaultTokenValue();

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
            $session = $this->requestStack->getSession();
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

    public function getImageSizeOptions(): array
    {
        return $this->imageSizes->getAllOptions();
    }

    public function editModule(DataContainer $dc): string
    {
        if ($dc->value < 1) {
            return '';
        }

        $requestToken = $this->csrfTokenManager->getDefaultTokenValue();
        $title = sprintf(StringUtil::specialchars($GLOBALS['TL_LANG']['tl_content']['editalias'][1] ?? '%s'), $dc->value);
        $image = Image::getHtml('alias.gif', $GLOBALS['TL_LANG']['tl_content']['editalias'][0], 'style="vertical-align:top"');

        return sprintf(' <a href="contao?do=themes&amp;table=tl_module&amp;act=edit&amp;id=%s&amp;rt=%s" title="%s" style="padding-left:3px">%s</a>',
            $dc->value,
            $requestToken,
            $title,
            $image
        );
    }

    public function invokeI18nl10n(DataContainer $dc): void
    {
        if (in_array('i18nl10n', $this->kernelBundles)) {
            System::loadLanguageFile('languages');
            $GLOBALS['TL_DCA']['tl_block_module']['palettes']['default'] =
                str_replace('keywords', 'keywords, language', $GLOBALS['TL_DCA']['tl_block_module']['palettes']['default']);
        }
    }

    public function getI18nl10nLanguages()
    {
        $arrLanguages = [];
        if (in_array('i18nl10n', $this->kernelBundles)) {
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

            $in = implode(',', array_map(intval(...), array_unique($pIds)));
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
}