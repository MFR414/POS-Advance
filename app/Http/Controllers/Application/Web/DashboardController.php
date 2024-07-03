<?php

namespace App\Http\Controllers\Application\Web;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Services\DashboardServices;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $dashboardServices = new DashboardServices();

        if( auth()->user()->hasAnyRole('Admin','Super Admin','Owner User')){
            $dashboardData = $dashboardServices->getDashboardData();
        } else {
            $dashboardData = $dashboardServices->getMonthlyDashboardData();
        }

        // dd($dashboardData);
        
        return view('application.dashboard',[
            //'vehicle chart data
            'data' => json_encode($dashboardData),
            'active_page' => 'dashboard',
        ]);
    }
}
