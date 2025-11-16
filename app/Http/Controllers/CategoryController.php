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
        $categories = Category::orderBy('name', 'asc')->get(); // Menampilkan data dengan pagination
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:10|unique:categories,code',
            ]);

            Category::create($validated);

            return redirect()->route('categories.index')->with('success', 'Kategori berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuat kategori. Silakan coba lagi. Error: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:10|unique:categories,code,' . $category->id,
            ]);

            $category->update($validated);

            return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui kategori. Silakan coba lagi. Error: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            $documentCount = $category->documents()->count();
            $categoryName = $category->name;

            $category->delete();

            $message = "Kategori '$categoryName' berhasil dihapus.";
            if ($documentCount > 0) {
                $message .= " ($documentCount dokumen terkait juga telah dihapus)";
            }

            return redirect()->route('categories.index')->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus kategori. Silakan coba lagi. Error: ' . $e->getMessage());
        }
    }

    public function bulkDelete(Request $request)
    {
        try {
            $ids = json_decode($request->ids);

            if (empty($ids) || !is_array($ids)) {
                return redirect()->back()->with('error', 'Tidak ada kategori yang dipilih.');
            }

            $categories = Category::whereIn('id', $ids)->get();
            $totalDocuments = $categories->sum(function ($category) {
                return $category->documents()->count();
            });

            $count = Category::whereIn('id', $ids)->delete();

            $message = "$count kategori berhasil dihapus.";
            if ($totalDocuments > 0) {
                $message .= " ($totalDocuments dokumen terkait juga telah dihapus)";
            }

            return redirect()->route('categories.index')->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus kategori. Silakan coba lagi. Error: ' . $e->getMessage());
        }
    }

    public function countDocuments(Request $request)
    {
        try {
            $ids = json_decode($request->ids);

            if (empty($ids) || !is_array($ids)) {
                return response()->json(['totalDocuments' => 0]);
            }

            $categories = Category::whereIn('id', $ids)->get();
            $totalDocuments = $categories->sum(function ($category) {
                return $category->documents()->count();
            });

            return response()->json(['totalDocuments' => $totalDocuments]);
        } catch (\Exception $e) {
            return response()->json(['totalDocuments' => 0, 'error' => $e->getMessage()], 500);
        }
    }
}
