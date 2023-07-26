<?php

namespace App\Http\Controllers\inventory;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class ItemController extends Controller
{

    public function index(): \Illuminate\Http\JsonResponse
    {
        $items = Item::where('id', '!=', 1)->get()->toArray();
        $itemArray = [];
        foreach ($items as $item) {
            $date = Carbon::now();
            $lastActivity = $date->createFromTimeStamp(strtotime($item['updated_at']))->diffForHumans();
            $lastActivity = ['updated_at_format' => $lastActivity];
            $itemArray[] = array_merge($item, (array)$lastActivity);
        }
        $content = [
            'status' => true,
            'message' => 'Lista de artículos.',
            'data' => $itemArray
        ];
        return response()->json($content, 200);
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Item $item): \Illuminate\Http\JsonResponse
    {
        $content = [
            'success' => true,
            'message' => 'Artículo encontrado.',
            'data' => $item
        ];
        return response()->json($content, 200);
    }

    public function update(Request $request, Item $item)
    {
        //
    }

    public function destroy(Item $item)
    {
        //
    }
}
