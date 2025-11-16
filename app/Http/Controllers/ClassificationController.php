<?php

namespace App\Http\Controllers;

use App\Models\Classification;
use Illuminate\Http\Request;

class ClassificationController extends Controller
{
    public function index()
    {
        $classifications = Classification::orderBy('kode_klasifikasi')->get();
        return view('admin.classifications.index', compact('classifications'));
    }

    public function create()
    {
        return view('admin.classifications.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_klasifikasi' => 'required|string|max:50|unique:classifications,kode_klasifikasi',
            'nama_klasifikasi' => 'required|string|max:255',
        ]);

        Classification::create($validated);

        return redirect()->route('classifications.index')
            ->with('success', 'Klasifikasi berhasil ditambahkan');
    }

    public function edit(Classification $classification)
    {
        return view('admin.classifications.edit', compact('classification'));
    }

    public function update(Request $request, Classification $classification)
    {
        $validated = $request->validate([
            'kode_klasifikasi' => 'required|string|max:50|unique:classifications,kode_klasifikasi,' . $classification->id,
            'nama_klasifikasi' => 'required|string|max:255',
        ]);

        $classification->update($validated);

        return redirect()->route('classifications.index')
            ->with('success', 'Klasifikasi berhasil diupdate');
    }

    public function destroy(Classification $classification)
    {
        // Check if used by documents
        if ($classification->documents()->count() > 0) {
            return redirect()->route('classifications.index')
                ->with('error', 'Klasifikasi tidak dapat dihapus karena sudah digunakan oleh dokumen');
        }

        $classification->delete();

        return redirect()->route('classifications.index')
            ->with('success', 'Klasifikasi berhasil dihapus');
    }

    // API endpoint untuk dropdown
    public function getAllActive()
    {
        $classifications = Classification::orderBy('kode_klasifikasi')->get();
        return response()->json($classifications);
    }
}
