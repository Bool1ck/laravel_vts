<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\ConnectingPoint;
use App\Models\Region;
use App\Models\RoleRegionUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConnectionPointController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Region $region)
    {
        $points =[];
        if ($region->userHasPermission(Auth::user())) {
            $points = ConnectingPoint::all()->where('region_id', $region->id);
        }
        return view('connectionpoints.index', compact('points'));
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
