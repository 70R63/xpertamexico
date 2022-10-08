<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Models\Ltd;


class GuiaCreada extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * El objeto contiene lo valores de la forma
     *
     * @var objeto
     */
    public $objeto;

    /**
     * El nombre del ltd
     *
     * @var nombre
     */
    public $nombre;

    /**
     * Ees el valor generado despues de insertar el registro
     *
     * @var id
     */
    public $id;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($objeto, int $id)
    {
        $this->objeto = $objeto;
        $ltd = Ltd::findOrFail($objeto->ltd_id)->get("nombre")->toArray();
        $this->nombre =$ltd[0]["nombre"]; 
        $this->id = $id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from("guias@ulalaexpress.com","Guias UlalaExpress")
            ->subject("Asunto del correo")
            ->view('email/guia_creacion')
            ->with(["usuario"=>"Javier"]);
    }
}
