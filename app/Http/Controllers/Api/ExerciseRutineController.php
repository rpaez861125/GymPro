<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExerciseRoutine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExerciseRutineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rutines = ExerciseRoutine::all();
        $data =  [
            'rutines' => $rutines,
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
            'name' => 'required|max:255',
            'description' => 'required',
            'difficulty' => 'required',
            'duration_minutes' => 'required|integer|min:1',
            'category' => 'required|max:255',
            'exercises' => 'required'
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la Validacion de Datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $rutine = ExerciseRoutine::create([
            'name' => $request->name,
            'description' => $request->description,
            'difficulty' => $request->difficulty,
            'duration_minutes' => $request->duration_minutes,
            'category' => $request->category,
            'exercises' => $request->exercises
        ]);
        if (!$rutine) {
            $data = [
                'message' => 'Error al crear la rutina',
                'status' => 500
            ];
            return response()->json($data, 500);
        }
        $data = [
            'message' => 'Rutina creada correctamente',
            'rutine' => $rutine,
            'status' => 201
        ];
        return response()->json($data, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rutine = ExerciseRoutine::find($id);
        if (!$rutine) {
            $data = [
                'message' => 'Rutina no encontrada',
                'status' => 404
            ];
            return response()->json($data, 404);
        }
        $data = [
            'rutine' => $rutine,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    public function showClients(string $id)
    {
        $rutine = ExerciseRoutine::find($id);
        if (!$rutine) {
            $data = [
                'message' => 'Rutina no encontrada',
                'status' => 404
            ];
            return response()->json($data, 404);
        }
        $clients = $rutine->clients;
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
        $rutine = ExerciseRoutine::find($id);
        if (!$rutine) {
            $data = [
                'message' => 'Rutina no encontrada',
                'status' => 404
            ];
            return response()->json($data, 404);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'description' => 'required',
            'difficulty' => 'required',
            'duration_minutes' => 'required|integer|min:1',
            'category' => 'required|max:255',
            'exercises' => 'required'
        ]);
        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la Validacion de Datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }
        $rutine->update([
            'name' => $request->name,
            'description' => $request->description,
            'difficulty' => $request->difficulty,
            'duration_minutes' => $request->duration_minutes,
            'category' => $request->category,
            'exercises' => $request->exercises
        ]);
        $data = [
            'message' => 'Rutina actualizada correctamente',
            'rutine' => $rutine,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rutine = ExerciseRoutine::find($id);
        if (!$rutine) {
            $data = [
                'message' => 'Rutina no encontrada',
                'status' => 404
            ];
            return response()->json($data, 404);
        }
        $rutine->delete();
        $data = [
            'message' => 'Rutina eliminada correctamente',
            'status' => 200
        ];
        return response()->json($data, 200);
    }
}
