<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Habit;
use Illuminate\Http\Request;

class HabitController extends Controller
{
    // 1. Ambil semua Habit milik user
    public function index()
    {
        $habits = Habit::where('user_id', auth()->id())->get();
        return response()->json(['success' => true, 'data' => $habits]);
    }

    // 2. Tambah Habit Baru (Misal: "Coding", "Minum Air")
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $habit = Habit::create([
            'name' => $request->input('name'),
            'streak' => 0,
            'user_id' => auth()->id()
        ]);

        return response()->json(['success' => true, 'data' => $habit], 201);
    }

    // 3. Update Streak (Logika Check-in)
    // Fungsi ini akan menambah +1 pada streak setiap diklik
    public function update(Request $request, $id)
    {
        $habit = Habit::where('user_id', auth()->id())->find($id);

        if (!$habit) {
            return response()->json(['message' => 'Habit tidak ditemukan'], 404);
        }

        // Jika request berisi 'increment', tambah streak
        if ($request->has('increment')) {
            $habit->increment('streak');
            $habit->update(['last_checked' => now()]);
        } else {
            // Update nama habit saja
            $habit->update($request->only(['name']));
        }

        return response()->json(['success' => true, 'data' => $habit]);
    }

    // 4. Hapus Habit
    public function destroy($id)
    {
        $habit = Habit::where('user_id', auth()->id())->find($id);
        if (!$habit) return response()->json(['message' => 'Not Found'], 404);
        
        $habit->delete();
        return response()->json(['success' => true, 'message' => 'Habit dihapus']);
    }
}