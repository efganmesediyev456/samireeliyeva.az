<?php

namespace App\Http\Controllers\Api;

use App\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\CatalogCollection;
use App\Http\Resources\CatalogResource;
use App\Models\Catalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CatalogApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Catalog::query();

        // Filter by status if provided
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Sort by order if requested
        if ($request->has('sort_by_order') && $request->sort_by_order) {
            $query->orderBy('order', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Paginate the results
        $perPage = $request->per_page ?? 15;
        $catalogs = $query->paginate($perPage);

        return CatalogResource::collection($catalogs);
    }
}
