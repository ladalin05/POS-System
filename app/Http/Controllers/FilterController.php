<?php

namespace App\Http\Controllers;

use App\Models\Product\Category;
use App\Models\Setting\Unit;
use Illuminate\Http\Request;

class FilterController extends Controller
{
    public function getSubCategory(Request $request)
    {
        $request->validate([
            'category_id' => 'required'
        ]);

        $subCategories = Category::where('parent_id', $request->category_id)->pluck('name', 'id')->toArray();

        return response()->json($subCategories);
    }   
}