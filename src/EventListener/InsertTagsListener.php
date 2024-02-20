<?php
/**
 * Copyright (c) 2018 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\Blocks\EventListener;

use HeimrichHannot\Blocks\BlockChild;
use HeimrichHannot\Blocks\Model\BlockModuleModel;

class InsertTagsListener
{
    private array $supportedTags = [
        'insert_block_child',
    ];

    /**
     * Replaces block insert tags.
     */
    public function onReplaceInsertTags(string $tag): false|string
    {
        $elements = explode('::', $tag);
        $key      = strtolower($elements[0]);

        if (in_array($key, $this->supportedTags, true)) {
            return $this->replaceBlockInsertTag($key, $elements[1]);
        }

        return false;
    }

    /**
     * Replaces a block-related insert tag.
     */
    private function replaceBlockInsertTag(string $insertTag, string|int $id): string
    {
        if ($insertTag === 'insert_block_child')
        {
            if (null === ($model = BlockModuleModel::findByPk($id))) {
                return '';
            }

            $blockChild = new BlockChild($model);
            return $blockChild->generate();
        }

        return '';
    }
}