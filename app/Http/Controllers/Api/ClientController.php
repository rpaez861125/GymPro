<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Api\UserProvisioningTrait;


class ClientController extends Controller
{
    use UserProvisioningTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = Client::all();

        $data =  [
            'clients' => $clients,
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
            'email' => 'required|email|unique:clients,email|unique:users,email',
            'phone' => 'required|max:20',
            'birth_date' => 'required|date',
            'photo_url' => 'nullable|url',
            'trainer_id' => 'nullable|exists:trainers,id',
            'routine_id' => 'nullable|exists:routines,id',
            'driver_id' => 'nullable|exists:drivers,id',
            'membership_type' => 'required|string',
            'membership_status' => 'required|string',
            'membership_expiry_date' => 'nullable|date',
            'join_date' => 'required|nullable|date',
            'status' => 'required|string',
            'emergency_contact' => 'nullable|string',
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
        // Aprovisionar usuario en `users` (rol: client)
        $user = $this->provisionUser(
            'client',
            $request->full_name,
            $request->email
        );

        if (!$user) {
            $data = [
                'message' => 'Cliente no pudo crear el usuario asociado',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        $client = Client::create([
            'user_id' => $user->id,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'birth_date' => $request->birth_date,
            'photo_url' => $request->photo_url,
            'trainer_id' => $request->trainer_id,
            'routine_id' => $request->routine_id,
            'driver_id' => $request->driver_id,
            'membership_type' => $request->membership_type,
            'membership_status' => $request->membership_status,
            'membership_expiry_date' => $request->membership_expiry_date,
            'join_date' => $request->join_date,
            'status' => $request->status,
            'emergency_contact' => $request->emergency_contact,
            'notes' => $request->notes
        ]);

        if (!$client) {
            $data = [
                'message' => 'Error al crear el cliente',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        $data = [
            'message' => 'Cliente creado correctamente',
            'client' => $client,
            'user' => $user,
            'status' => 201
        ];
        return response()->json($data, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $client = Client::find($id);

        if (!$client) {
            $data = [
                'message' => 'Cliente no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $data = [
            'client' => $client,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function showAttendance(string $id)
    {
        $client = Client::find($id);

        if (!$client) {
            $data = [
                'message' => 'Cliente no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $attendance = $client->attendances;

        $data = [
            'attendance' => $attendance,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function showPayments(string $id)
    {
        $client = Client::find($id);

        if (!$client) {
            $data = [
                'message' => 'Cliente no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $payments = $client->payments;

        $data = [
            'payments' => $payments,
            'status' => 200
        ];

        return response()->json($data, 200);
    }
    public function showBodyMeasurements(string $id)
    {
        $client = Client::find($id);

        if (!$client) {
            $data = [
                'message' => 'Cliente no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $bodyMeasurements = $client->bodyMeasurements;

        $data = [
            'body_measurements' => $bodyMeasurements,
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
        $client = Client::find($id);

        if (!$client) {
            $data = [
                'message' => 'Cliente no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'full_name' => 'sometimes|required|max:255',
            'email' => 'sometimes|required|email|unique:clients,email,' . $id,
            'phone' => 'sometimes|required|max:20',
            'birth_date' => 'nullable|date',
            'photo_url' => 'nullable|url',
            'trainer_id' => 'nullable|exists:trainers,id',
            'routine_id' => 'nullable|exists:routines,id',
            'driver_id' => 'nullable|exists:drivers,id',
            'membership_type' => 'nullable|string',
            'membership_status' => 'nullable|string',
            'membership_expiry_date' => 'nullable|date',
            'join_date' => 'nullable|date',
            'status' => 'nullable|string',
            'emergency_contact' => 'nullable|string',
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

        $client->update([
            'full_name' => $request->full_name ?? $client->full_name,
            'email' => $request->email ?? $client->email,
            'phone' => $request->phone ?? $client->phone,
            'birth_date' => $request->birth_date ?? $client->birth_date,
            'photo_url' => $request->photo_url ?? $client->photo_url,
            'trainer_id' => $request->trainer_id ?? $client->trainer_id,
            'routine_id' => $request->routine_id ?? $client->routine_id,
            'driver_id' => $request->driver_id ?? $client->driver_id,
            'membership_type' => $request->membership_type ?? $client->membership_type,
            'membership_status' => $request->membership_status ?? $client->membership_status,
            'membership_expiry_date' => $request->membership_expiry_date ?? $client->membership_expiry_date,
            'join_date' => $request->join_date ?? $client->join_date,
            'status' => $request->status ?? $client->status,
            'emergency_contact' => $request->emergency_contact ?? $client->emergency_contact,
            'notes' => $request->notes ?? $client->notes
        ]);

        $data = [
            'message' => 'Cliente actualizado correctamente',
            'client' => $client,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $client = Client::find($id);

        if (!$client) {
            $data = [
                'message' => 'Cliente no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $client->delete();

        $data = [
            'message' => 'Cliente eliminado correctamente',
            'status' => 200
        ];

        return response()->json($data, 200);
    }
}
