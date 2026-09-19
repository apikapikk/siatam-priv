<?php

namespace App\Controllers\Admin;

use App\Core\Controller;

class DashboardController extends Controller
{
    public function index(): void
    {
        $dashboard = get_admin_dashboard_data();
        
        $this->render('admin/beranda', [
            'dashboard' => $dashboard,
            'pageTitle' => 'Beranda Admin',
            'activeNav' => 'beranda'
        ]);
    }
}
