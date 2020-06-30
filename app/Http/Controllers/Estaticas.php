<?php

namespace App\Http\Controllers;

use App\Geo;
use App\Notifications\NotyTemperatura;
use App\Temperatura;
use App\User;
use Illuminate\Http\Request;

class Estaticas extends Controller
{
   public function ingresarDatos($tem,$lat,$lng)
    {
        $nuevo_valor=floatval($tem);
        
        $temperatura=Temperatura::first();
        if(!$temperatura){
            $temperatura=new Temperatura();
        }
        $temperatura->valor=$nuevo_valor;
        $temperatura->save();   

        if($temperatura->valor>=38){
            $user=User::first();
            $user->notify(new NotyTemperatura($temperatura->valor));
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
}
