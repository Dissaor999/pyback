<?php

namespace App\Http\Controllers\sale;

use App\Http\Controllers\Controller;
use App\Models\PaymentType;
use Illuminate\Http\Request;
use Validator;

class PaymentTypeController extends Controller
{

    public function index(): \Illuminate\Http\JsonResponse
    {
        $paymentTypes = PaymentType::all();
        $content = [
            'status' => true,
            'message' => 'Lista de tipos de pagos',
            'data' => $paymentTypes
        ];
        return response()->json($content, 200);
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'name' => 'required|string|min:3|max:50|unique:payment_types',
            'sat_code' => 'required|string|min:3|max:15|unique:payment_types'
        ]);

        if ($validator->fails()) {
            $content = [
                'status' => false,
                'message' => 'Entradas no válidas.',
                'error' => $validator->errors()
            ];
            return response()->json($content, 400);
        }

        $paymentType = PaymentType::create($requestData);
        $content = [
            'status' => true,
            'message' => 'Tipo de pago creado con éxito.',
            'data' => $paymentType
        ];
        return response()->json($content, 201);
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $paymentType =  PaymentType::whereId($id)->first();
        if (is_null($paymentType)) {
            $content = [
                'status' => false,
                'message' => 'Tipo de pago no encontrado.'
            ];
            return response()->json($content, 404);
        }
        $content = [
            'success' => true,
            'message' => 'Tipo de pago encontrado.',
            'data' => $paymentType
        ];
        return response()->json($content, 200);
    }

    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $paymentType =  PaymentType::whereId($id)->first();
        if (is_null($paymentType)) {
            $content = [
                'status' => false,
                'message' => 'Tipo de pago no encontrado.'
            ];
            return response()->json($content, 404);
        }

        $requestData = $request->all();

        $validator = Validator::make($requestData, [
            'name' => 'required|string|min:3|max:50',
            'sat_code' => 'required|string|min:3|max:15'
        ]);

        if ($validator->fails()) {
            $content = [
                'status' => false,
                'message' => 'Entradas no válidas.',
                'error' => $validator->errors()
            ];
            return response()->json($content, 400);
        }

        $paymentType->name = $requestData['name'];
        $paymentType->sat_code = $requestData['sat_code'];
        $paymentType->save();

        $content = [
            'status' => true,
            'message' => 'Tipo de pago actualizado con éxito.',
            'data' => $paymentType
        ];
        return response()->json($content, 201);
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $paymentType =  PaymentType::whereId($id)->first();
        if (is_null($paymentType)) {
            $content = [
                'status' => false,
                'message' => 'Tipo de pago no encontrado.'
            ];
            return response()->json($content, 404);
        }

        $paymentType->delete();
        $content = [
            'status' => true,
            'message' => 'Product borrado exitosamente.',
            'data' => $paymentType
        ];
        return response()->json($content, 200);
    }
}
