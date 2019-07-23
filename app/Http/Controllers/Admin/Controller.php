<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as AppController;

class Controller extends AppController
{

    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        $this->middleware([
            'auth',
            'check.role',
        ]);
    }
}
