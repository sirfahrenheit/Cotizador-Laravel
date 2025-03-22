<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Mail;
use App\Mail\BackupSuccessfulMail;

class DatabaseBackup extends Command
{
    /**
     * El nombre y la firma del comando.
     *
     * @var string
     */
    protected $signature = 'database:backup';

    /**
     * La descripción del comando.
     *
     * @var string
     */
    protected $description = 'Realiza el backup de la base de datos y lo guarda como un archivo comprimido, enviando notificación por email.';

    /**
     * Ejecuta el comando.
     *
     * @return int
     */
    public function handle()
    {
        // Ruta donde se almacenarán los backups
        $backupPath = storage_path('app/backup');

        // Si el directorio no existe, se crea
        if (!File::exists($backupPath)) {
            File::makeDirectory($backupPath, 0755, true);
            $this->info("Directorio de backup creado en: {$backupPath}");
        }

        // Generar el nombre del archivo con fecha y hora
        $filename = "backup-" . now()->format('Y-m-d_H-i-s') . ".sql.gz";

        // Construir el comando mysqldump usando los datos del .env
        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s | gzip > %s',
            escapeshellarg(env('DB_USERNAME')),
            escapeshellarg(env('DB_PASSWORD')),
            escapeshellarg(env('DB_HOST')),
            escapeshellarg(env('DB_DATABASE')),
            escapeshellarg($backupPath . '/' . $filename)
        );

        $this->info("Ejecutando comando de backup...");

        // Ejecutar el comando utilizando Symfony Process
        $process = Process::fromShellCommandline($command);
        $process->setTimeout(300); // Timeout de 5 minutos

        try {
            $process->mustRun();
            $this->info("Backup completado exitosamente. Archivo generado: {$backupPath}/{$filename}");

            // Enviar notificación por email
            $notificationEmail = env('BACKUP_NOTIFICATION_EMAIL', 'cotizaciones@distribuidorajadi.site');
            Mail::to($notificationEmail)->send(new BackupSuccessfulMail($filename, $backupPath));
            $this->info("Notificación por correo enviada a: {$notificationEmail}");
        } catch (ProcessFailedException $exception) {
            $this->error("El comando de backup falló: " . $exception->getMessage());
            return 1;
        }

        return 0;
    }
}

