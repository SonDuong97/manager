<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Controller as AdminController;

class TopController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.home');
    }
}
