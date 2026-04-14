<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Diary;
use App\Models\Habit;
use App\Models\Tasks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    // GET /api/stats — Ambil statistik user untuk chart di frontend
    public function index(Request $request)
    {
        $userId = auth()->id();

        // 1. Data Pie Chart: Tugas Selesai vs Belum
        $taskStats = [
            ['name' => 'Selesai', 'value' => Tasks::where('user_id', $userId)->where('is_done', true)->count()],
            ['name' => 'Pending', 'value' => Tasks::where('user_id', $userId)->where('is_done', false)->count()],
        ];

        // 2. Data Bar Chart: Perbandingan Streak Habit
        $habitStats = Habit::where('user_id', $userId)
            ->select('name', 'streak as value')
            ->get();

        // 3. Data Mood Chart: Distribusi Mood dari Diary
        $moodStats = Diary::where('user_id', $userId)
            ->select('mood as name', DB::raw('count(*) as value'))
            ->groupBy('mood')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => [
                'tasks'  => $taskStats,
                'habits' => $habitStats,
                'moods'  => $moodStats,
            ]
        ], 200);
    }
}
