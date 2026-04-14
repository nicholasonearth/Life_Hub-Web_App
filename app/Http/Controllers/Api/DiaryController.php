<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Diary;
use Illuminate\Http\Request;

class DiaryController extends Controller
{
    // 1. GET: Ambil semua catatan diary milik user
    public function index()
    {
        $diaries = Diary::where('user_id', auth()->id())
                        ->orderBy('created_at', 'desc')
                        ->get();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil daftar diary.',
            'data'    => $diaries
        ], 200);
    }

    // 2. POST: Tambah catatan diary baru
    public function store(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $diary = Diary::create([
            // UBAH BAGIAN INI: Gunakan ->input()
            'title'   => $request->input('title'),
            'content' => $request->input('content'),
            'mood'    => $request->input('mood'), 
            'user_id' => auth()->id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Jurnal berhasil disimpan!',
            'data'    => $diary
        ], 201);
    }
    // 3. GET: Lihat satu catatan spesifik
    public function show($id)
    {
        $diary = Diary::where('user_id', auth()->id())->find($id);

        if (!$diary) {
            return response()->json(['message' => 'Jurnal tidak ditemukan atau akses dilarang.'], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $diary
        ], 200);
    }

    // 4. PUT: Update isi jurnal
    public function update(Request $request, $id)
    {
        $diary = Diary::where('user_id', auth()->id())->find($id);

        if (!$diary) {
            return response()->json(['message' => 'Jurnal tidak ditemukan atau akses dilarang.'], 404);
        }

        $diary->update($request->only(['title', 'content', 'mood']));

        return response()->json([
            'success' => true,
            'message' => 'Jurnal berhasil diperbarui.',
            'data'    => $diary
        ], 200);
    }

    // 5. DELETE: Hapus jurnal
    public function destroy($id)
    {
        $diary = Diary::where('user_id', auth()->id())->find($id);

        if (!$diary) {
            return response()->json(['message' => 'Jurnal tidak ditemukan atau akses dilarang.'], 404);
        }

        $diary->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jurnal berhasil dihapus.'
        ], 200);
    }
}