<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller as AppController;

class Controller extends AppController
{
    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->middleware([
            'auth',
            'pass_except_admin_role',
        ]);
    }
}
