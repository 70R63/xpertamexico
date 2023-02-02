<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App;
class rastreoAutomatico extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rastreo:automatico';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Se actualiza el rasreo';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $controller = App::make('\App\Http\Controllers\API\GuiaController');
        app()->call([$controller, 'rastreoActualizarAutomatico'], []);

        return Command::SUCCESS;
    }
}
