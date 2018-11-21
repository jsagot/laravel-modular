<?php

namespace Modules\Demo\Controllers;

use App\Http\Controllers\Controller;
//use Illuminate\Http\Request;
use Modules\Demo\Facades\Dummy;

class IndexController extends Controller
{
    public function show()
    {
        return view('demo::index', ['dummy' => Dummy::getDummy()]);
    }
}
