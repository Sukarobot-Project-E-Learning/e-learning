<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {
        $katakatagodeg = "hayang uih";
        return view('client.layout.page.about',compact('katakatagodeg'));
    }
}
