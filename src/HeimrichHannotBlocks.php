<?php

/**
 * Copyright (c) 2024 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\Blocks;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class HeimrichHannotBlocks extends Bundle
{
    public function getPath(): string
    {
        return dirname(__DIR__);
    }
}