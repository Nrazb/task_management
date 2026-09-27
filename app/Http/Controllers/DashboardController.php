<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $statistics = [
            'total' => Task::count(),

            'pending' => Task::where(
                'status',
                'pending'
            )->count(),

            'in_progress' => Task::where(
                'status',
                'in_progress'
            )->count(),

            'completed' => Task::where(
                'status',
                'completed'
            )->count(),
        ];

        $recentActivities = Task::latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'statistics',
            'recentActivities'
        ));
    }
}
