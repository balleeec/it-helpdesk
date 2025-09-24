<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Models\Category;
use Yajra\DataTables\DataTables;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.categories.index');
    }

    public function data()
    {
        $categories = Category::with('parent')->select('categories.*');
        return DataTables::of($categories)
            ->addIndexColumn()
            ->addColumn('parent', fn($category) => $category->parent->name ?? '-')
            ->addColumn('action', function ($category) {
                $editUrl = route('admin.categories.edit', $category->id);
                $deleteUrl = route('admin.categories.destroy', $category->id);

                $editButton = '<a href="' . $editUrl . '" class="btn btn-sm btn-warning">Edit</a>';

                // Ganti tombol hapus agar bisa ditargetkan oleh JavaScript
                $deleteButton = '<button type="button" class="btn btn-sm btn-danger delete-btn" data-url="' . $deleteUrl . '">Hapus</button>';

                return $editButton . ' ' . $deleteButton;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.categories.create', compact('categories'));
    }

    public function store(StoreCategoryRequest $request)
    {
        Category::create($request->validated());
        return redirect()->route('admin.categories.index')->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        $categories = Category::where('id', '!=', $category->id)->get();
        return view('admin.categories.edit', compact('category', 'categories'));
    }

    public function update(StoreCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        // CEK APAKAH KATEGORI INI PUNYA ANAK
        if ($category->children()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Kategori tidak dapat dihapus karena memiliki anak kategori.');
        }

        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
