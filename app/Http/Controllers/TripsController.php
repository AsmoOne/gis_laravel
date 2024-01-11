<?php

namespace App\Http\Controllers;

use App\Http\Resources\TripsResource;
use App\Models\Trips;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TripsController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       return TripsResource::collection(Trips::all());
    }

    public function getDrivers()
    {
        $drivers = Trips::getDriverIds();

        return response()->json($drivers);
    } 

    public function csv()
    {
        $drivers = Trips::getDriverIds();
        $toCsv[] = ['driver_id', 'total_minutes_with_passenger'];

        foreach ($drivers as $driver) {
            $toCsv[] = [$driver, Trips::calculateTripsDurationMinutes(Trips::getTripsByDriverId($driver))];
        }

        return response()->streamDownload(function () use ($toCsv) {
            foreach ($toCsv as $line) {
                echo implode(',', $line) . PHP_EOL;
            }
        }, 'output_data.csv');
    }

    public function calculate(){
        $driversIds = request()->all();
        $response = [];

        foreach ($driversIds as $driver) {
            $response[] = ['driver_id' => $driver, 'total_minutes_with_passenger' => Trips::calculateTripsDurationMinutes(Trips::getTripsByDriverId($driver))];
        }

        return response()->json($response);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Trips $trips)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Trips $trips)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Trips $trips)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Trips $trips)
    {
        //
    }
}
