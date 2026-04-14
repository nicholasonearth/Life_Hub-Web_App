<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tasks;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index() {
        $tasks = Tasks::where('user_id', auth()->id())->orderBy('created_at', 'desc')->get();
        return response()->json(['success' => true, 'data' => $tasks]);
    }

    public function store(Request $request) {
        $request->validate(['title' => 'required|string']);
        $task = Tasks::create([
            'title' => $request->title,
            'user_id' => auth()->id(),
            'is_done' => false
        ]);
        return response()->json(['success' => true, 'data' => $task]);
    }

    public function update(Request $request, $id) {
        $task = Tasks::where('user_id', auth()->id())->findOrFail($id);
        
        // Simpan status lama sebelum diupdate
        $wasDone = $task->is_done;
        
        $task->update($request->only(['title', 'is_done']));

        // LOGIKA GAMIFIKASI: Jika tugas diubah dari BELUM -> SELESAI
        if (!$wasDone && $task->is_done) {
            $user = auth()->user();
            $user->increment('experience', 10); // +10 XP

            // Cek Level Up (Tiap 100 XP)
            if ($user->experience >= 100) {
                $user->increment('level');
                $user->experience = 0; // Reset XP setelah naik level
                $user->save();
            }
        }

        return response()->json(['success' => true, 'data' => $task]);
    }

    public function destroy($id) {
        Tasks::where('user_id', auth()->id())->findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}