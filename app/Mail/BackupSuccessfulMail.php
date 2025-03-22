<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BackupSuccessfulMail extends Mailable
{
    use Queueable, SerializesModels;

    public $filename;
    public $backupPath;

    /**
     * Crea una nueva instancia del mensaje.
     *
     * @param string $filename
     * @param string $backupPath
     */
    public function __construct($filename, $backupPath)
    {
        $this->filename = $filename;
        $this->backupPath = $backupPath;
    }

    /**
     * Construye el mensaje.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Backup de Base de Datos Exitoso')
                    ->view('emails.backup_successful')
                    ->with([
                        'filename' => $this->filename,
                        'backupPath' => $this->backupPath,
                    ]);
    }
}
