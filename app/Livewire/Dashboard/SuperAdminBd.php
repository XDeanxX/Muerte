<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Throwable;

class SuperAdminBd extends Component
{
    use WithFileUploads;

    public $importFile;
    public $message = '';
    public $messageType = '';
    
    public $importType = null;

    protected $listeners = ['resetMessages'];
    
    private $dumpPath = '/opt/lampp/bin/mysqldump';
    private $mysqlPath = '/opt/lampp/bin/mysql';

    public function cancelarImportacion()
    {
        $this->importFile = null; 
        $this->importType = null;
    }

    public function exportSql($format = 'sql')
    {
        $this->resetMessages();

        try {
            if ($format === 'sql') {
                return $this->executeSqlExport();
            } elseif ($format === 'json') {
                return $this->executeJsonExport();
            }
        } catch (\Exception $e) {
            $this->message = 'Error al exportar: ' . $e->getMessage();
            $this->messageType = 'error';
            Log::error('SQL/JSON Export Error: ' . $e->getMessage());
        }
        
        return null;
    }

    private function executeSqlExport()
    {
        if (!file_exists($this->dumpPath)) {
             throw new \Exception('Ruta de mysqldump no válida. Archivo no encontrado en: ' . $this->dumpPath);
        }
        
        $timestamp = now()->format('Y-m-d_His');
        $dbName = env('DB_DATABASE' , 'sistema_solges_2025');
        $dbUser = env('DB_USERNAME', 'root');
        $dbPassword = env('DB_PASSWORD');
        $dbHost = env('DB_HOST', '127.0.0.1'); 
        $dbPort = env('DB_PORT', '3306');

        if (empty($dbName)) {
             throw new \Exception('El nombre de la base de datos (DB_DATABASE) no puede estar vacío.');
        }

        $backupDir = storage_path('app/backups');
        if (!file_exists($backupDir)) {
            mkdir($backupDir, 0755, true); 
        }
        
        $filename = "backup_{$dbName}_{$timestamp}.sql";
        $filepath = "{$backupDir}/{$filename}";

        $args = [
            '--host=' . escapeshellarg($dbHost),
            '--port=' . escapeshellarg($dbPort),
            '--user=' . escapeshellarg($dbUser),
        ];
        
        if (!empty($dbPassword)) {
            $args[] = '--password=' . escapeshellarg($dbPassword);
        }
        
        $command = sprintf(
            '%s %s %s > %s',
            escapeshellarg($this->dumpPath), 
            implode(' ', $args), 
            escapeshellarg($dbName),
            escapeshellarg($filepath)
        );

        $process = Process::fromShellCommandline($command);
        $process->setTimeout(300);
        $process->run();
        
        if (!$process->isSuccessful()) {
            $errorOutput = $process->getErrorOutput();
            if (file_exists($filepath)) { @unlink($filepath); }
            $sanitizedCommand = preg_replace('/--password=\S*/', '--password=*', $command);
            
            throw new \Exception("Fallo en mysqldump. Error: " . $errorOutput . " Comando: " . $sanitizedCommand); 
        }

        $this->dispatch('show-toast', [
            'message' => 'Exportación de la base de datos en SQL completada.' ,
            'type' => 'success'
        ]);

        return response()->download($filepath)->deleteFileAfterSend(true);
    }

    private function executeJsonExport()
    {
        $dbName = env('DB_DATABASE', 'sistema_solges_2025');
        $timestamp = now()->format('Y-m-d_His');
        
        $backupDir = storage_path('app/backups');
        if (!file_exists($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $filename = "backup_{$dbName}_{$timestamp}.json";
        $filepath = "{$backupDir}/{$filename}";

        $tables = DB::select('SHOW TABLES');
        $tables = array_map('current', $tables);
        $data = [];

        foreach ($tables as $table) {
            if (in_array($table, ['migrations', 'failed_jobs', 'password_reset_tokens', 'personal_access_tokens', 'jobs', 'sessions'])) {
                continue;
            }
            $data[$table] = DB::table($table)->get()->toArray();
        }

        file_put_contents($filepath, json_encode($data, JSON_PRETTY_PRINT));

        $this->dispatch('show-toast', [
            'message' => 'Exportación de la base de datos en JSON completada.' ,
            'type' => 'success'
        ]);

        return response()->download($filepath)->deleteFileAfterSend(true);
    }

    public function importDatabase()
    {
        $this->resetMessages();
        
        $this->validate([
            'importFile' => 'required|file|mimes:sql,json|max:102400',
            'importType' => 'required|in:sql,json',
        ], [
            'importFile.mimes' => 'Solo se permiten archivos con extensión .sql o .json.',
            'importType.required' => 'Debes seleccionar el formato del archivo (SQL o JSON).',
        ]);
        
        $path = null;
        $fullPath = null;
        $userId = auth()->id();

        try {
            $path = $this->importFile->store('temp-imports');
            $fullPath = Storage::disk('local')->path($path);

            chmod($fullPath, 0777);

            if ($this->importType === 'sql') {
                $this->executeSqlImport($fullPath);
            } else {
                $this->executeJsonImport($fullPath);
            }

            if ($userId) {
                auth()->loginUsingId($userId);
            }

            Storage::delete($path);
            
            $this->dispatch('show-toast', [
                'message' => 'Importación de la base de datos completada.' ,
                'type' => 'success'
            ]);

            $this->message = 'Importación completada exitosamente';
            $this->messageType = 'success';
            $this->reset(['importFile', 'importType']);

        } catch (\Exception $e) {
            if (isset($path)) {
                Storage::delete($path);
            }
            
            $this->message = 'Error al importar: ' . $e->getMessage();
            $this->messageType = 'error';
            Log::error('SQL/JSON Import Error: ' . $e->getMessage());
        }
    }

   private function executeJsonImport($filepath)
    {
        try {

            @ini_set('max_execution_time', 600);
            @ini_set('memory_limit', '1024M');

            Log::info('Iniciando importación JSON desde: ' . $filepath);
            
            $json = file_get_contents($filepath);
            if ($json === false) {
                throw new \Exception('No se pudo leer el archivo JSON desde el disco.');
            }
            
            $data = json_decode($json, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Error al decodificar el archivo JSON. Error: ' . json_last_error_msg());
            }

            Log::info('JSON decodificado. Deshabilitando llaves foráneas y truncando tablas.');

            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            foreach ($data as $table => $rows) {
                Log::info("Truncando tabla: $table");
                DB::table($table)->truncate();
            }

            Log::info('Tablas truncadas. Iniciando transacción de inserción.');

            DB::transaction(function () use ($data) {
                foreach ($data as $table => $rows) {
                    
                    if (empty($rows)) { // Omitir si no hay filas
                        Log::info("Omitiendo inserción en tabla vacía: $table");
                        continue;
                    }

                    Log::info("Insertando datos en: $table");
                    
                    foreach (array_chunk($rows, 200) as $chunk) {
                        $insertData = json_decode(json_encode($chunk), true);
                        DB::table($table)->insert($insertData);
                    }
                }
            });

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            Log::info('Importación JSON completada.');

        } catch (Throwable $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            Log::error('Fallo en executeJsonImport: ' . $e->getMessage() . ' en ' . $e->getFile() . ':' . $e->getLine());
            throw new \Exception('Error durante la importación JSON: ' . $e->getMessage());
        }
    }


    public function resetMessages()
    {
        $this->message = '';
        $this->messageType = '';
    }

    public function render()
    {
        return view('livewire.dashboard.super-admin-bd')->layout('components.layouts.rbac');
    }
}