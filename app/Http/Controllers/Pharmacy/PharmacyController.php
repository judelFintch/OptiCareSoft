<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;

class PharmacyController extends Controller
{
    public function index()
    {
        $this->authorize('pharmacy.view');
        return view('pages.pharmacy.index');
    }
}
