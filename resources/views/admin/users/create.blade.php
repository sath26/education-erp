@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <h3>Novo Usuário:</h3>
            {!!
                form($form->add('insert','submit', [
                    'attr' => ['class' => 'btn btn-primary btn-block'],
                    'label' => Icon::plus().' Inserir'
                ]))
            !!}
        </div>
    </div>
@endsection