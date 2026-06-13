<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BodyMeasurement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BodyMeasurementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bodyMeasurements = BodyMeasurement::all();
        $data = [
            'bodyMeasurements' => $bodyMeasurements,
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
            'client_id' => 'required|exists:clients,id',
            'client_name' => 'required|max:255',
            'measurement_date' => 'required|date',
            'weight' => 'required|numeric',
            'body_fat' => 'nullable|numeric',
            'chest' => 'nullable|numeric',
            'waist' => 'nullable|numeric',
            'hips' => 'nullable|numeric',
            'arms' => 'nullable|numeric',
            'legs' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            $data = [
                'message' => 'Validación fallida',
                'errors' => $validator->errors(),
                'status' => 422
            ];
            return response()->json($data, 422);
        }
        $bodyMeasurement = BodyMeasurement::create([
            'client_id' => $request->client_id,
            'client_name' => $request->client_name,
            'measurement_date' => $request->measurement_date,
            'weight' => $request->weight,
            'body_fat' => $request->body_fat,
            'chest' => $request->chest,
            'waist' => $request->waist,
            'hips' => $request->hips,
            'arms' => $request->arms,
            'legs' => $request->legs,
            'notes' => $request->notes,
        ]);
        $data = [
            'message' => 'Medición corporal creada correctamente',
            'bodyMeasurement' => $bodyMeasurement,
            'status' => 201
        ];
        return response()->json($data, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bodyMeasurement = BodyMeasurement::find($id);
        if (!$bodyMeasurement) {
            $data = [
                'message' => 'Medición corporal no encontrada',
                'status' => 404
            ];
            return response()->json($data, 404);
        }
        $data = [
            'bodyMeasurement' => $bodyMeasurement,
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
        $bodyMeasurement = BodyMeasurement::find($id);
        if (!$bodyMeasurement) {
            $data = [
                'message' => 'Medición corporal no encontrada',
                'status' => 404
            ];
            return response()->json($data, 404);
        }
        $validator = Validator::make($request->all(), [
            'client_id' => 'sometimes|required|exists:clients,id',
            'client_name' => 'sometimes|required|max:255',
            'measurement_date' => 'sometimes|required|date',
            'weight' => 'sometimes|required|numeric',
            'body_fat' => 'nullable|numeric',
            'chest' => 'nullable|numeric',
            'waist' => 'nullable|numeric',
            'hips' => 'nullable|numeric',
            'arms' => 'nullable|numeric',
            'legs' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            $data = [
                'message' => 'Validación fallida',
                'errors' => $validator->errors(),
                'status' => 422
            ];
            return response()->json($data, 422);
        }
        $bodyMeasurement->update([
            'client_id' => $request->client_id ?? $bodyMeasurement->client_id,
            'client_name' => $request->client_name ?? $bodyMeasurement->client_name,
            'measurement_date' => $request->measurement_date ?? $bodyMeasurement->measurement_date,
            'weight' => $request->weight ?? $bodyMeasurement->weight,
            'body_fat' => $request->body_fat ?? $bodyMeasurement->body_fat,
            'chest' => $request->chest ?? $bodyMeasurement->chest,
            'waist' => $request->waist ?? $bodyMeasurement->waist,
            'hips' => $request->hips ?? $bodyMeasurement->hips,
            'arms' => $request->arms ?? $bodyMeasurement->arms,
            'legs' => $request->legs ?? $bodyMeasurement->legs,
            'notes' => $request->notes ?? $bodyMeasurement->notes,
        ]);
        $data = [
            'message' => 'Medición corporal actualizada correctamente',
            'bodyMeasurement' => $bodyMeasurement,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bodyMeasurement = BodyMeasurement::find($id);
        if (!$bodyMeasurement) {
            $data = [
                'message' => 'Medición corporal no encontrada',
                'status' => 404
            ];
            return response()->json($data, 404);
        }
        $bodyMeasurement->delete();
        $data = [
            'message' => 'Medición corporal eliminada correctamente',
            'status' => 200
        ];
        return response()->json($data, 200);
    }
}
