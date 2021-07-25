@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <h1 class="col-12 text-center">Lista de carros</h1>
        <p class="col-12 text-center">Lista com os carros cadastrados em nosso banco de dados, aqui também podemos excluí-los</p>
    </div>

    @if (session('msg'))
        <div class="alert alert-success" role="alert">
            {{ session('msg') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif

    @if(empty($cars))
        <p class="text-center">Não existem veículos cadastrados no sistema, utilize a tela de <a href="{{route('data-capture')}}">Captura</a> para cadastrar novos veículos</p>
    @endif

    @if(!empty($cars))
    <table class="table">
        <thead>
          <tr>
            <th scope="col">id</th>
            <th scope="col">Nome</th>
            <th scope="col">Especificações</th>
            <th scope="col">Link</th>
            <th scope="col">#</th>
          </tr>
        </thead>
        <tbody>
            @foreach ($cars as $car)
            <tr>
              <th scope="row">{{$car->car_id}}</th>
              <td>{{$car->nome_veiculo}}</td>
              <td class="especificacoes">
                  <p>Ano:  {{$car->ano}}</p>
                  <p>Combustível:  {{$car->combustivel}}</p>
                  <p>Portas:  {{$car->portas}}</p>
                  <p>Quilometragem:  {{$car->quilometragem}} KM</p>
                  <p>Câmbio:  {{$car->cambio}}</p>
                  <p>Cor: {{$car->cor}}</p>
                </td>
              <td><a href="{{$car->link}}">{{$car->link}}</a></td>
              <td>
                  <a href="#" onclick="deleteCar({{json_encode($car)}})"</a>Deletar</td>
            </tr>
            @endforeach
        </tbody>
      </table>
      @endif
</div>
@endsection

<script>

function deleteCar(car){
    console.log(car);
    if(confirm(`Tem certeza de que deseja deletar o veículo: ${car.nome_veiculo} ?`)){
        location.href = `{{route('deleteCar')}}/${car.car_id}`;
    }
}

</script>
