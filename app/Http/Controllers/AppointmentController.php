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

    public function updateAppointment($appointmentID, Request $request)
    {
        $appointment = Appointment::find($appointmentID);
        $appointment->name = $request->name;
        $appointment->email = $request->email;
        $appointment->doctorID = $request->doctorID;
        $appointment->phone = $request->phone;
        $appointment->gender = $request->gender;
        $appointment->age = $request->age;
        $appointment->problem = $request->problem;
        $appointment->date = $request->date;
        $appointment->time = $request->time;
        $appointment->save();
        return response()->json(['message' => 'Appointment updated successfully']);
    }
    public function showAllAppointments()
    {
        $appointments = Appointment::with(['doctor', 'support'])->get();
        return response()->json($appointments);
    }
    public function showPendingAppointments()
    {
        $appointment = Appointment::with(['doctor', 'support'])
                                    ->where('status', 'pending')
                                    ->get();
        return response()->json($appointment);
    }
    public function showAcceptedAppointments()
    {
        $appointment = Appointment::with(['doctor', 'support'])
                                    ->where('status', 'accepted')
                                    ->get();
        return response()->json($appointment);
    }
    public function showRejectedAppointments()
    {
        $appointment = Appointment::with(['doctor', 'support'])
                                    ->where('status', 'rejected')
                                    ->get();
        return response()->json($appointment);
    }
    public function showPendingDoctorAppointments($doctorID)
    {
        $appointments = Appointment::where('doctorID', $doctorID)
                                    ->where('status', 'pending')
                                    ->with(['doctor', 'support'])
                                    ->orderByDesc('date')
                                    ->orderByDesc('time')
                                    ->get();
        return response()->json($appointments);
    }
    public function showAppointmentList($doctorID, $date)
    {
        $appointments = Appointment::where('doctorID', $doctorID)
                                    ->whereDate('date', $date)
                                    ->orderByDesc('time')
                                    ->with(['doctor', 'support'])
                                    ->get();
        return response()->json($appointments);
    }
    public function showMonthlyAppointmentList($doctorID, $month, $year)
    {
        $firstDayOfMonth = "{$year}-{$month}-01";
        $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfMonth));

        // Now you can use $firstDayFormatted and $lastDayFormatted in your query or wherever you need them
        // Example query:
        $appointments = Appointment::where('doctorID', $doctorID)
                                   ->whereDate('date', '>=', $firstDayOfMonth)
                                   ->whereDate('date', '<=', $lastDayOfMonth)
                                   ->orderBy('date')
                                   ->get();

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
    public function destroy($appointmentID)
    {
        $appointment = Appointment::find($appointmentID);
        $appointment->delete();
        return response()->json(['message' => 'Appointment Deleted'], 200);
    }
}
