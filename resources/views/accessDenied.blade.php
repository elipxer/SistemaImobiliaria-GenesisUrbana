@extends('adminlte::page')<!DOCTYPE html>
@section('css')
    <style>
        #alert{
            font-size: 35px;
            text-align: center;
            line-height: 100px;
        }
    </style>
@endsection
@section('content')
<div class="alert alert-danger w-75" id="alert" style="margin: auto; height:150px">
    ACESSO NEGADO.
</div>
<h4 style="margin-top: 15px;"><center>Seu perfil de usuário não tem permissão para este item.</center></h4>

@endsection