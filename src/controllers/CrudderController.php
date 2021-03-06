<?php

namespace Kakposoe\Crudder\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kakposoe\Crudder\Classes\Crudder;

class CrudderController extends Controller
{
    /**
     * Show create page
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        $content = (new Crudder)->create($request->route_name);
        return view('crudder::template', compact('content'));
    }
}
