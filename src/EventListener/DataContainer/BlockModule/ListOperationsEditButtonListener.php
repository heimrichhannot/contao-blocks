<?php

namespace HeimrichHannot\Blocks\EventListener\DataContainer\BlockModule;

use Contao\CoreBundle\DataContainer\DataContainerOperation;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;

#[AsCallback(table: 'tl_block_module', target: 'list.operations.children.button')]
class ListOperationsEditButtonListener
{
    public function __invoke(DataContainerOperation $operation): void
    {
        if ('content' === ($operation->getRecord()['type'] ?? '')) {
            return;
        }

        $operation->setHtml('');
    }
}