<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attendances = Attendance::all();
        $data = [
            'attendances' => $attendances,
            'status' => 200
        ];
        return response()->json($data, 200);
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
        $validator = Validator::make($request->all(), [
            'check_in_date' => 'required|date',
            'check_in_time' => 'required|date_format:H:i:s',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors(),
                'status' => 422
            ];
            return response()->json($data, 422);
        }

        $attendance = Attendance::create([
            'client_id' => $request->client_id,
            'client_name' => $request->client_name,
            'check_in_date' => $request->check_in_date,
            'check_in_time' => $request->check_in_time,
            'notes' => $request->notes,
        ]);
        $data = [
            'massage' => 'Asistencia registrada correctamente',
            'attendance' => $attendance,
            'status' => 201
        ];
        return response()->json($data, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $attendance = Attendance::find($id);
        if (!$attendance) {
            $data = [
                'message' => 'Asistencia no encontrada',
                'status' => 404
            ];
            return response()->json($data, 404);
        }
        $data = [
            'attendance' => $attendance,
            'status' => 200
        ];
        return response()->json($data, 200);
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
        $attendance = Attendance::find($id);
        if (!$attendance) {
            $data = [
                'message' => 'Asistencia no encontrada',
                'status' => 404
            ];
            return response()->json($data, 404);
        }
        $validator = Validator::make($request->all(), [
            'check_in_date' => 'required|date',
            'check_in_time' => 'required|date_format:H:i:s',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors(),
                'status' => 422
            ];
            return response()->json($data, 422);
        }
        $attendance->update([
            'client_id' => $request->client_id ?? $attendance->client_id,
            'client_name' => $request->client_name ?? $attendance->client_name,
            'check_in_date' => $request->check_in_date ?? $attendance->check_in_date,
            'check_in_time' => $request->check_in_time ?? $attendance->check_in_time,
            'notes' => $request->notes ?? $attendance->notes,
        ]);
        $data = [
            'message' => 'Asistencia actualizada correctamente',
            'attendance' => $attendance,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $attendance = Attendance::find($id);
        if (!$attendance) {
            $data = [
                'message' => 'Asistencia no encontrada',
                'status' => 404
            ];
            return response()->json($data, 404);
        }
        $attendance->delete();
        $data = [
            'message' => 'Asistencia eliminada correctamente',
            'status' => 200
        ];
        return response()->json($data, 200);
    }
}
