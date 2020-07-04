<?php

use App\Geo;
use App\Pulso;
use App\Temperatura;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $email=env('EMAIL_ADMIN', 'david.criollo14@gmail.com');
        $user=User::where('email',$email)->first();
        if(!$user){
            $user=new User();
            $user->name = 'MEDARDO';
            $user->email = $email;
            $user->password = Hash::make($email);
            $user->save();

        }

        $temp=Temperatura::first();
        if(!$temp){
            $temp=new Temperatura();
            $temp->valor=0;
            $temp->save();
        }

        $geo=Geo::first();
        if(!$geo){
            $geo=new Geo();
            $geo->lat='-1.047717';
            $geo->lng='-78.588130';
            $geo->save();
        }

        $pul=Pulso::first();
        if(!$pul){
            $pul=new Pulso();
            $pul->valor=0;
            $pul->save();
        }
    }
}
