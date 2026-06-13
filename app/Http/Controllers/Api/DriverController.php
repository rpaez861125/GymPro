<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $drivers = Driver::all();
        $data =  [
            'drivers' => $drivers,
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
            'full_name' => 'required|max:255',
            'email' => 'required|email|unique:drivers,email|lowercase',
            'phone' => 'required|digits_between:6,10',
            'vehicle' => 'required|max:255',
            'schedule' => 'required|max:255',
            'salary' => 'required|numeric',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            $data = [
                'massage' => 'Error en la Validacion de Datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $driver = Driver::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'vehicle' => $request->vehicle,
            'schedule' => $request->schedule,
            'salary' => $request->salary,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        if (!$driver) {
            $data = [
                'message' => 'Error al crear el conductor',
                'status' => 500
            ];
            return response()->json($data, 500);
        }
        $data = [
            'driver' => $driver,
            'message' => 'Conductor creado correctamente',
            'status' => 201
        ];
        return response()->json($data, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $driver = Driver::find($id);

        if (!$driver) {
            $data = [
                'message' => 'Conductor no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $data = [
            'driver' => $driver,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function showClients(string $id)
    {
        $driver = Driver::find($id);

        if (!$driver) {
            $data = [
                'message' => 'Conductor no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $clients = $driver->clients;

        $data = [
            'clients' => $clients,
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
        $driver = Driver::find($id);

        if (!$driver) {
            $data = [
                'message' => 'Conductor no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'full_name' => 'required|max:255',
            'email' => 'required|email|unique:drivers,email,' . $id . '|lowercase',
            'phone' => 'required|digits_between:6,10',
            'vehicle' => 'required|max:255',
            'schedule' => 'required|max:255',
            'salary' => 'required|numeric',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la Validacion de Datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $driver->update([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'vehicle' => $request->vehicle,
            'schedule' => $request->schedule,
            'salary' => $request->salary,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);
        $data = [
            'driver' => $driver,
            'message' => 'Conductor actualizado correctamente',
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $driver = Driver::find($id);
        if (!$driver) {
            $data = [
                'message' => 'Conductor no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }
        $driver->delete();
        $data = [
            'message' => 'Conductor eliminado correctamente',
            'status' => 200
        ];
        return response()->json($data, 200);
    }
}
