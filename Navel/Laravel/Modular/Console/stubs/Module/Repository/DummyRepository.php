<?php

namespace Modules\{module}\Repository;

use Modules\{module}\Repository\Contracts\DummyRepositoryInterface;

/**
 *
 */
class DummyRepository implements DummyRepositoryInterface
{

    public function getDummy()
    {
        return '{module}';
    }
}
