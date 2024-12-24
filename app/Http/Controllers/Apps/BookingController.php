<?php

namespace App\Http\Controllers\Apps;

use App\DataTables\BookingDataTable;
use App\DataTables\BookingsDataTable;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Property;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(BookingDataTable $dataTable)
    {
        return $dataTable->render('pages/apps.booking-management.list');
    }
    public function create(Request $request)
    {
        $property = Property::find($request->id);
        $startDate = date('Y-m-d');

        return view('booking.create', [
            'property' => $property,
            'startDate' => $startDate
        ]);
    }
}
