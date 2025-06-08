<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class HomePageController extends Controller
{
    function index(){
        return Inertia::render('HomePage');
    }

    function aboutus(){
        return Inertia::render('AboutUsPage');
    }

    function ourservices(){
        return Inertia::render('OurServicesPage');
    }
}
