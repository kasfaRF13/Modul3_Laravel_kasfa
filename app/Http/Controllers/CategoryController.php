<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    public function destroy(Category $category)
    {
        // Cek apakah kategori masih digunakan oleh kegiatan
        if ($category->activities()->exists()) {
            return back()->withErrors(['error' => 'Gagal! Kategori tidak dapat dihapus karena masih digunakan oleh kegiatan.']);
        }

        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus!');
    }
}