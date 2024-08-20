<?php

namespace App\Services;

use App\Mail\SendPasswordMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    /**
     * Enviar notificación por correo electrónico.
     *
     * @param  string  $email
     * @param  string  $subject
     * @param  array  $data
     * @return void
     */
    public function sendEmail($email, $subject, $data)
    {
        try {
            Mail::to($email)->send(new SendPasswordMail($data['user'], $data['password']));
        } catch (\Exception $e) {
            // Manejar el error, por ejemplo, registrarlo en el log
            Log::error('Error al enviar el correo: ' . $e->getMessage());
            
            // Puedes lanzar una excepción personalizada o manejar el error de otra manera
            throw new \Exception('No se pudo enviar el correo.');
        }
    }

    // Aquí puedes agregar métodos para enviar SMS, WhatsApp, etc.
}