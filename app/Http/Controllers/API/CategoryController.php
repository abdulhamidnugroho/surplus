<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = DB::table('categories')->whereNull('deleted_at')->get();
        
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
        $validator = Validator::make($request->all(), [
            'name'      => 'required|unique:categories,name',
            'enable'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success'   => false,
                'data'      => $validator->errors()
            ], 400);
        }

        DB::beginTransaction();

        try {
            $category = new Category;

            $category->name     = $request->name;
            $category->enable   = $request->enable;
            $category->save();

            $response = [
                'success'   => true,
                'data'      => $category
            ];
        } catch(Exception $e) {
            DB::rollback();
            Log::error($request->route()->getName()." : ".$e->getMessage());

            $response = ['success'  => false, 'data' => 'Failed to create category'];
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
        $category = Category::where('id', $id)->with('category_product')->first();

        if ($category) {
            return response()->json([
                'success'   => true,
                'data'      => $category,
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
            'name'      => 'nullable',
            'enable'    => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success'   => false,
                'data'      => $validator->errors()
            ], 400);
        }

        $category = Category::findOrFail($id);

        DB::beginTransaction();

        try {
            $category->name   = $request->name;
            $category->enable  = $request->enable;
            $category->save();

            $response = [
                'success'  => true,
                'data' => $category
            ];
        } catch(Exception $e) {
            DB::rollback();
            Log::error($request->route()->getName()." : ".$e->getMessage());

            $response = ['success'  => true, 'data' => 'Failed to update category'];
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
            $category = Category::findOrFail($id);
            if ($category->category_product()->exists()) {
                $response = ['success'  => true, 'data' => 'This category has product'];
                goto dependent;
            }
            $category->delete();

            $response = ['success'  => true, 'data' => 'Category deleted successfully'];
        } catch(Exception $e) {
            \DB::rollback();
            \Log::error(request()->route()->getName()." : ".$e->getMessage());

            $response = ['success'  => true, 'data' => 'Failed to delete category'];
        }

        DB::commit();

        dependent:

        return response()->json($response);
    }
}
