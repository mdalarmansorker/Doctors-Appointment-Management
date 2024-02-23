<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'gender' => 'required',
            'age' => 'required|integer',
            'problem' => 'required',
            'date' => 'required|date',
            'time' => 'required',
            'doctorID' => 'required|integer',
            'supportID' => 'required',
        ]);
        
        // Add additional fields to the validated data
        $validated['supportID'] = intval($request->supportID);
        $validated['status'] = 'pending';
    
        // Create the appointment
        $appointment = Appointment::create($validated);
    
        // Return a success response with status code 201
        return response()->json(['message' => 'Appointment Created Successfully!'], 201);
    }
    // public function store(Request $request)
    // {
    //     // Extract data from the request
    //     $data = $request->all();

    //     // Add additional fields to the data
    //     $data['status'] = 'pending';

    //     // // Create the appointment
    //     $appointment = Appointment::create($data);

    //     // Return a success response with status code 201
    //     return response()->json(['message' => 'Appointment Created Successfully!', 'data' => $data], 201);
    // }


    /**
     * Display the specified resource.
     */
    // public function show()
    // {
    //     $appointments = Appointment::all();
    //     return response()->json($appointments);
    // }
    public function show()
    {
        $appointments = Appointment::with(['doctor', 'support'])->get();
        return response()->json($appointments);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        //
    }
}
