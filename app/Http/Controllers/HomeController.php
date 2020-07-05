<?php
namespace App\Http\Controllers;
use App\Geo;
use App\Pulso;
use App\Temperatura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data = array('geo' => Geo::first() );
        return view('home',$data);
    }
    
    public function obtenerTemperatura()
    {
        $temp=Temperatura::first();
        $fecha=$temp->updated_at?'Última fecha: '.$temp->updated_at->toDateTimeString():'';
        $data = array('temperatura' =>  $temp->valor??0,'fecha'=>$fecha);
        return response()->json($data);
    }

    public function obtenerLatitudLongitud()
    {
        $geo=Geo::first();
        $fecha=$geo->updated_at?'Última fecha: '.$geo->updated_at->toDateTimeString():'';
        $data = array('latitude' => $geo->lat??null,'longitude'=>$geo->lng??null,'fecha'=>$fecha );
        return response()->json($data);
    }

    public function obtenerPulsos()
    {
        $pulso=Pulso::first();
        $fecha=$pulso->updated_at?'Última fecha: '.$pulso->updated_at->toDateTimeString():'';
        $data = array('pulso' =>  $pulso->valor??0,'fecha'=>$fecha);
        return response()->json($data);
    }
    
}
