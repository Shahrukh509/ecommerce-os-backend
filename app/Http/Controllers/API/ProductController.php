<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Log;
use Exception;
use DB;
use App\Services\CrudService;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            Log::info('Initiate ' . __FUNCTION__ . '() from class ' . __CLASS__);

            $perPage = request('perPage') ?? 100;
            $orderBy = request('orderBy') ?? 'desc';
            $page = request('page') ?? 1;
            $forgetKey = request('forgetKey');

            // Generate cache key with pagination information
            $cacheKey = 'products_page_' . $page . '_perPage_' . $perPage . '_orderBy_' . $orderBy;
            if ($forgetKey) Cache::store('redis')->forget($cacheKey);
            // Check if the data is cached
            $cachedResponse = Cache::store('redis')->get($cacheKey);
            if (!empty($cachedResponse)) return $cachedResponse; // Return the full cached response, not just the data
            // Fetch products from the database
            $products = Product::orderBy('id', $orderBy)->paginate($perPage);
            $responseData = [
                'data' => $products->makeHidden('media'), // Products data
                'total' => $products->total(),            // Total number of records
            ];
            // Prepare the response
            $response = $this->sendResponse($responseData, 'Products Retrieved Successfully');
            // Cache the entire response for 24 hours
            Cache::store('redis')->put($cacheKey, $response, 1440); // Cache for 24 hours

            // Return the response
            return $response;


        } catch (Exception $e) {
            Log::error('Error found in ' . __FUNCTION__ . '() in class ' . __CLASS__);
            Log::error($e->getMessage());
            Log::debug('===============================================================');
            return $this->sendError($e->getMessage());
        }
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            Log::info('Initiate ' . __FUNCTION__ . '() from class ' . __CLASS__);
            $data = Product::FindOrFail($id)->makeHidden('media');
            return $this->sendResponse($data, 'Product Retrieved Successfully');

        } catch (Exception $e) {
            Log::error('Error found in ' . __FUNCTION__ . '() in class ' . __CLASS__);
            Log::error($e->getMessage());
            Log::debug('===============================================================');
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
