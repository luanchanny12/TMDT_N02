<?php

namespace App\Http\Controllers;

class StaticPageController extends Controller
{
    public function buyingGuide()
    {
        return view('pages.buying-guide');
    }

    public function returnPolicy()
    {
        return view('pages.return-policy');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function faq()
    {
        return view('pages.faq');
    }
}
