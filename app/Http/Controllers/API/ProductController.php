<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::whereNull('deleted_at')->with([
            'category_product', 'product_image'
        ])->get();

        return response()->json([
            'success' => true,
            'data' => $products,
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
            'name'          => 'required|unique:categories,name',
            'description'   => 'required',
            'category_ids'  => 'required',
            'enable'        => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success'   => false,
                'data'      => $validator->errors()
            ], 400);
        }

        $category_ids = json_decode($request->category_ids);
        $valid_category_ids = [];

        foreach ($category_ids as $id) {
            $exists = DB::table('categories')
                ->whereNull('deleted_at')
                ->where('id', $id)
                ->exists();
            
            if ($exists) {
                $valid_category_ids[] = $id;
            }
        }

        if (!$valid_category_ids) {
            return response()->json([
                'success'   => false,
                'data'      => 'No valid category found'
            ], 400);
        }

        $image_ids = json_decode($request->image_ids);
        $valid_image_ids = [];

        if ($image_ids) {
            foreach ($image_ids as $id) {
                $exists = DB::table('images')
                    ->whereNull('deleted_at')
                    ->where('id', $id)
                    ->exists();
                
                if ($exists) {
                    $valid_image_ids[] = $id;
                }
            }
        }

        DB::beginTransaction();

        try {
            $product = new Product;

            $product->name          = $request->name;
            $product->description   = $request->description;
            $product->enable        = $request->enable;
            $product->save();

            $category_insert = [];
            foreach ($valid_category_ids as $id) {
                $temp = [
                    'product_id' => $product->id,
                    'category_id' => $id
                ];
                $category_insert[] = $temp;
            }

            DB::table('category_products')->insert($category_insert);

            if ($valid_image_ids) {
                $image_insert = [];
                foreach ($valid_image_ids as $id) {
                    $temp = [
                        'product_id' => $product->id,
                        'image_id' => $id
                    ];
                    $image_insert[] = $temp;
                }

                DB::table('product_images')->insert($image_insert);
            }

            $response = [
                'success'   => true,
                'data'      => $product
            ];
        } catch(Exception $e) {
            DB::rollback();
            Log::error($request->route()->getName()." : ".$e->getMessage());

            $response = ['success'  => false, 'data' => 'Failed to create product'];
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
        $product = Product::where('id', $id)
            ->with(['category_product', 'product_image'])
            ->first();

        if ($product) {
            return response()->json([
                'success'   => true,
                'data'      => $product,
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
            'name'          => 'required|unique:categories,name',
            'description'   => 'required',
            'category_ids'   => 'required',
            'enable'        => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success'   => false,
                'data'      => $validator->errors()
            ], 400);
        }

        $category_ids = json_decode($request->category_ids);
        $valid_category_ids = [];

        foreach ($category_ids as $category_id) {
            $exists = DB::table('categories')
                ->whereNull('deleted_at')
                ->where('id', $category_id)
                ->exists();
            
            if ($exists) {
                $valid_category_ids[] = $category_id;
            }
        }

        if (!$valid_category_ids) {
            return response()->json([
                'success'   => false,
                'data'      => 'No valid category found'
            ], 400);
        }

        $image_ids = json_decode($request->image_ids);
        $valid_image_ids = [];

        if ($image_ids) {
            foreach ($image_ids as $image_id) {
                $exists = DB::table('images')
                    ->whereNull('deleted_at')
                    ->where('id', $image_id)
                    ->exists();
                
                if ($exists) {
                    $valid_image_ids[] = $image_id;
                }
            }
        }

        DB::beginTransaction();

        try {
            $product = Product::find($id);

            if (!$product) {
                $response = ['success'  => false, 'data' => 'Product not found'];
                goto not_found;
            }

            $product->name          = $request->name;
            $product->description   = $request->description;
            $product->enable        = $request->enable;
            $product->save();

            $category_insert = [];
            foreach ($valid_category_ids as $id) {
                $temp = [
                    'product_id' => $product->id,
                    'category_id' => $id
                ];
                $category_insert[] = $temp;
            }

            DB::table('category_products')->insert($category_insert);

            if ($valid_image_ids) {
                $image_insert = [];
                foreach ($valid_image_ids as $id) {
                    $temp = [
                        'product_id' => $product->id,
                        'image_id' => $id
                    ];
                    $image_insert[] = $temp;
                }

                DB::table('product_images')->insert($image_insert);
            }

            $response = [
                'success'   => true,
                'data'      => $product
            ];
        } catch(Exception $e) {
            DB::rollback();
            Log::error($request->route()->getName()." : ".$e->getMessage());

            $response = ['success'  => false, 'data' => 'Failed to update product'];
        }

        DB::commit();

        not_found:

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
            $product = Product::findOrFail($id);
            $product->delete();

            $response = ['success'  => false, 'data' => 'Product deleted successfully'];
        } catch(Exception $e) {
            DB::rollback();
            Log::error(request()->route()->getName()." : ".$e->getMessage());

            $response = ['success'  => false, 'data' => 'Failed to delete product'];
        }

        DB::commit();
        
        dependent:

        return response()->json($response);
    }
}
