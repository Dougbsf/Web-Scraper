@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <h1 class="col-12 text-center">Captura de dados</h1>
        <p class="col-12 text-center">Utilize o campo abaixo para buscar por um termo específico ou deixe em branco para retornar todos os resultados da primeira página.</p>
    </div>
    <div class="row mb-4 d-flex justify-content-center">
        <form class="col-lg-8">
            <div class="input-group col-lg-12">
                <input id="inputTermo" type="text" class="form-control col-8 mr-2" placeholder="Termo" aria-label="Termo">
                <button class="btn btn-primary col-4" id="btnCapturar">Capturar</button>
            </div>
        </form>
    </div>
    <div class="row col-12 d-flex justify-content-center" id="msg">
    </div>
    <div class="row col-12" id="carData">
    </div>
</div>
@endsection

<script>
window.onload = function(){
    document.getElementById('btnCapturar').addEventListener('click', function(event){
        getCarsData(event);
    });
}


function getCarsData(e){
    e.preventDefault();
    blockButton();

    const termo = document.getElementById('inputTermo').value;

    var url = '{{route('getCarsData')}}/'+termo;
    $.ajax({
        type: "GET",
        url: url,
        success: function(data,status){
            mensagem();
            showResults(data);
            unblockButton();
        },
        error: function(error){
            mensagemErro();
            console.log(error)
            unblockButton();
        }

    });
    return false;
}

function blockButton(){
    const button = document.getElementById('btnCapturar');
    button.setAttribute('disabled','disabled');
    button.innerHTML = 'Aguarde...';
}

function unblockButton(){
    const button = document.getElementById('btnCapturar');
    button.removeAttribute('disabled');
    button.innerHTML = 'Capturar';
}

function mensagem(msg = null){
        document.getElementById('msg').innerHTML = '';

        const div = document.createElement('div');
        div.classList.add('alert', 'alert-success');
        div.setAttribute('role', 'alert');
        if(!msg){
            div.innerHTML = `
            Captura realizada com sucesso!<br/>
            Ir até a <a href="{{route('car-list')}}">Área de Listagem</a>?
            `;
        }else{
            div.innerHTML = msg;
        }

        document.getElementById('msg').appendChild(div);
}

function mensagemErro(){
    document.getElementById('msg').innerHTML = '';

    const div = document.createElement('div');
    div.classList.add('alert', 'alert-danger');
    div.setAttribute('role', 'alert');
    div.innerHTML = 'Houve um erro ao realizar a captura...';

    document.getElementById('msg').appendChild(div);
}

function showResults(data){
    data = JSON.parse(data);

    const carDataDiv = document.getElementById('carData');
    carDataDiv.innerHTML = '';

    if(data.car_list.length > 0){
        const divData = document.createElement('div');
        divData.classList.add('row','col-12');
        let tableHtml = `
        <table class="table">
        <thead>
            <tr>
            <th scope="col">#</th>
            <th scope="col">Nome</th>
            <th scope="col">Especificações</th>
            <th scope="col">Status</th>
            </tr>
        </thead>
        <tbody>
        `;

        for (let index = 0; index < data.car_list.length; index++) {
            const row = `
            <tr>
                <th scope="row">${index+1}</th>
                <td><a href="${data.car_list[index].link}">${data.car_list[index].nome_veiculo}</a></td>
                <td class="especificacoes">
                    <p>Ano: ${data.car_list[index].ano}</p>
                    <p>Combustível: ${data.car_list[index].combustivel}</p>
                    <p>Portas: ${data.car_list[index].portas}</p>
                    <p>Quilometragem: ${data.car_list[index].quilometragem} KM</p>
                    <p>Câmbio: ${data.car_list[index].cambio}</p>
                    <p>Cor: ${data.car_list[index].cor}</p>
                </td>
                <td>${data.car_list[index].status}</td>
            </tr>
            `
            tableHtml+= row;

        }

        tableHtml+= `
            </tbody>
            </table>
        `;

        divData.innerHTML = tableHtml;

        carDataDiv.appendChild(divData);
    }else{
        mensagem('Nenhum veículo encontrado com esses termos');
    }

}

</script>
