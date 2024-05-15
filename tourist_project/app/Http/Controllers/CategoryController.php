<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Destination;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index($slug, $destinationId){
        $destinations = Destination::where('category_id', $destinationId)->get();
        $categories = Category::where('parent_id', 0)->get();
        return view('destination.category.list',compact('destinations','categories'));
    }
}
