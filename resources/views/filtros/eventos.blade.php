<?php 
//use App\Classes\URLify;
use App\Classes\MyUtil; 
?>


<?php 
$uri = explode('/',$_SERVER["REQUEST_URI"]); 
$path = $uri[count($uri)-1]
?>
@extends('layout.principal')	

@section('title', 'JUCAAD | Filtro de Eventos')

@section('conteudo')

	<section class="cg_bg-new-blue" id="usuarios">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 text-center text-white">
					<h2 class="section-heading">Filtro de Eventos</h2>
					<hr class="my-4">
				</div>
			</div>
		</div>
	  	<div class="container">
        	@if(count($eventoList)>1)
				<div class="row">
					<div class="col-lg-12 text-left text-white">
						<h3 class="section-heading">Selecione o Evento: 
							<span class=""><select path="{{$path}}"><option value=''>Selecione</option>
								@foreach($eventoList as $evento)
									<option value="{{$evento->CD_EVENTO}}">{{$evento->NO_EVENTO}}</option>
								@endforeach	
							</select></span>
						</h3>
					</div>
				</div>				
			@endif	
		</div>
	
    </section>
	
	<script type="text/javascript" src="{{url('js/filtro-eventos.js')}}"></script>

	
	
@endsection    


