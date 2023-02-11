<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = DB::table('images')->whereNull('deleted_at')->get();
        
        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $request->all();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'file' => 'required|mimes:jpeg,jpg,png|max:2048',
            'enable' => 'required',            
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success'   => false,
                'data'      => $validator->errors()
            ], 400);
        }

        DB::beginTransaction();

        try {
            $image = new Image;

            $dir = 'images/product_images/';
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }

            if ($request->hasFile('file')) {
                $file     = $request->file('file');
                $file_name = Carbon::now()->toDateString() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($dir, $file_name);
                $image->file = $dir.$file_name;
            } else {
                $image->file = 'images/product_images/surplus.png';
            }

            $image->name    = $request->name;
            $image->enable  = $request->enable;
            $image->save();

            $response = [
                'success'   => true,
                'data'      => $image
            ];
        } catch(\Exception $e) {
            DB::rollback();
            Log::error($request->route()->getName()." : ".$e->getMessage());

            $response = ['success'  => false, 'data' => 'Failed to create image'];
        }

        DB::commit();

        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $image = Image::where('id', $id)->first();

        if ($image) {
            return response()->json([
                'success'   => true,
                'data'      => $image,
            ], 200);
        }

        return response()->json([
            'success'   => false,
            'data'      => 'Data not found',
        ], 404);
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
        $validator = Validator::make($request->all(), [
            'file' => 'nullable|mimes:jpeg,jpg,png|max:2048',          
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success'   => false,
                'data'      => $validator->errors()
            ], 400);
        }

        $image = Image::findOrFail($id);
        
        DB::beginTransaction();

        try {
            $dir = 'images/product_images/';
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }

            if ($request->hasFile('file')) {
                $file     = $request->file('file');
                $file_name = Carbon::now()->toDateString() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
                if (file_exists($image->file) && $image->file != 'images/product_images/surplus.png') {
                    unlink($image->file);
                }
                $file->move($dir, $file_name);
                $image->file = $dir . $file_name;
            }

            $image->name    = $request->name;
            $image->enable  = $request->enable;
            $image->save();

            $response = [
                'success'   => true,
                'data'      => $image
            ];
        } catch(\Exception $e) {
            DB::rollback();
            Log::error($request->route()->getName()." : ".$e->getMessage());

            $response = ['success'  => false, 'data' => 'Failed to update image'];
        }

        DB::commit();

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $image = Image::findOrFail($id);
            
            if (file_exists($image->image)) {
                if ($image->image != 'images/product_images/surplus.png') {
                    unlink($image->image);
                }
            }
            $image->delete();

            $response = ['success'  => false, 'data' => 'Image deleted successfully'];
        } catch(\Exception $e) {
            DB::rollback();
            Log::error(request()->route()->getName()." : ".$e->getMessage());

            $response = ['success'  => false, 'data' => 'Failed to delete image'];
        }

        DB::commit();

        return response()->json($response);
    }
}
