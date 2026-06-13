<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Payment;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::all();
        $data = [
            'payments' => $payments,
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
            'amount' => 'required|numeric',
            'payment_date' => 'required|date',
            'payment_method' => 'required|max:255',
            'concept' => 'required|max:255',
            'status' => 'required',
            'notes' => 'nullable|max:255',
        ]);
        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la Validacion de Datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }
        $payment = Payment::create([
            'client_id' => $request->client_id,
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'payment_method' => $request->payment_method,
            'concept' => $request->concept,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);
        $data = [
            'payment' => $payment,
            'message' => 'Pago creado correctamente',
            'status' => 201
        ];
        return response()->json($data, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $payment = Payment::find($id);
        if (!$payment) {
            $data = [
                'message' => 'Pago no Encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }
        $data = [
            'payment' => $payment,
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
        $payment = Payment::find($id);
        if (!$payment) {
            $data = [
                'message' => 'Pago no Encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }
        $validator = Validator::make($request->all(), [
            'client_id' => 'required|exists:clients,id',
            'amount' => 'required|numeric',
            'payment_date' => 'required|date',
            'payment_method' => 'required|max:255',
            'concept' => 'required|max:255',
            'status' => 'required',
            'notes' => 'nullable|max:255',
        ]);
        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la Validacion de Datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }
        $payment->update([
            'client_id' => $request->client_id ?? $payment->client_id,
            'amount' => $request->amount ?? $payment->amount,
            'payment_date' => $request->payment_date ?? $payment->payment_date,
            'payment_method' => $request->payment_method ?? $payment->payment_method,
            'concept' => $request->concept ?? $payment->concept,
            'status' => $request->status ?? $payment->status,
            'notes' => $request->notes ?? $payment->notes,
        ]);
        $data = [
            'payment' => $payment,
            'message' => 'Pago actualizado correctamente',
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            $data = [
                'message' => 'Pago no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $payment->delete();

        $data = [
            'message' => 'Pago eliminado correctamente',
            'status' => 200
        ];

        return response()->json($data, 200);
    }
}
