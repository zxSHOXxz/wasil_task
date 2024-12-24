<?php

namespace App\Http\Controllers\Apps;

use App\DataTables\PropertiesDataTable;
use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index(PropertiesDataTable $dataTable)
    {
        return $dataTable->render('pages/apps.property-management.list');
    }

    public function show(Property $property)
    {
        return view('pages.apps.property-management.show', compact('property'));
    }
}
