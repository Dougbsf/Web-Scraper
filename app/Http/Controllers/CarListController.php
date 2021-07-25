<?php

namespace App\Http\Controllers;

use Faker\Core\Number;
use Illuminate\Support\Facades\DB;
use Session;

class CarListController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){
        $cars = $this->getCars();

        return view('car-list', ['cars'=>$cars]);
    }

    public function getCars(){
        try{
            $car_list = DB::select('select *,c.id as car_id from cars c');
            return $car_list;

        } catch (\Exception $e){
            throw $e;
        }
    }

    public function deleteCar($id){
        try{

            DB::delete('delete from cars where id = ?', [$id]);

            Session::flash('msg','Veículo com o id: ' . $id .' deletado com sucesso!');
            return true;

        }catch (\Exception $e){
            
            Session::flash('error','Houve um problema ao deletar o veículo.');
            return false;
        }
    }

}
