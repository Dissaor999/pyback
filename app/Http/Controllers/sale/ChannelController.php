<?php

namespace App\Http\Controllers\sale;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use Illuminate\Http\Request;
use Validator;

class ChannelController extends Controller
{

    public function index(): \Illuminate\Http\JsonResponse
    {
        $channels = Channel::all();
        $content = [
            'status' => true,
            'message' => 'Lista de canales de ventas.',
            'data' => $channels
        ];
        return response()->json($content, 200);
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'name' => 'required|string|min:3|max:50|unique:channels',
            'access' => 'required|string|min:3|max:50',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            $content = [
                'status' => false,
                'message' => 'Entradas no válidas.',
                'error' => $validator->errors()
            ];
            return response()->json($content, 400);
        }

        $channel = Channel::create($requestData);
        $content = [
            'status' => true,
            'message' => 'Canal de venta creado con éxito.',
            'data' => $channel
        ];
        return response()->json($content, 201);
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $channel = Channel::whereId($id)->first();
        if (is_null($channel)) {
            $content = [
                'status' => false,
                'message' => 'Canal de venta no encontrado.'
            ];
            return response()->json($content, 404);
        }
        $content = [
            'success' => true,
            'message' => 'Canal de venta encontrado.',
            'data' => $channel
        ];
        return response()->json($content, 200);
    }

    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $channel = Channel::whereId($id)->first();
        if (is_null($channel)) {
            $content = [
                'status' => false,
                'message' => 'Canal de venta no encontrado.'
            ];
            return response()->json($content, 404);
        }

        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'name' => 'required|string|min:3|max:50',
            'access' => 'required|string|min:3|max:50',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            $content = [
                'status' => false,
                'message' => 'Entradas no válidas.',
                'error' => $validator->errors()
            ];
            return response()->json($content, 400);
        }

        $channel->name = $requestData['name'];
        $channel->access = $requestData['access'];
        $channel->status = $requestData['status'];
        $channel->save();

        $content = [
            'status' => true,
            'message' => 'Canal de venta actualizado con éxito.',
            'data' => $channel
        ];
        return response()->json($content, 201);
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $channel =  Channel::whereId($id)->first();
        if (is_null($channel)) {
            $content = [
                'status' => false,
                'message' => 'Canal de venta no encontrado.'
            ];
            return response()->json($content, 404);
        }

        $channel->delete();
        $content = [
            'status' => true,
            'message' => 'Canal de venta borrado exitosamente.',
            'data' => $channel
        ];
        return response()->json($content, 200);
    }
}
