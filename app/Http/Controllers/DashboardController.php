<?php

namespace App\Http\Controllers;

use App\Models\Permintaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Permintaan::query();

        $search = $request->query('search');
        $cabang_id = $request->query('cabang_id');

        if ($search) {
            $query->where('no_request', 'like', "%{$search}%");
        }

        if ($cabang_id) {
            $query->where('cabang_id', $cabang_id);
        }

        // Filter by cabang_id if the user is a branch level
        if ($user->isBranchLevel()) {
            $query->where('cabang_id', $user->cabang_id);
        }

        $stats = [
            'total' => (clone $query)->count(),
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'approved' => (clone $query)->where('status', 'approved')->count(),
            'rejected' => (clone $query)->where('status', 'rejected')->count(),
        ];

        $requests = $query->with(['cabang', 'user'])
            ->latest()
            ->paginate(10)
            ->appends($request->query());

        $cabangs = !$user->isBranchLevel() ? \App\Models\Cabang::all() : collect();

        return view('dashboard', compact('stats', 'requests', 'cabangs'));
    }
}
