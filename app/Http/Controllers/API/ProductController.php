<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Retrieve all products.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $products = Product::all();

        return response()->json([
            'message' => 'Products retrieved successfully',
            'data' => $products,
        ], 200);
    }

    /**
     * Store a new product with image upload.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeee(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'qty_type' => 'required|string|max:50',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|integer|exists:categories,id',
            // 'sub_category' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('assets/img', 'public');
            $validated['image'] = $imagePath; 
        }

        $product = Product::create($validated);

        return response()->json([
            'message' => 'Product created successfully',
            'data' => $product,
        ], 201);
    }

    public function store(Request $request)
{
    $data=$request->json()->all();
   
    $adminId= Auth::user()->id;
    Product::create([
        'admin_id'=>$adminId,'category_id'=>$data['category_id'],'name'=>$data['name'],'quantity'=>$data['quantity'],'qty_type'=>$data['qty_type'],'description'=>$data['description'],'price'=>$data['price'],'image'=>$data['image']
    ]);
    return response()->json([
        'message' => 'Product created successfully',
        'data' => $data,
    ], 201);
}

public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Product retrieved successfully',
            'data' => $product,
        ], 200);
    }


   public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'qty_type' => 'required|string|max:50',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|integer|exists:categories,id',
            'sub_category' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('assets/img', 'public');
            $validated['image'] = $imagePath;
        }

        $product->update($validated);

        return response()->json([
            'message' => 'Product updated successfully',
            'data' => $product,
        ], 200);
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found',
            ], 404);
        }

        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully',
        ], 200);
    }

public function getProducts(Request $request)
{
    $user = Auth::user();

    if (!$user) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

     $products = Product::where('admin_id', $user->id)->get();

    return response()->json([
        'message' => 'Products are',
        'products' => $products
    ], 200);
}
public function updateProduct(Request $request,$id)
{
    $data=$request->json()->all();
        $adminId= Auth::user()->id;
        Product::where('id',$id)->where('admin_id', $adminId)->update([
          'admin_id'=>$adminId,'category_id'=>$data['category_id'],'name'=>$data['name'],'quantity'=>$data['quantity'],'qty_type'=>$data['qty_type'],'description'=>$data['description'],'price'=>$data['price'],'image'=>$data['image']
    ]);
         return response()->json([
            'message' => 'Product updated successfully',
            'data' => $data,
        ], 201);
}

public function deleteProducts(Request $request,$id)
{
    $data = $request->json()->all();
    $userId = Auth::id(); 
    $product = Product::where('id', $id)->where('admin_id', $userId)->first();
    if (!$product) {
        return response()->json(['message' => 'Product not found or unauthorized'], 404);
    }
   $product->delete();

    return response()->json(['message' => 'Product deleted successfully']);
}

}
