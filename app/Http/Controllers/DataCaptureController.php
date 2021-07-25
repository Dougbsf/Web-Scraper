<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DataCaptureController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        return view('data-capture');
    }

    public function getCarsData(string $termo = null){

        $response = Http::get('https://www.questmultimarcas.com.br/estoque',[
            'termo'=>$termo
        ]);

        $response->throw();

        $body = $response->body();

        $car_pattern = '/(<article\sclass=".*?"\sid=".*?">([\s\S]*?)<\/article>)/';

        $car_list = [];

        //separando os carros da lista
        preg_match_all($car_pattern, $body, $car_list);

        $car_list = $car_list[0];

        $defined_car_list = [];

        foreach ($car_list as $key => $value) {
            array_push($defined_car_list, $this->setupCarData($value));
        }

        return $this->saveToDatabase($defined_car_list);

    }


    private function setupCarData(string $car_data){
        $name_pattern = '/<h2 .*?"><a .*?>([\s\S]*?)<\/a/';
        $link_pattern = '/<h2 .*?"><a href="([\s\S]*?)">/';
        $year_pattern = '/<span class=".*?">\s*Ano: <\/span>\s*<span .*?>\s*([0-9]*)<\/span>/';
        $fuel_pattern = '/<span class=".*?">\s*Combustível: <\/span>\s*<span .*?>\s*(.*?) <\/span>/';
        $doors_pattern = '/<span class=".*?">\s*Portas: <\/span>\s*<span .*?>\s*([\d]) portas <\/span>/';
        $km_pattern = '/<span class=".*?">\s*Quilometragem: <\/span>\s*<span .*?>\s*([0-9]*[\.\d+]*?) <\/span>/';
        $gearbox_pattern = '/<span class=".*?">\s*Câmbio: <\/span>\s*<span .*?>\s*(.*?) <\/span>/';
        $color_pattern = '/<span class=".*?">\s*Cor: <\/span>\s*<span .*?>\s*(.*?) <\/span>/';

        $name_data = null;
        $link_data = null;
        $year_data = null;
        $fuel_data = null;
        $doors_data = null;
        $km_data = null;
        $gearbox_data = null;
        $color_data = null;

        preg_match_all($name_pattern, $car_data, $name_data);
        preg_match_all($link_pattern, $car_data, $link_data);
        preg_match_all($year_pattern, $car_data, $year_data);
        preg_match_all($fuel_pattern, $car_data, $fuel_data);
        preg_match_all($doors_pattern, $car_data, $doors_data);
        preg_match_all($km_pattern, $car_data, $km_data);
        preg_match_all($gearbox_pattern, $car_data, $gearbox_data);
        preg_match_all($color_pattern, $car_data, $color_data);

        return array(
            'nome_veiculo'=> $name_data ? $name_data[1][0] : '',
            'link'=> $link_data ? $link_data[1][0] : '',
            'ano'=> $year_data ? $year_data[1][0] : 0,
            'combustivel'=> $fuel_data ? $fuel_data[1][0] : '',
            'portas'=> $doors_data ? $doors_data[1][0] : 0,
            'quilometragem'=> $km_data ? str_replace('.','',$km_data[1][0]) : 0,
            'cambio'=> $gearbox_data ? $gearbox_data[1][0] : '',
            'cor'=> $color_data ? $color_data[1][0] : ''
        );
    }


    private function saveToDatabase(array $car_list){
        $user = Auth::user();

        $inserts = 0;
        $updates = 0;

        foreach ($car_list as $key => $car) {


            try{
                $car_exists = DB::select('select id,link from cars where link = :link', ['link' => $car['link']]);

                if(empty($car_exists)){

                    DB::insert('
                    insert into cars (nome_veiculo, link, ano, combustivel, portas, quilometragem, cambio, cor, user_id, created_at, updated_at)
                    values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                    [$car['nome_veiculo'], $car['link'], $car['ano'], $car['combustivel'], 
                     $car['portas'], $car['quilometragem'], $car['cambio'], $car['cor'], $user['id'],
                     \Carbon\Carbon::now(), \Carbon\Carbon::now()]);

                    $car_list[$key]['status'] = 'Inserido';

                    $inserts++;

                }else{

                    $carId = $car_exists[0]->id;

                    DB::update('update cars set nome_veiculo = ?, ano = ?, combustivel = ?, portas = ?, quilometragem = ?, cambio = ?, cor = ?, updated_at = ? where id = ?'
                    , [$car['nome_veiculo'], $car['ano'], $car['combustivel'], $car['portas'], $car['quilometragem'], $car['cambio'], $car['cor'], \Carbon\Carbon::now(), $carId]);

                    $car_list[$key]['status'] = 'Atualizado';

                    $updates++;
                }

            } catch (\Exception $e){
                throw $e;
            }

        }

        return json_encode(array('car_list'=>$car_list, 'inserts' => $inserts, 'updates' => $updates));

    }
}
