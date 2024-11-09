<?php

namespace App\Http\Controllers\API;

use App\Models\Cart;
use Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Log;
use Exception;
use DB;
use Illuminate\Support\Facades\Cache;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        try {
            Log::info('Initiate ' . __FUNCTION__ . '() from class ' . __CLASS__);
            $validatedData = $request->validate(Cart::$addRules);
            if (Auth::id()) {
                $validatedData['user_id'] = Auth::id();
            } else {
                $validatedData['ip_address'] = $request->ip();
            }
            $cartItem = Cart::where('product_id', $validatedData['product_id'])
                ->where(function ($query) {
                    if (Auth::check()) {
                        $query->where('user_id', Auth::id());
                    } else {
                        $query->where('ip_address', request()->ip());
                    }
                })->first();

            if ($cartItem) {
                // Update the quantity if it already exists
                $cartItem->quantity += $validatedData['quantity']??1;
                $cartItem->save();
            } else {
                if(empty($validatedData['quantity'])) $validatedData['quantity']=1;
                // Create a new cart item if it does not exist
                $cartItem = Cart::create($validatedData);
            }
            $allCart = Cart::with('products')->where(function ($query) {
                if (Auth::check()) {
                    $query->where('user_id', Auth::id());
                } else {
                    $query->where('ip_address', request()->ip());
                }
            })->get();
            return $this->sendResponse($allCart, 'Cart Added Successfully');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            Log::error('Validation Error in ' . __FUNCTION__ . '() in class ' . __CLASS__);
            Log::error($e->getMessage());
            Log::debug('Validation Errors: ' . json_encode($e->errors()));
            return $this->sendError($e->errors(), 422); // Pass errors in array format

        } catch (Exception $e) {
            // Handle other errors
            Log::error('Error found in ' . __FUNCTION__ . '() in class ' . __CLASS__);
            Log::error($e->getMessage());
            Log::debug('===============================================================');
            return $this->sendError($e->getMessage(), 500); // Pass message directly
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    try {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
        $input = $request->only('quantity');
        $cart = Cart::whereId($id)->update($input);
        if ($cart) {
            $carts = $this->getUserCarts();
            return $this->sendResponse($carts, 'Carts Updated Successfully');
        } else {
            return $this->sendError('No changes made to the cart.', 400);
        }
    } catch (ModelNotFoundException $e) {
        Log::error('Cart not found: ' . $e->getMessage());
        return $this->sendError('Cart not found.', 404);
    } catch (Exception $e) {
        // Handle other errors
        Log::error('Error found in ' . __FUNCTION__ . '() in class ' . __CLASS__);
        Log::error($e->getMessage());
        Log::debug('===============================================================');
        return $this->sendError('An error occurred while updating the cart.', 500); // Generic error message
    }
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        dd('at delete');
    }

    public function userCart(){
        try{
            $column= Auth::id()? 'user_id':'ip_address';
            $operation= Auth::id()? Auth::id():request()->ip();
            $cart = Cart::with('products')->where("$column","$operation")->get();
            return $this->sendResponse($cart,"Cart Retrieved Successfully");
        }catch (Exception $e) {
            // Handle other errors
            Log::error('Error found in ' . __FUNCTION__ . '() in class ' . __CLASS__);
            Log::error($e->getMessage());
            Log::debug('===============================================================');
            return $this->sendError($e->getMessage(), 500); // Pass message directly
        }
    }

    private function getUserCarts(){
        $column = Auth::check() ? 'user_id' : 'ip_address';
        $targetValue = Auth::check() ? Auth::id() : request()->ip();
        $userCartList = Cart::with('products')->where($column, $targetValue)->get();
        return $userCartList;
    }
}
