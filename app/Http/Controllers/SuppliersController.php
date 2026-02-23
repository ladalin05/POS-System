<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\DataTables\SupplierDataTable;

class SuppliersController extends Controller
{
    public function index(SupplierDataTable $dataTable)
    {
        return $dataTable->render('suppliers.index');
    }

    public function create(): View
    {
        return view('suppliers.create');
    }

    public function store(Request $request): RedirectResponse
    {

        return Redirect::route('suppliers.index')->with('success', 'Supplier created successfully.');
    }
}
