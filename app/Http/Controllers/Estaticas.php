<?php

namespace App\Http\Controllers;

use App\Geo;
use App\Notifications\NotyTemperatura;
use App\Pulso;
use App\Temperatura;
use App\User;
use Illuminate\Http\Request;

class Estaticas extends Controller
{
   public function ingresarDatos($tem,$pul,$lat,$lng)
    {
        $msg_tem='ALERTA, BRYAN TEMPERATURA ALTA: ';
        $msg_pul='ALERTA, BRYAN FRECUENCIA CARDIACA ALTA';

        $nuevo_valor_temp=floatval($tem);
        $nuevo_valor_pul=floatval($pul);
        
        $temperatura=Temperatura::first();
        if(!$temperatura){
            $temperatura=new Temperatura();
        }
        $temperatura->valor=$nuevo_valor_temp;
        $temperatura->save();   

        if($temperatura->valor>=38){
            $user=new User();
            $user->email='qmedgazu@hotmail.com';
            $user->notify(new NotyTemperatura($msg_tem.' '.$temperatura->valor.' °C'));
        }

        if($temperatura->valor>=38){
            $user=new User();
            $user->email='carol-myky@hotmail.com';
            $user->notify(new NotyTemperatura($msg_tem.' '.$temperatura->valor.' °C'));
        }
        
        $pulso=Pulso::first();
        if(!$pulso){
            $pulso =new Pulso();
        }
        $pulso->valor=$nuevo_valor_pul;
        $pulso->save();

        if($pulso->valor>=95){
            $user=new User();
            $user->email='qmedgazu@hotmail.com';
            $user->notify(new NotyTemperatura($msg_pul.' '.$pulso->valor.' PULSOS POR MINUTO'));
        }
        if($pulso->valor>=95){
            $user=new User();
            $user->email='carol-myky@hotmail.com';
            $user->notify(new NotyTemperatura($msg_pul.' '.$pulso->valor.' PULSOS POR MINUTO'));
        }

        $geo=Geo::first();
        if(!$geo){
            $geo=new Geo();
        }
        $geo->lat=$lat;
        $geo->lng=$lng;
        $geo->save();


        
        return response()->json(['estado'=>'si']);
    }

    public function obtenerLatitudLongitud()
    {
        $geo=Geo::first();
        $fecha=$geo->updated_at?'Última fecha: '.$geo->updated_at->toDateTimeString():'';
        $data = array('latitude' => $geo->lat??null,'longitude'=>$geo->lng??null,'fecha'=>$fecha );
        return response()->json($data);
    }
}
