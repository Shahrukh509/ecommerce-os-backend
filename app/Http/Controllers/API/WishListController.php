<?php

namespace App\Http\Controllers\API;

use App\Models\WishList;
use Auth;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Log;

class WishListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate the incoming request data
            $validatedData = $request->validate(WishList::$addRules);
    
            // Determine the column based on user authentication
            $column = Auth::check() ? 'user_id' : 'ip_address';
            // Perform the create or update operation
            $wishList = WishList::updateOrCreate(
                ['product_id' => $validatedData['product_id']],
                [
                    'product_id' => $validatedData['product_id'],
                    $column => Auth::check() ? Auth::id() : $request->ip()
                ]
            );
            $userWishList = $this->getUserWishList();
    
            // Send a success response
            return $this->sendResponse($userWishList, 'Item Added To Wish List');
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            Log::error('Validation Error in ' . __METHOD__);
            Log::error('Validation message: ' . $e->getMessage());
            Log::debug('Validation Errors: ' . json_encode($e->errors()));
            
            // Return validation errors with a 422 status code
            return $this->sendError('Validation error', 422, $e->errors());
    
        } catch (Exception $e) {
            // Log detailed error information
            Log::error('Error in ' . __METHOD__);
            Log::error('Exception message: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
    
            // Send a generic error response
            return $this->sendError('An error occurred while adding the item to the wish list.', 500);
        }
    }
    


    /**
     * Display the specified resource.
     */
    public function show(WishList $wishList)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WishList $wishList)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WishList $wishList)
    {
        //
    }

    public function userWishList()
    {
        try {
            $userWishList = $this->getUserWishList();
            return $this->sendResponse($userWishList, 'Wish List Retrieved Successfully');
        } catch (Exception $e) {
            Log::error('Error in ' . __METHOD__, [
                'message' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
            ]);
    
            return $this->sendError('An error occurred while retrieving the wish list.', 500);
        }
    }

    private function getUserWishList(){
        $column = Auth::check() ? 'user_id' : 'ip_address';
        $targetValue = Auth::check() ? Auth::id() : request()->ip();
        $userWishList = WishList::where($column, $targetValue)->get();
        return $userWishList;
    }
    
}
