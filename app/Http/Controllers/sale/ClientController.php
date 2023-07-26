<?php

namespace App\Http\Controllers\sale;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Validator;

class ClientController extends Controller
{

    public function index(): \Illuminate\Http\JsonResponse
    {
        $clients = Client::all();
        $content = [
            'status' => true,
            'message' => 'Lista de Clientes.',
            'data' => $clients
        ];
        return response()->json($content, 200);
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'name' => 'required|string|min:5|max:150',
            'rfc' => 'string|min:3|max:15|unique:clients',
            'phone' => 'required|string|min:10|max:15|unique:clients',
            'email' => 'string|email|max:50|unique:clients',
        ]);

        if ($validator->fails()) {
            $content = [
                'status' => false,
                'message' => 'Entradas no válidas.',
                'error' => $validator->errors()
            ];
            return response()->json($content, 400);
        }

        $client = Client::create($requestData);
        $content = [
            'status' => true,
            'message' => 'Cliente creado con éxito.',
            'data' => $client
        ];
        return response()->json($content, 201);
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $client = Client::whereId($id)->first();
        if (is_null($client)) {
            $content = [
                'status' => false,
                'message' => 'Cliente no encontrado.'
            ];
            return response()->json($content, 404);
        }
        $content = [
            'success' => true,
            'message' => 'Cliente encontrado.',
            'data' => $client
        ];
        return response()->json($content, 200);
    }

    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $client = Client::whereId($id)->first();
        if (is_null($client)) {
            $content = [
                'status' => false,
                'message' => 'Cliente no encontrado.'
            ];
            return response()->json($content, 404);
        }

        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'name' => 'required|string|min:5|max:150',
            'rfc' => 'string|min:3|max:15',
            'phone' => 'required|string|min:10|max:15',
            'email' => 'string|email|max:50',
        ]);

        if ($validator->fails()) {
            $content = [
                'status' => false,
                'message' => 'Entradas no válidas.',
                'error' => $validator->errors()
            ];
            return response()->json($content, 400);
        }

        $client->name = $requestData['name'];
        $client->address = $requestData['address'];
        $client->rfc = $requestData['rfc'];
        $client->phone = $requestData['phone'];
        $client->email = $requestData['email'];
        $client->status = $requestData['status'];
        $client->save();

        $content = [
            'status' => true,
            'message' => 'Cliente actualizado con éxito.',
            'data' => $client
        ];
        return response()->json($content, 201);
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $client = Client::whereId($id)->first();
        if (is_null($client)) {
            $content = [
                'status' => false,
                'message' => 'Cliente no encontrado.'
            ];
            return response()->json($content, 404);
        }

        $client->delete();
        $content = [
            'status' => true,
            'message' => 'Cliente borrado exitosamente.',
            'data' => $client
        ];
        return response()->json($content, 200);
    }
}
