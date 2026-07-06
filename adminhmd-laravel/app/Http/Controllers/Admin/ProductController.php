<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Attachment;
use App\Models\Category;


class ProductController extends Controller
{
    //
    public function index(Request $request)
    {
        $search      = $request->get('search');
        $category_id = $request->get('category_id');
 
        $products = Product::with(['category', 'primaryImage'])
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('product_name', 'like', "%{$search}%")
                        ->orWhere('_description', 'like', "%{$search}%");
                });
            })
            ->when($category_id, function ($q) use ($category_id) {
                $q->where('category_id', $category_id);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();
 
        $categories = Category::orderBy('category_name')->get();
 
        return view('admin.products.index', compact('products', 'categories', 'search', 'category_id'));
    }
      
    //post product
    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|max:100',
            'price'  => 'required|numeric|min:1',
        ]);
        // $attachmentsCount = $product->productattachments()->count();
        // if (($request->boolean('ishot') || $request->boolean('isactive')) && $attachmentsCount < 5) {
        //     return back()->with(
        //         'info',
        //         'A product must have at least 5 images before it can be marked as Hot or Active.'
        //         );
        //     }
            
        Product::create([
            'product_name' => $request->product_name,
            '_description' => $request->_description,
            'price'        => $request->price,
            'category_id'  => $request->category_id ?: null,
            'ishot'        => 0,
            'isactive'     => 0,
            'created_by'   => auth()->id(),
        ]);
 
        return back()->with('success', 'Product added successfully!');
    }
     
    //update product
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'product_name' => 'required|max:100',
            'price'  => 'required|numeric|min:0',
        ]);

        $attachmentsCount = $product->productattachments()->count();
        
        if (($request->boolean('ishot') || $request->boolean('isactive')) && $attachmentsCount < 5) {
            return back()->with(
                'info',
                'A product must have at least 5 images before it can be marked as Hot or Active.'
            );
        }
 
        $product->update([
            'product_name' => $request->product_name,
            '_description' => $request->_description,
            'price'      => $request->price,
            'category_id'  => $request->category_id ?: null,
            'ishot' => $request->boolean('ishot'),
            'isactive'   => $request->boolean('isactive'),
            'updated_by' => auth()->id(),
        ]);
 
        return back()->with('success', 'Product updated successfully!');
    }

    //delete product
    public function destroy(Product $product)
    {
        if ($product->orderItems()->count() > 0) {
            return back()->with('error', 'Cannot delete — product exists in orders!');
        }
 
        // Delete physical image files
        foreach ($product->productattachments as $att) {
            $path = public_path('uploads/' . $att->file_name);
            if (file_exists($path)) unlink($path);
        }
 
        $product->delete();
        return back()->with('success', 'Product deleted successfully!');
    }

     // ── Attachments ────
    public function uploadAttachment(Request $request, Product $product)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ]);
 
        $file     = $request->file('image');
        $fileSize = $file->getSize();

        $fileType = $file->getClientMimeType();

        $filename = 'product_' . $product->id . '_' . time() . '_' . uniqid() . '.' . $file->extension();
        $file->move(public_path('uploads'), $filename);
 
        if ($request->boolean('is_primary')) {
            $product->productattachments()->update(['is_primary' => 0]);
        }
 
        Attachment::create([
            'product_id' => $product->id,
            'file_name'  => $filename,
            'file_type'  => $fileType,
            'file_size'  => $fileSize,
            'file_url'   => 'uploads/' . $filename,
            'is_primary' => $request->boolean('is_primary') ? 1 : 0,
            'created_by' => auth()->id(),
            'created_at' => now(),
        ]);
 
        return back()->with('success', 'Image uploaded successfully!');
    }
 
    public function setPrimary(Product $product, Attachment $attachment)
    {
        $product->productattachments()->update(['is_primary' => 0]);
        $attachment->update(['is_primary' => 1]);
        return back()->with('success', 'Primary image updated!');
    }
 
    public function deleteAttachment(Attachment $attachment)
    {
        $path = public_path('uploads/' . $attachment->file_name);
        if (file_exists($path)) unlink($path);
        $attachment->delete();
        return back()->with('success', 'Image deleted successfully!');
    }

}
