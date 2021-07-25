@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <p class="text-center">Bem vindo, {{$user['name']}}</p>

                    <div class="col-lg-12 d-flex justify-content-around">
                        <a href="{{route('data-capture')}}" class="col-5 btn btn-primary" >Capturar Dados</a>
                        <a href="{{route('car-list')}}" class="col-5 btn btn-secondary">Listar Carros</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
