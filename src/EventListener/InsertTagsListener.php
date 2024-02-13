<?php
/**
 * Copyright (c) 2018 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\Blocks\EventListener;


class InsertTagsListener
{
    /**
     * @var array
     */
    private $supportedTags = [
        'insert_block_child',
    ];


    /**
     * Replaces block insert tags.
     *
     * @param string $tag
     *
     * @return string|false
     */
    public function onReplaceInsertTags($tag)
    {
        $elements = explode('::', $tag);
        $key      = strtolower($elements[0]);

        if (\in_array($key, $this->supportedTags, true)) {
            return $this->replaceBlockInsertTag($key, $elements[1]);
        }

        return false;
    }


    /**
     * Replaces an block-related insert tag.
     *
     * @param string $insertTag
     * @param string $id
     *
     * @return string
     */
    private function replaceBlockInsertTag($insertTag, $id)
    {
        switch ($insertTag) {
            case 'insert_block_child':

                if (null === ($model = BlockModuleModel::findByPk($id))) {
                    return '';
                }

                $blockChild = new BlockChild($model);
                return $blockChild->generate();
        }

        return '';
    }
}

class_alias(InsertTagsListener::class, 'HeimrichHannot\Blocks\InsertTagsListener');
