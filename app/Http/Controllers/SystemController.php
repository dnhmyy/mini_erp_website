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
        // 1. Check if mysqldump is available
        $mysqlPath = shell_exec('command -v mysqldump');
        if (!$mysqlPath) {
            return redirect()->back()->with('error', 'Gagal: Tool "mysqldump" tidak ditemukan. Harap jalankan "docker-compose up -d --build" untuk mengaktifkan perubahan Dockerfile.');
        }

        // 2. Check Directory Writability
        $storagePath = storage_path('app');
        if (!is_writable($storagePath)) {
            return redirect()->back()->with('error', 'Gagal: Folder "' . $storagePath . '" tidak dapat ditulis oleh sistem. Hubungi Admin untuk cek izin folder (chmod).');
        }

        $databaseName = config('database.connections.mysql.database');
        $userName = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');

        $filename = "backup-" . now()->format('Y-m-d-H-i-s') . ".sql";
        $path = $storagePath . '/' . $filename;

        // 3. Execute mysqldump (redirect stderr to file to capture errors)
        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s > %s 2>&1',
            escapeshellarg($userName),
            escapeshellarg($password),
            escapeshellarg($host),
            escapeshellarg($databaseName),
            escapeshellarg($path)
        );

        exec($command, $output, $returnVar);

        // 4. Handle Failure
        if ($returnVar !== 0) {
            $errorMessage = '';
            if (file_exists($path)) {
                $errorMessage = file_get_contents($path);
                unlink($path); // Delete the error log file
            }
            
            // Clean up common error messages for better readability
            $errorMessage = trim(str_replace(['mysqldump: [Warning] Using a password on the command line interface can be insecure.', 'mysqldump: '], '', $errorMessage));
            
            return redirect()->back()->with('error', 'Gagal Backup: ' . ($errorMessage ?: 'Terjadi kesalahan sistem yang tidak diketahui.'));
        }

        // 5. Final check for success
        if (!file_exists($path) || filesize($path) === 0) {
            if (file_exists($path)) unlink($path);
            return redirect()->back()->with('error', 'Gagal Backup: File hasil backup kosong atau tidak tercipta.');
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
