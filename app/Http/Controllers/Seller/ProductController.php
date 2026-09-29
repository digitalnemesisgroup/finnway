<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // Step 1: Show add product wizard
    public function create()
    {
        $categories = Category::where('is_active', true)->whereNull('parent_id')->with('children')->get();
        return view('seller.products.create', compact('categories'));
    }

    public function getCategoryFields(Request $request)
    {
        $categoryId = $request->category_id;
        $conditionType = $request->condition_type;
        $fields = \App\Models\CategoryField::where('category_id', $categoryId)
            ->whereIn('condition_type', ['both', $conditionType])
            ->get();
        return response()->json(['fields' => $fields]);
    }

    // Save product (from wizard)
    public function store(Request $request)
    {
        $request->validate([
            'seller_type'   => 'nullable|string',
            'condition_type'=> 'required|in:new,old',
            'name'          => 'required|string|max:200',
            'category_id'   => 'required|exists:categories,id',
            'selling_price' => 'required|numeric|min:1',
            'description'   => 'nullable|string',
            'images'        => 'required|array|min:1',
            'images.*'      => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        // Dynamic Field Validation
        $categoryFields = \App\Models\CategoryField::where('category_id', $request->category_id)
            ->whereIn('condition_type', ['both', $request->condition_type])
            ->get();
            
        $metaRules = [];
        foreach ($categoryFields as $field) {
            $rule = [];
            if ($field->is_required) $rule[] = 'required';
            else $rule[] = 'nullable';
            
            if ($field->input_type === 'number') {
                $rule[] = 'numeric';
                if ($field->min_val !== null) $rule[] = 'min:' . $field->min_val;
                if ($field->max_val !== null) $rule[] = 'max:' . $field->max_val;
            } else {
                $rule[] = 'string';
                if ($field->min_val !== null) $rule[] = 'min:' . $field->min_val;
                if ($field->max_val !== null) $rule[] = 'max:' . $field->max_val;
            }
            $metaRules['meta.' . $field->name] = implode('|', $rule);
        }
        
        if (!empty($metaRules)) {
            $request->validate($metaRules);
        }

        $user = Auth::user();
        $sellerType = $request->seller_type ?: ($user->seller_type ?: 'Retailer');

        // Make user a seller
        if (!$user->is_seller) {
            $user->update(['is_seller' => true, 'seller_type' => $sellerType]);
        }

        $slug = Str::slug($request->name) . '-' . Str::random(6);

        $originalPrice = $request->original_price ?? $request->selling_price;
        $discount = $originalPrice > 0 ? round(($originalPrice - $request->selling_price) / $originalPrice * 100, 2) : 0;

        $product = Product::create([
            'user_id'           => $user->id,
            'seller_type'       => $sellerType,
            'category_id'       => $request->category_id,
            'name'              => $request->name,
            'slug'              => $slug,
            'description'       => $request->description,
            'condition_type'    => $request->condition_type,
            'condition_label'   => $request->condition_label,
            'selling_price'     => $request->selling_price,
            'original_price'    => $originalPrice,
            'discount_percent'  => $discount,
            'brand'             => $request->brand,
            'model'             => $request->model,
            'delivery_type'     => $request->delivery_type ?? 'courier',
            'delivery_partner_type' => $request->delivery_partner_type ?? 'seller',
            'delivery_days'     => $request->delivery_days ?? 3,
            'pickup_available'  => $request->boolean('pickup_available'),
            'city'              => $request->city ?? $user->city,
            'state'             => $request->state ?? $user->state,
            'pincode'           => $request->pincode ?? $user->pincode,
            'product_age_months'=> $request->product_age_months,
            'bill_available'    => $request->boolean('bill_available'),
            'warranty_available'=> $request->boolean('warranty_available'),
            'warranty_info'     => $request->warranty_info,
            'damage_details'    => $request->damage_details,
            'status'            => 'pending',
            'stock'             => $request->stock ?? 1,
        ]);

        if ($request->has('meta')) {
            foreach ($request->meta as $key => $value) {
                if (!empty($value)) {
                    \App\Models\ProductMeta::create([
                        'product_id' => $product->id,
                        'key' => $key,
                        'value' => is_array($value) ? json_encode($value) : $value,
                    ]);
                }
            }
        }

        // Upload images
        foreach ($request->file('images', []) as $i => $file) {
            $path = $file->store('products', 'public');
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $path,
                'is_primary' => $i === 0,
                'sort_order' => $i,
            ]);
        }

        return redirect()->route('seller.products')->with('success', 'Product submitted for review!');
    }

    // My Products list
    public function index(Request $request)
    {
        $status = $request->status ?? 'all';
        $query = Product::with(['images', 'category'])
            ->where('user_id', Auth::id())
            ->latest();

        if ($status !== 'all') $query->where('status', $status);

        $products = $query->paginate(10);
        return view('seller.products.index', compact('products', 'status'));
    }

    public function edit(Product $product)
    {
        \Illuminate\Support\Facades\Gate::authorize('update', $product);
        $categories = Category::where('is_active', true)->whereNull('parent_id')->with('children')->get();
        $product->load('images');
        return view('seller.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        \Illuminate\Support\Facades\Gate::authorize('update', $product);

        $request->validate([
            'name'          => 'required|string|max:200',
            'selling_price' => 'required|numeric|min:1',
            'stock'         => 'required|integer|min:0',
            'description'   => 'required|string',
            'new_images'    => 'nullable|array',
            'new_images.*'  => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $product->update($request->only([
            'name', 'category_id', 'description', 'condition_type', 'condition_label',
            'selling_price', 'original_price', 'brand', 'delivery_type', 'delivery_days',
            'product_age_months', 'bill_available', 'warranty_available', 'warranty_info',
            'damage_details', 'stock',
        ]));

        // Add new images
        if ($request->hasFile('new_images')) {
            $count = $product->images()->count();
            foreach ($request->file('new_images') as $i => $file) {
                $path = $file->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => $count === 0 && $i === 0,
                    'sort_order' => $count + $i,
                ]);
            }
        }

        return redirect()->route('seller.products')->with('success', 'Product updated!');
    }

    public function destroy(Product $product)
    {
        \Illuminate\Support\Facades\Gate::authorize('delete', $product);
        $product->update(['status' => 'inactive']);
        return back()->with('success', 'Product deactivated.');
    }
}
