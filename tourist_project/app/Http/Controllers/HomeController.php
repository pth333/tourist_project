<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function index(){

        $response = Http::get('http://127.0.0.1:8000/admin/destination');
        dd($response);
        if($response->ok()){
            $destination = $response->json();
            dd($destination);
        }

        $categories = Category::where('parent_id', 0)->get();
        return view('home.home',compact('categories'));
    }
}
