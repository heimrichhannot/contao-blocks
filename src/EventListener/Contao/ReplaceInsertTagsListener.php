<?php
/**
 * @copyright Heimrich & Hannot GmbH, 2025
 * @license LGPL-3.0-or-later
 */
namespace HeimrichHannot\Blocks\EventListener\Contao;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use HeimrichHannot\Blocks\BlockChild;
use HeimrichHannot\Blocks\Model\BlockModuleModel;

#[AsHook('replaceInsertTags')]
class ReplaceInsertTagsListener
{
    public function __invoke(string $tag): false|string
    {
        if (!$fragments = \explode('::', $tag)) {
            return false;
        }

        return match (\strtolower(\array_shift($fragments))) {
            'insert_block_child' => $this->insertBlockChild((int) ($fragments[0] ?? 0)),
            default => false,
        };
    }

    private function insertBlockChild(int $id): string
    {
        if (!$id || !$model = BlockModuleModel::findByPk($id)) {
            return '';
        }

        return (new BlockChild($model))->generate();
    }
}