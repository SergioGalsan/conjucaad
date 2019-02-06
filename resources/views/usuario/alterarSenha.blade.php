<?php 
//use App\Classes\URLify;
use App\Classes\MyUtil; 
?>


@extends('layout.principal')	

@section('title', 'JUCAAD | Alterar Senha')

@section('conteudo')

	<section class="cg_bg-new-blue" id="usuarios">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 text-center text-white">
					<h2 class="section-heading">Alterar Senha</h2>
					<hr class="my-4">
				</div>
			</div>
		</div>
	  	
		<div class="container">
			<div class="row">  
				<div class="col-lg-8">
					@if(count($errors) > 0)					
						<div class="alert alert-danger">
							<ul>
								@foreach ($errors->all() as $error)
									<li>{!! $error !!}</li>
								@endforeach
							</ul>
						</div>
					@endif
				</div>
					
				<div class="col-lg-8">
					@if(!empty($msgSenha))
						@component('components.alert')
							@slot('type',$msgSenha['type'])
							@slot('text',$msgSenha['text'])
						@endcomponent	
					@endif
				</div>
			</div>
		</div>		

		<div class="container">
			<div class="row">
				<div class="col-lg-12 text-left text-white">
				<h3 class="section-heading"><span class="cg_blue2">{{$perfilLogado->usuario[0]->NO_USUARIO}}</span></h3>
				
				</div>
			</div>
		</div>
		
		<div class="container text-white" style="margin-top:20px">
			
			<form name="buscarInscricao" role="form" style="min-width: 100%;" method="post" action="{{Request::url()}}">	
				<div class="row">				
					<input type="hidden" name="_token" value="{{ csrf_token() }}" />
					<input type="hidden" name="CD_USUARIO" value="{{$perfilLogado->usuario[0]->CD_USUARIO}}" />
					<div class="col-lg-4" style="display: inline-block;">
						<div class="form-group">
							<label>Nova Senha</label>
							<input type="password" class="form-control" name="TX_SENHA" id="TX_SENHA">
						</div>							
					</div>
					<div class="col-lg-4" style="display: inline-block;">
						<div class="form-group">
							<label>Repita a Nova Senha</label>
							<input type="password" class="form-control" name="TX_SENHA_confirmation" id="TX_SENHA_confirmation">
						</div>							
					</div>				
				</div>
				<div class="row">																				
					<div class="col-md-2">
						<button type="submit" class="btn btn-primary btn-xl">Enviar</button> <br/><br/>
					</div>					
				</div>
				<div class="row">																					
					
				</div>
			</form>			
		</div>
       
    </section>
	
@endsection    


