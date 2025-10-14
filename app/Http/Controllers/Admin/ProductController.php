<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('sort_order')->orderBy('name')->paginate(20);
        $categories = Product::distinct()->pluck('category')->filter();
        
        return view('admin.products', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Product::distinct()->pluck('category')->filter();
        $gameTypes = Product::distinct()->pluck('game_type')->filter();
        return view('admin.product-form', compact('categories', 'gameTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'game_type' => 'nullable|string|max:100',
            'game_type_select' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'image_url' => 'nullable|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'game_type' => $request->game_type,
            'price' => $request->price,
            'tax_rate' => $request->tax_rate,
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $request->sort_order ?? 0,
            'image_url' => $request->image_url,
        ];

        // Determine game_type from select or custom input
        $gameType = $request->game_type_select ?: $request->game_type;
        
        // Validate that at least one game_type is provided
        if (empty($gameType)) {
            return back()->withErrors(['game_type' => 'Game type is required.'])->withInput();
        }
        
        $data['game_type'] = $gameType;

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/products'), $imageName);
            $data['image'] = 'images/products/' . $imageName;
        }

        Product::create($data);

        return redirect()->route('admin.products')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Product::distinct()->pluck('category')->filter();
        $gameTypes = Product::distinct()->pluck('game_type')->filter();
        return view('admin.product-form', compact('product', 'categories', 'gameTypes'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'game_type' => 'nullable|string|max:100',
            'game_type_select' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'image_url' => 'nullable|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'game_type' => $request->game_type,
            'price' => $request->price,
            'tax_rate' => $request->tax_rate,
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $request->sort_order ?? 0,
            'image_url' => $request->image_url,
        ];

        // Determine game_type from select or custom input
        $gameType = $request->game_type_select ?: $request->game_type;
        
        // Validate that at least one game_type is provided
        if (empty($gameType)) {
            return back()->withErrors(['game_type' => 'Game type is required.'])->withInput();
        }
        
        $data['game_type'] = $gameType;

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }
            
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/products'), $imageName);
            $data['image'] = 'images/products/' . $imageName;
        }

        $product->update($data);

        return redirect()->route('admin.products')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products')->with('success', 'Product deleted successfully.');
    }

    public function toggleStatus(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);
        
        $status = $product->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "Product {$status} successfully.");
    }
}
