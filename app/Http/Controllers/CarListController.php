<?php

namespace App\Http\Controllers;

use Faker\Core\Number;
use Illuminate\Support\Facades\DB;

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

            return redirect()->back()->with('msg','Veículo com o id: ' . $id .' deletado com sucesso!');

        }catch (\Exception $e){

            return redirect()->back()->with('error','Houve um problema ao deletar o veículo.');
        }
    }

}
