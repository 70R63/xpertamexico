<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App;
use Log;
class rastreoEstafetaV2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rastreo:estafetav2 {--paridad= : Valor de para o impar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Se actualiza el rastreo de estafeta v2';

    /**
     * Execute the console command.
     *
     * @return int
     */ 
    public function handle()
    {
        Log::info($this->option('paridad'));
        $paridad = $this->option('paridad');

        $controller = App::make('\App\Http\Controllers\API\GuiaController');
        app()->call([$controller, 'rastreoEstafetav2'], ["paridad"=>$paridad]);

        return Command::SUCCESS;
    }
}
