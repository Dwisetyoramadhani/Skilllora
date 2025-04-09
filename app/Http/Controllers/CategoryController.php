<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function createCategory(Request $request)
    {
        try {
            $request->validate([
                'category_title' => 'required|string'
            ]);

            $category = Category::create([
                'category_title' => $request->category_title
            ]);

            return response()->json([
                'message' => 'Category berhasil di buat',
                'category' => $category

            ], 201);
        } catch (\Exception $e) {
            return response()->json([

                'message' => 'Terjadi Kesalahan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $category = Category::find($id);

            $category->delete();

            return response()->json([
                'message' => 'Category berhasil di hapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        try {
            $category = Category::all();

            return CategoryResource::collection($category);
        } catch (\Exception $e) {
            return response()->json([

                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage()
            ]);
        }
    }
}
