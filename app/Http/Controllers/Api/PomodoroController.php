<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PomodoroController extends Controller
{
    // POST /api/pomodoro/complete
    // Dipanggil saat sesi fokus 25 menit selesai → beri +25 XP ke user
    public function complete(Request $request)
    {
        $user      = $request->user();
        $xpGain    = 25;

        $newXp    = $user->experience + $xpGain;
        $newLevel = $user->level;
        $leveledUp = false;

        // Level naik tiap 100 XP (bisa naik lebih dari 1 level sekaligus)
        while ($newXp >= 100) {
            $newXp -= 100;
            $newLevel++;
            $leveledUp = true;
        }

        $user->update([
            'experience' => $newXp,
            'level'      => $newLevel,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Sesi Pomodoro selesai! +{$xpGain} XP",
            'data'    => [
                'xp_gained'  => $xpGain,
                'level'      => $newLevel,
                'experience' => $newXp,
                'leveled_up' => $leveledUp,
            ]
        ], 200);
    }
}
