<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    private function rules($id = null): array
    {
        return [
            'code'       => ['required', 'string', 'max:50', Rule::unique('products', 'code')->ignore($id)],
            'name'       => 'required|string|max:150',
            'category'   => ['required', Rule::in(Product::CATEGORIES)],
            'cost_price' => 'required|integer|min:0',
            'price'      => 'required|integer|min:1',
            'stock'      => 'required|integer|min:0',
        ];
    }

    public function index()  { return Product::orderBy('name')->get(); }

    public function store(Request $r)
    {
        return response()->json(Product::create($r->validate($this->rules())), 201);
    }

    public function update(Request $r, Product $product)
    {
        $product->update($r->validate($this->rules($product->id)));
        return $product;
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->noContent();
    }
}
