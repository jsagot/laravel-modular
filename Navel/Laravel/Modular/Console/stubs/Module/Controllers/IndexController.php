<?php

namespace Modules\{module}\Controllers;

use App\Http\Controllers\Controller;
//use Illuminate\Http\Request;
use Modules\{module}\Facades\Dummy;

class IndexController extends Controller
{
    public function show()
    {
        return view('{moduleName}::index', ['dummy' => Dummy::getDummy()]);
    }
}
