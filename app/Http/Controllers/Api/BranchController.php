<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\JsonResponse;

class BranchController extends Controller
{
    public function index(): JsonResponse
    {
        $branches = Branch::query()
            ->latest()
            ->get()
            ->map(fn (Branch $branch) => [
                'id' => $branch->id,
                'name' => $branch->name,
                'address' => $branch->address,
                'open_from' => $branch->open_from_formatted,
                'open_to' => $branch->open_to_formatted,
            ]);

        return response()->json([
            'data' => $branches,
        ]);
    }
}
