<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Response;
use Symfony\Component\Process\Process;

class SystemController extends Controller
{
    public function index()
    {
        return view('system.index');
    }

    public function backup()
    {
        $databaseName = config('database.connections.mysql.database');
        $userName = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');

        $filename = "backup-" . now()->format('Y-m-d-H-i-s') . ".sql";
        $path = storage_path('app/' . $filename);

        // Command to execute mysqldump
        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s > %s',
            escapeshellarg($userName),
            escapeshellarg($password),
            escapeshellarg($host),
            escapeshellarg($databaseName),
            escapeshellarg($path)
        );

        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            return redirect()->back()->with('error', 'Gagal melakukan backup database.');
        }

        return Response::download($path)->deleteFileAfterSend(true);
    }

    public function resetRequests(Request $request)
    {
        if ($request->confirmation !== 'RESET') {
            return redirect()->back()->with('error', 'Konfirmasi tidak sesuai. Harap ketik "RESET".');
        }

        try {
            DB::beginTransaction();
            
            Schema::disableForeignKeyConstraints();
            DB::table('permintaan_details')->truncate();
            DB::table('permintaans')->truncate();
            Schema::enableForeignKeyConstraints();
            
            DB::commit();

            return redirect()->back()->with('success', 'Semua data request telah berhasil direset.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mereset data: ' . $e->getMessage());
        }
    }
}
