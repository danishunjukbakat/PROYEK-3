<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $categories = Category::withCount('activities')
        ->orderBy('name')
        ->get();

    return view('categories.index', compact('categories'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
        {
            if ($category->activities()->exists()) {
                return redirect()
                    ->route('categories.index')
                    ->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh Activity.');
                 }

    $category->delete();

    return redirect()
        ->route('categories.index')
        ->with('success', 'Kategori berhasil dihapus.');
    }
}
