<?php

namespace Modules\Demo\Repository;

use Modules\Demo\Repository\Contracts\DummyRepositoryInterface;

/**
 *
 */
class DummyRepository implements DummyRepositoryInterface
{

    public function getDummy()
    {
        return 'Dummy';
    }
}
