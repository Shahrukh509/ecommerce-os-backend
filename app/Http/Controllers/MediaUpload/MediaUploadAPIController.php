<?php

namespace App\Http\Controllers\MediaUpload;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Eloquent\MediaUploadRepository;
use Exception;
use Helper;
use Log;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Http\Controllers\classes\UploadProcess;

class MediaUploadAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{

            if($request->hasFile('image')){
                

                $image = UploadProcess::getInstance(null,$request->image);
                $image = $image->saveTemporaryMedia();
                return Helper::sendResponse($image,'Images upploaded successfully!');
            }else throw new Exception('No file selected ',400);

        }catch(\Exception $e){
            Log::debug($e->getMessage());
        return Helper::sendError($e->getMessage());
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{

            $image = UploadProcess::getInstance(null,$id);
            $delete = $image->deleteTemporaryMedia();
            return Helper::sendResponse($delete,'Image deleted successfully!');

        }
        catch(\Exception $e){
            Log::debug($e->getMessage());
        return Helper::sendError($e->getMessage());
        }
        
}
}
