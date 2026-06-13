<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Trainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Api\UserProvisioningTrait;

class TrainerController extends Controller
{
    use UserProvisioningTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trainers = Trainer::all();

        $data =  [
            'trainers' => $trainers,
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
            'email' => 'required|email|unique:trainers,email|lowercase|unique:users,email',
            'phone' => 'required|digits_between:6,10',
            'specialty' => 'required|max:255',
            'status' => 'required',
            'salary' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            $data = [
                'massage' => 'Error en la Validacion de Datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        // Aprovisionar usuario en `users` (rol: trainer)
        $user = $this->provisionUser(
            'trainer',
            $request->full_name,
            $request->email
        );

        if (!$user) {
            $data = [
                'massage' => 'Error al crear el usuario asociado al entrenador',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        $trainer = Trainer::create([
            'user_id' => $user->id,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'specialty' => $request->specialty,
            'photo_url' => $request->photo_url,
            'status' => $request->status,
            'salary' => $request->salary
        ]);

        if (!$trainer) {
            $data = [
                'massage' => 'Error al Crear el Entrenador',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        $data = [
            'message' => 'Entrenador creado correctamente',
            'trainer' => $trainer,
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

        $trainer = Trainer::find($id);

        if (!$trainer) {
            $data = [
                'message' => 'Entrenador no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $data = [
            'trainer' => $trainer,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    public function showClients(string $id)
    {
        $trainer = Trainer::find($id);

        if (!$trainer) {
            $data = [
                'message' => 'Entrenador no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $clients = $trainer->clients;

        $data = [
            'clients' => $clients,
            'status' => 200
        ];
        return response()->json($data, 200);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function editPartial(Request $request, string $id)
    {
        $trainer = Trainer::find($id);

        if (!$trainer) {
            $data = [
                'message' => 'Entrenador no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }
        $validator = Validator::make($request->all(), [
            'full_name' => 'max:255',
            'email' => 'lowercase|email|unique:trainers,email,' . $id,
            'phone' => 'digits_between:6,10',
            'specialty' => 'max:255',
            'salary' => 'numeric'
        ]);

        if ($validator->fails()) {
            $data = [
                'massage' => 'Error en la Validacion de Datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        if ($request->has('full_name')) {
            $trainer->full_name = $request->full_name;
        }
        if ($request->has('email')) {
            $trainer->email = $request->email;
        }
        if ($request->has('phone')) {
            $trainer->phone = $request->phone;
        }
        if ($request->has('specialty')) {
            $trainer->specialty = $request->specialty;
        }
        if ($request->has('photo_url')) {
            $trainer->photo_url = $request->photo_url;
        }
        if ($request->has('status')) {
            $trainer->status = $request->status;
        }
        if ($request->has('salary')) {
            $trainer->salary = $request->salary;
        }
        $trainer->save();

        $data = [
            'message' => 'Entrenador actualizado correctamente',
            'trainer' => $trainer,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $trainer = Trainer::find($id);

        if (!$trainer) {
            $data = [
                'message' => 'Entrenador no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'full_name' => 'required|max:255',
            'email' => 'required|lowercase|email|unique:trainers,email,' . $id,
            'phone' => 'required|digits_between:6,10',
            'specialty' => 'required|max:255',
            'status' => 'required',
            'salary' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            $data = [
                'massage' => 'Error en la Validacion de Datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }
        $trainer->update([
            'full_name' => $request->full_name ?? $trainer->full_name,
            'email' => $request->email ?? $trainer->email,
            'phone' => $request->phone ?? $trainer->phone,
            'specialty' => $request->specialty ?? $trainer->specialty,
            'photo_url' => $request->photo_url ?? $trainer->photo_url,
            'status' => $request->status ?? $trainer->status,
            'salary' => $request->salary ?? $trainer->salary
        ]);

        $data = [
            'message' => 'Entrenador actualizado correctamente',
            'trainer' => $trainer,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $trainer = Trainer::find($id);

        if (!$trainer) {
            $data = [
                'message' => 'Entrenador no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $trainer->delete();

        $data = [
            'message' => 'Entrenador eliminado correctamente',
            'status' => 200
        ];
        return response()->json($data, 200);
    }
}
