<?php

namespace App\Http\Controllers;

class FrontendController extends Controller
{
    public function home()
    {
        return view('public.home');
    }

    public function features()
    {
        return view('public.features');
    }

    public function pricing()
    {
        return view('public.pricing');
    }

    public function about()
    {
        return view('public.about');
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function privacy()
    {
        return view('public.privacy');
    }

    public function terms()
    {
        return view('public.terms');
    }
}
