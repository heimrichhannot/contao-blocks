<?php

namespace HeimrichHannot\Blocks\EventListener;

use Contao\ModuleModel;
use HeimrichHannot\Blocks\Model\BlockModel;
use HeimrichHannot\Blocks\Model\BlockModuleModel;
use HeimrichHannot\UtilsBundle\EntityFinder\Element;
use HeimrichHannot\UtilsBundle\Event\EntityFinderFindEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
class EntityFinderFindEventListener
{
    public function __invoke(EntityFinderFindEvent $event): void
    {
        if (!str_starts_with($event->table, 'tl_block')) {
            return;
        }

        match($event->table) {
            BlockModel::getTable() => $this->block($event),
            BlockModuleModel::getTable() => $this->blockModule($event),
            default => null,
        };
    }

    private function block(EntityFinderFindEvent $event): void
    {
        $model = BlockModel::findByPk($event->id);

        if (null === $model) {
            return;
        }

        $event->setElement(new Element(
            $model->id,
            BlockModel::getTable(),
            'Block ' . $model->title . ' (ID: ' . $model->id . ')',
            (function () use ($model): \Iterator {
                yield ['table' => ModuleModel::getTable(), 'id' => $model->module];
            })()
        ));
    }

    /**
     * @codeCoverageIgnore
     */
    private function blockModule(EntityFinderFindEvent $event): void
    {
        $model = BlockModuleModel::findByPk($event->id);

        if (null === $model) {
            return;
        }

        $event->setElement(new Element(
            $model->id,
            BlockModuleModel::getTable(),
            'Block module ' . $model->title . ' (ID: ' . $model->id . ')',
            (function () use ($model): \Iterator {
                /* @phpstan-ignore class.notFound */
                yield ['table' => BlockModel::getTable(), 'id' => $model->pid];
            })()
        ));
    }
}