<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('settings.manage');

        $activities = Activity::with(['causer', 'subject'])
            ->when($request->event, fn ($q, $e) => $q->where('event', $e))
            ->when($request->subject, fn ($q, $s) =>
                $q->where('subject_type', 'like', "%{$s}%")
            )
            ->when($request->user, fn ($q, $u) =>
                $q->whereHasMorph('causer', [\App\Models\User::class], fn ($uq) =>
                    $uq->where('name', 'like', "%{$u}%")
                       ->orWhere('email', 'like', "%{$u}%")
                )
            )
            ->when($request->from, fn ($q, $f) =>
                $q->where('created_at', '>=', Carbon::parse($f)->startOfDay())
            )
            ->when($request->to, fn ($q, $t) =>
                $q->where('created_at', '<=', Carbon::parse($t)->endOfDay())
            )
            ->latest()
            ->paginate(50);

        return view('pages.admin.activity-log', compact('activities'));
    }
}
