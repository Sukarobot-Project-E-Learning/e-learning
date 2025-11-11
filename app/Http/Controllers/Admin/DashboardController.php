<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // TODO: Add statistics queries
        // $totalStudents = User::where('role', 'user')->count();
        // $totalCourses = Course::count();
        // $totalEvents = Event::count();
        // $totalBlogs = Blog::count();

        return view('admin.dashboard');
    }
}

