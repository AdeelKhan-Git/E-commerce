<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    //index page
    public function index(Request $request){
        $search =  $request->get('search');

        $categories = Category::when($search, function ($q) use ($search) {
                            $q->where('category_name', 'like', "%$search%");
                        })
                        ->latest()
                        ->paginate(10)
                        ->withQueryString();

        return view('admin.categories.index',compact('categories','search'));
    }

    // Post Category
    public function store(Request $request){
        $request->validate([
            'category_name' => 'required|max:50|unique:categories,category_name',
        ]);

        Category::create([
            'category_name' => $request->category_name,
            '_description' => $request->_description,
            'category_image' => $request->category_image,
            'created_by' => auth()->id(),
        ]);

        return back()->with('success','Category added successfully!');
    }


    //update category
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'category_name' => 'required|max:50|unique:categories,category_name,' . $category->id,
        ]);
 
        $category->update([
            'category_name' => $request->category_name,
            '_description'  => $request->_description,
            'updated_by'    => auth()->id(),
        ]);
 
        return back()->with('success', 'Category updated successfully!');
    }
    

    //delete category
    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Cannot delete — products are assigned to this category!');
        }
 
        $category->delete();
        return back()->with('success', 'Category deleted successfully!');
    }
}
