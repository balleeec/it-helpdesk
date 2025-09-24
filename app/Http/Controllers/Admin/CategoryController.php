<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Models\Category;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

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
            // BARU: Menambahkan kolom checkbox
            ->addColumn('checkbox', function ($category) {
                return '<input type="checkbox" name="category_checkbox[]" class="category_checkbox" value="' . $category->id . '" />';
            })
            ->addColumn('parent', fn($category) => $category->parent->name ?? '-')
            ->addColumn('action', function ($category) {
                $editUrl = route('admin.categories.edit', $category->id);
                $deleteUrl = route('admin.categories.destroy', $category->id);
                $editButton = '<a href="' . $editUrl . '" class="btn btn-sm btn-warning">Edit</a>';
                $deleteButton = '<button type="button" class="btn btn-sm btn-danger delete-btn" data-url="' . $deleteUrl . '">Hapus</button>';
                return $editButton . ' ' . $deleteButton;
            })
            // BARU: Memberitahu Yajra kolom checkbox juga berisi HTML
            ->rawColumns(['checkbox', 'action'])
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

    public function bulkDelete(Request $request)
    {
        $ids = collect($request->ids);
        $successNames = [];
        $errorNames = [];

        if ($ids->isNotEmpty()) {
            $categoriesToDelete = Category::whereIn('id', $ids)->with(['children'])->get();

            foreach ($categoriesToDelete as $category) {
                // Cek apakah ada anak kategori yang TIDAK termasuk dalam daftar yang akan dihapus
                $childIds = $category->children->pluck('id');
                $remainingChildren = $childIds->diff($ids);

                // Jika kategori aman untuk dihapus (tidak punya anak tersisa & tidak dipakai tiket)
                if ($remainingChildren->isEmpty()) {
                    $category->delete(); // Langsung hapus
                    $successNames[] = $category->name;
                } else {
                    // Jika tidak aman, tambahkan ke daftar error
                    $errorNames[] = $category->name;
                }
            }

            // Buat pesan respons dinamis
            $message = '';
            if (!empty($successNames)) {
                $message .= 'Berhasil menghapus kategori: ' . implode(', ', $successNames) . '. ';
            }
            if (!empty($errorNames)) {
                $message .= 'Gagal menghapus kategori (masih memiliki relasi): ' . implode(', ', $errorNames) . '.';
            }

            return response()->json(['success' => true, 'message' => trim($message)]);
        }

        return response()->json(['error' => "Tidak ada kategori yang dipilih."], 422);
    }
}
