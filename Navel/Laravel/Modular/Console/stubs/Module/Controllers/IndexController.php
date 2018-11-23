<?php

namespace Modules\{module}\Controllers;

use App\Http\Controllers\Controller;
//use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function show()
    {
        return view('{moduleName}::index', [
            'module' => ucfirst(config('{moduleName}.config.name')),
        ]);
    }
}
