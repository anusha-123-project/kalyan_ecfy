<?php

namespace App\Http\Controllers\Api;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    
    public function index()
    {
        // Fake data
        $categories = [
            ['id' => 1, 'name' => 'Electronics', 'description' => 'Electronic gadgets'],
            ['id' => 2, 'name' => 'Books', 'description' => 'Various books and literature'],
        ];

        return response()->json(['categories' => $categories]);
    }

    public function store1(Request $request)
    {
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);
          $newCategory = [
            'id' => rand(3, 100), 
            'name' => $request->name,
            'description' => $request->description,
        ];

        return response()->json(['message' => 'Category created successfully', 'category' => $newCategory], 201);
    }
  

    public function store(Request $request)
    {
        $data=$request->json()->all();
        $adminId= Auth::user()->id;
        Category::create([
        'admin_id'=>$adminId,'name'=>$data['name'],'image'=>$data['image'],'parent_id'=>$data['parent_id'],'position'=>$data['position'],'status'=>$data['status'],'priority'=>$data['priority'],'module_id'=>$data['module_id'],'slug'=>$data['slug'],'featured'=>$data['featured']   
     ]);
        return response()->json([
        'message' => 'category created successfully',
        'data' => $data,
    ], 201);
    }
    public function update(Request $request,$id)
    {
        $data=$request->json()->all();
        $adminId= Auth::user()->id;
        Category::where('id',$id)->where('admin_id', $adminId)->update([
            'admin_id'=>$adminId,'name'=>$data['name'],'image'=>$data['image'],'parent_id'=>$data['parent_id'],'position'=>$data['position'],'status'=>$data['status'],'priority'=>$data['priority'],'module_id'=>$data['module_id'],'slug'=>$data['slug'],'featured'=>$data['featured']   
         ]);
         return response()->json([
            'message' => 'category updated successfully',
            'data' => $data,
        ], 201);
    }
    public function delete(Request $request, $id)
    {
        $data=$request->json()->all();
        $adminId= Auth::user()->id;
        Category::where ('id',$id)->where('admin_id', $adminId)->delete([
           'admin_id'=>$adminId,'name'=>$data['name'],'image'=>$data['image'],'parent_id'=>$data['parent_id'],'position'=>$data['position'],'status'=>$data['status'],'priority'=>$data['priority'],'module_id'=>$data['module_id'],'slug'=>$data['slug'],'featured'=>$data['featured']   
         ]);
         return response()->json([
            'message' => 'category Deleted successfully',
            'data' => $data,
        ], 201);
    }
}
