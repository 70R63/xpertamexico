<?php
namespace App\Negocio\Saldos;

use Log;

//modelos
use App\Models\Guia;
use App\Models\GuiasPaquete;
use App\Models\Saldos\GuiasExternas as mGuiasExternas;
//use App\Models\Saldos\Ajustes as mAjustes;
//use App\Models\API\Sucursal;

//Negocio
use App\Negocio\Saldos\Saldos AS nSaldos;

//Utilerias
use Carbon\Carbon;

class Externa 
{
    private $mensaje = array();
    private $guia = null;
    private $tabla = array();
    
 
    /**
     * Metodo detalleGuia, se busca  sumar saldo de una guia eliminada
     * 
     * @param array $parametros
     * @return void
     */

    public function guia ($file)
    {
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
        //dd($file->getClientOriginalName());
        $numeroDeRegistros = 0;
        $ids = "";
        $importeTotal = 0;
        $fileNombre = $file->getClientOriginalName();

        $csvFile = fopen($file->getRealPath(), "r");
        fgetcsv($csvFile);
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
            $precioUnitario = $data[16]+$data[17]+$data[18]+$data[14]+$data[19]+$data[20];
            $insert= array();
            $cia = $data[11];
            $precio = sprintf("%.4f",($precioUnitario*1.16));
            
            $insert['empresa_id'] = $data[0];
            $insert['ltd_id'] = $data[1];
            $insert['tracking_number'] = $data[2];
            $insert['servicio_id'] = $data[3];
            $insert['created_at'] = $data[4];
            $insert['largo'] = $data[5];
            $insert['ancho'] = $data[6];
            $insert['alto'] = $data[7];
            $insert['peso'] = $data[8];
            $insert['peso_dimensional'] = $data[9];
            $insert['peso_bascula'] = $data[10];
            $insert['cia'] = $cia;
            $insert['cia_d'] = $data[12];
            $insert['valor_envio'] = $data[13];
            $insert['seguro'] = $data[14];
            $insert['sobre_peso_kg'] = $data[15];
            $insert['costo_base'] = $data[16];
            $insert['costo_kg_extra'] = $data[17];
            $insert['costo_extendida'] = $data[18];
            $insert['servicio_premium'] = $data[19];
            $insert['multipieza'] = $data[20];
            $insert['precio'] = $precio;
             

            $insert['numero_solicitud'] = Carbon::now()->timestamp;
            $insert['usuario'] = auth()->user()->name;
            $insert['piezas'] = 1;

            $guiaId = Guia::create($insert)->id;
            
            $insert['guia_id'] = $guiaId;
            $insert['precio_unitario'] = $precioUnitario;


            GuiasPaquete::create($insert);
            
            $nSaldos = new nSaldos();
            $nSaldos->menosPrecio ( $cia, $precio );

            $ids = sprintf("%s%s,",$ids,$guiaId);

            $numeroDeRegistros++;
            $importeTotal +=$precio;
        }
        fclose($csvFile);

        $insertExternas['user_id'] = auth()->user()->id;
        $insertExternas['no_guias'] = $numeroDeRegistros;
        $insertExternas['importe_total'] = $importeTotal;
        $insertExternas['file_nombre'] = $fileNombre;
        

        mGuiasExternas::create($insertExternas);

        $this->mensaje[]= sprintf("%s registros insertados ",$numeroDeRegistros);
        $this->mensaje[]= sprintf("Los ids son : %s",$ids);
        
        
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
    }

     /**
     * Metodo detalleGuia, se busca  sumar saldo de una guia eliminada
     * 
     * @param array $parametros
     * @return void
     */

    public function tabla ()
    {
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);

            $this->tabla = mGuiasExternas::select("id","created_at", "user_id", "no_guias", "importe_total")
                //->joinUsuario()
                //->joinEmpresa()
                ->where('created_at', '>', now()->subDays(30)->endOfDay())
                ->orderBy("id","desc")
                ->get()->toArray();
        Log::info(__CLASS__." ".__FUNCTION__." ".__LINE__);
    }



    public function getMensajes ()
    {
        return $this->mensaje;
    }

    public function getGuia ()
    {
        return $this->guia;
    }

    public function getTabla()
    {
        return $this->tabla;
    }
}