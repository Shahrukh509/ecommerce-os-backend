<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Service\AuthProcess;
use Log;
use Exception;
use Auth;


class AuthenticationAPIController extends Controller
{
    public function Register(Request $request)
    {
      try{
        
        $obj = new AuthProcess($request);
        $user = $obj->RegisteringUser();
       
        return $this::sendResponse($user,'User registered successfully',200);
        
      }catch(Exception $e){
        Log::debug($e->getMessage());
        return $this::sendError($e->getMessage(),$e->getCode());

      }
    }

    

    public function login(Request $request){

        try{
            $login =new AuthProcess($request);
            $user = $login->LoginProcess();
            return $this::sendResponse($user,'User logged in',200);
        }catch(Exception $e){
            Log::debug($e->getMessage());
            return $this::sendError($e->getMessage(),$e->getCode());
        }

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
 
     /**
      * Store a newly created resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @return \Illumipnate\Http\Response
      */

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function SendOtp(Request $request)
    {
        try{

           $obj = new AuthProcess($request);
          $otp= $obj->sendOtp();
           return $this::sendResponse('your otp is '.$otp,'Link has been sent');

        }catch(Exception $e){
           
            return $this::sendError($e->getMessage(),$e->getCode());
        }
      
    }

    public function CheckOtp(Request $request){
      
        try{

            $obj =new AuthProcess($request);
            $obj->verifyingOtp();
            return $this::sendResponse(true,'provided otp is correct');
         


        }catch(Exception $e){
            Log::debug($e->getMessage());
            return $this::sendError($e->getMessage(),$e->getCode());

        }

        
    }

    public function passwordReset(Request $request){

        try{
            

            $obj =new AuthProcess($request);
            $obj->verifyingOtp();
            $obj->changePassword();
            return $this::sendResponse(true,'Password has been changed successfully!');
         


        }catch(Exception $e){
            Log::debug($e->getMessage());
            return $this::sendError($e->getMessage(),$e->getCode());

        }


    }public function otpForUserApproval(Request $request){


        try{
            

            $obj =new AuthProcess($request);
            $obj->verifyingOtp();
            $obj->changeUserApproval();
            return $this::sendResponse(true,'User has been approved successfully!');
         


        }catch(Exception $e){
            Log::debug($e->getMessage());
            return $this::sendError($e->getMessage(),$e->getCode());

        }


    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
      try{
        // dd(Auth::user()->currentAccessToken()->delete());

        $logout = Auth::user()->currentAccessToken()->delete();
        if($logout) return $this::sendResponse('','User has been logout');
        throw new Exception('Unable to logout');

      }catch(Exception $e){
        return $this::sendError($e->getMessage());

      }
    }
}
