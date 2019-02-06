<?php 
//use App\Classes\URLify;
use App\Classes\MyUtil; 
?>


@extends('layout.principal2')	

@section('title', 'JUCAAD | Inscrições')

@section('conteudo')

	<section class="cg_bg-new-blue" id="usuarios">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 text-center text-white">
					<h2 class="section-heading">Inscrições</h2>
					<hr class="my-4">
				</div>
			</div>
		</div>
	  	<div class="container">
        	@if(count($eventoList)>1)
				<div class="row">
					<div class="col-lg-12 text-left text-white">
					<h3 class="section-heading">Selecione o Evento: 
						<span class=""><select><option>Selecione</option>
							@foreach($eventoList as $evento)
								<option value="{{$evento->CD_EVENTO}}">{{$evento->NO_EVENTO}}</option>
							@endforeach	
						</select></span></h3>
					
					</div>
				</div>				
			@endif	
		</div>
	
    </section>
	
	
    

	@if(count($errors) > 0)
	<script>		
		window.location.href='#cadastro';					
	</script>
	@endif
	
	@if(!empty($msgCadastro))
	<script>		
		window.location.href='#cadastro';					
	</script>
	@endif
	
@endsection    


