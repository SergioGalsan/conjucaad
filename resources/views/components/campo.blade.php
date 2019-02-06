<?php 
use App\Classes\MyUtil;
use Illuminate\Support\Facades\DB; 
?>

{{-- Verificar e seguir de acordo com a página --}}
@if($pag=='form')	
	@if($participacaoBloqueada) 
		@php($disabled = "disabled") {{-- Essa var só recebe esse valor quando é proibido novas participações e houve participação anterior --}}
	@elseif(strpos($campo->ST_READONLY,'No formulário') !== false)	
		@php($readonly = "readonly")
	@endif
@elseif($pag=='resultado_individual')
	@php($disabled = "disabled") {{-- Todos os campos começam em disabled na tela de resultado individual --}}
	@if(strpos($pesquisa->DS_MATRICULAS_ADM,$matricula) !== false) {{-- Verificar se o campo vai ser editavel nessa tela --}}
		@php($editClass = "editavel")
	@elseif(@$participantePreenchimento->NR_MATRICULA==$matricula)
		@if(strpos($campo->ST_READONLY,'Na Consulta/Edição')===false)
			@if($pesquisa->ST_EDITAR_RESPOSTA=='A qualquer tempo')
				@php($editClass = "editavel")
			@elseif($pesquisa->ST_EDITAR_RESPOSTA=='Período específico')
				@if(strtotime(date("Y-m-d H:i:s"))>strtotime($pesquisa->DT_INICIO_EDICAO) && strtotime(date("Y-m-d H:i:s"))<strtotime($pesquisa->DT_FIM_EDICAO))
					@php($editClass = "editavel")
				@endif
			@endif
		@endif
	@endif	
@endif

{{-- Definições gerais do campo (required etc) --}}
@if($campo->ST_OBRIGATORIO == 'Sim' && $pag != 'criar-editar' ) @php($required = "req='1'") @endif
@php($value='')
@if(!empty($oldValue))
	@php($value = $oldValue)
@elseif(empty($participantePreenchimento)) 
	@php($value = $campo->DS_VALOR_INICIAL)
@elseif($participacaoBloqueada)
	@php($value = @$participantePreenchimento->preenchimento[$index]->TX_PREENCHIMENTO
				 .@$participantePreenchimento->preenchimento[$index]->NR_PREENCHIMENTO
				 .MyUtil::convDt(@$participantePreenchimento->preenchimento[$index]->DT_PREENCHIMENTO))
@endif

{{-- Retira os decimais de num. que não necessitam dessa parte e adiciona zeros a esquerda dos números que necessitam por questões de mascara --}}
@if(in_array($campo->CD_MASCARA,array("1","2","3","4","5","9","12")))
	@php($value = MyUtil::addZeros(str_replace('.00','',$value),$campo->CD_MASCARA,@$participantePreenchimento->preenchimento[$index]->DS_COMPLEMENTO))
@endif


@switch($campo->CD_TIPO_CAMPO)
	@case(1) {{-- Texto Simples --}}
	
		{{-- Associa as macaras (do BD) com os types do HTML5 --}}
		@php($arrTypes = array(""=>"text","1"=>"text","2"=>"text","3"=>"text","4"=>"text","5"=>"number","6"=>"text","7"=>"text","8"=>"text",
		"9"=>"text","10"=>"text","11"=>"text","12"=>"text","13"=>"email"))
		@php($arrMaskIconsClass = array(""=>"","1"=>"","2"=>"","3"=>"","4"=>"","5"=>"","6"=>"fa fa-calendar","7"=>"fa fa-clock-o",
			"8"=>"fa fa-calendar","9"=>"icon-location","10"=>"fa fa-phone","11"=>"fa fa-money","12"=>"fa fa-credit-card","13"=>"fa fa-envelope"))
		{{-- append icon input --}}
		@if($append = in_array($campo->CD_MASCARA,array("6","7","8","9","10","11","12","13"))) 
			<div class="input-group">
		@endif
		
		<input type='{{$arrTypes[$campo->CD_MASCARA]}}' id='campo{{$index}}' name='campo{{$index}}' value='{{@$value}}' {!!@$required!!} {{@$disabled}}
		class='{{@$campo->mascara[0]->NO_CLASSE_MASCARA}} {{@$editClass}}' maxlength='{{@$campo->QT_MAX_CARACTERE}}'  {{@$readonly}} title='{{@$title}}' 
		placeholder='{{@$campo->DS_PLACEHOLDER}}' />
		
		@if($append)
			<span class='input-group-addon'><i class='{{$arrMaskIconsClass[$campo->CD_MASCARA]}}'></i></span>
			</div>
		@endif
		@break
		
	@case(2) {{-- Texto Grande --}}
	
		<textarea rows='3' id='campo{{$index}}' name='campo{{$index}}' {!!@$required!!} {{@$disabled}} {{@$readonly}} title='{{@$title}}' 
			maxlength='{{@$campo->QT_MAX_CARACTERE}}' placeholder='{{@$campo->DS_PLACEHOLDER}}'>{{@$value}}</textarea>
		@break
	
	@case(3) {{-- Caixa de seleção --}}	
		
		<select id='campo{{$index}}' name='campo{{$index}}' {!!@$required!!} {{@$disabled}} {{@$readonly}} title='{{@$title}}' >
			<option value=''>Selecione</option>
			@php($opc = explode(';',$campo->DS_OPC_RESPOSTA))
			@for($j=0;$j<count($opc);$j++)
				@php($posi = strpos($opc[$j],'[')) {{-- Validação para quantidade máxima de respostas para campos select e radio --}}
				@if($posi === false)
					@php($selected = ($value == $opc[$j]) ? "selected":"")
					<option value='{{$opc[$j]}}' {{$selected}} >{{$opc[$j]}}</option>
				@else
					@php($posf = strpos($opc[$j],']') - $posi - 1)
					@php($max = substr($opc[$j],$posi+1,$posf)) {{-- Max de respostas permitidas para o item --}}
					@php($qt_res_bd = DB::table('TB_SEP_PREENCHIMENTO')->where([['CD_CAMPO',$campo->CD_CAMPO],['TX_PREENCHIMENTO',substr($opc[$j],0,$posi)]])->count('TX_PREENCHIMENTO'))
					@if($pag=='preenchimento-individual') {{-- Não levar em consideração o limite maximo de resposta nesta pagina --}}
						@php($qt_res_bd = 0)
					@endif
					@if($pag=='form' && $participacaoBloqueada) {{-- Não levar em consideração o limite maximo de resposta nesta condição --}}
						@php($qt_res_bd = 0)
					@endif
					@if($qt_res_bd<$max)
						@php($opc[$j] = substr($opc[$j],0,$posi))
						@php($selected = ($value == $opc[$j]) ? "selected":"")
						<option value='{{$opc[$j]}}' {{$selected}}>{{$opc[$j]}}</option>
					@endif	
				@endif
			@endfor
		</select>
		@break
		
	@case(4) {{-- Radio Button --}}
		
		<div class="vd_radio radio-info">
			@php($opc = explode(';',$campo->DS_OPC_RESPOSTA))
			@for($j=0;$j<count($opc);$j++)
				@php($posi = strpos($opc[$j],'[')) {{-- Validação para quantidade máxima de respostas para campos select e radio --}}
				@if($posi === false)
					@php($checked = ($value == $opc[$j]) ? "checked":"")
					<input type="radio" value="{{$opc[$j]}}" id='campo{{$index}}_check{{$j}}' name='campo{{$index}}' 
					{!!@$required!!} {{@$disabled}} {{@$readonly}} title='{{@$title}}' {{$checked}} />
					<label for="campo{{$index}}_check{{$j}}">{{$opc[$j]}}</label> <br/>
				@else
					@php($posf = strpos($opc[$j],']') - $posi - 1)
					@php($max = substr($opc[$j],$posi+1,$posf)) {{-- Max de respostas permitidas para o item --}}
					@php($qt_res_bd = DB::table('TB_SEP_PREENCHIMENTO')->where([['CD_CAMPO',$campo->CD_CAMPO],['TX_PREENCHIMENTO',substr($opc[$j],0,$posi)]])->count('TX_PREENCHIMENTO'))
					@if($pag=='preenchimento-individual') {{-- Não levar em consideração o limite maximo de resposta nesta pagina --}}
						@php($qt_res_bd = 0)
					@endif
					@if($pag=='form' && $participacaoBloqueada) {{-- Não levar em consideração o limite maximo de resposta nesta condição --}}
						@php($qt_res_bd = 0)
					@endif
					@if($qt_res_bd<$max)
						@php($opc[$j] = substr($opc[$j],0,$posi))
						@php($checked = ($value == $opc[$j]) ? "checked":"")
						<input type="radio" value="{{$opc[$j]}}" id='campo{{$index}}_check{{$j}}' name='campo{{$index}}' 
							{!!@$required!!} {{@$disabled}} {{@$readonly}} title='{{@$title}}' {{$checked}} />
						<label for="campo{{$index}}_check{{$j}}">{{$opc[$j]}}</label> <br/>
					@endif	
				@endif
			@endfor
			@if($campo->ST_OBRIGATORIO == 'Sim')
				<script type="text/javascript">
				$(document).on('submit','#f_pesquisa',function (e) {					
					var checked = $("[name=campo{{$index}}]:checked").length;				
					if(!checked) {
						e.preventDefault();
						alert("Selecione ao menos uma opção do campo {{$campo->NO_CAMPO}}");
						return false;
					}	
				});					
				</script>
			@endif
		</div>
		
		@break
		
	@case(5) {{-- Checkbox --}}

		<div class="vd_checkbox checkbox-info">
			@php($opc = explode(';',$campo->DS_OPC_RESPOSTA))
			@if(strpos($value,';')!==false) 
				@php($valorInicial = explode(';',$value)) 
			@else 
				@php($valorInicial = array($value))
			@endif	
			@for($j=0;$j<count($opc);$j++)
				@php($posi = strpos($opc[$j],'[')) {{-- Validação para quantidade máxima de respostas para campos select e radio --}}
				@if($posi === false)					
					@php($checked = (in_array($opc[$j],$valorInicial)) ? "checked":"")
					<input type="checkbox" value="{{$opc[$j]}}" id='campo{{$index}}_check{{$j}}' name='campo{{$index}}[]' 
						{!!@$required!!} {{@$disabled}} {{@$readonly}} title='{{@$title}}' {{$checked}} class="campo{{$index}}" />
					<label for="campo{{$index}}_check{{$j}}">{{$opc[$j]}}</label> <br/>
				@else
					@php($posf = strpos($opc[$j],']') - $posi - 1)
					@php($max = substr($opc[$j],$posi+1,$posf)) {{-- Max de respostas permitidas para o item --}}
					@php($qt_res_bd = DB::table('TB_SEP_PREENCHIMENTO')->where([['CD_CAMPO',$campo->CD_CAMPO],['TX_PREENCHIMENTO','like','%'.substr($opc[$j],0,$posi).'%']])->count('TX_PREENCHIMENTO'))
					@if($pag=='preenchimento-individual') {{-- Não levar em consideração o limite maximo de resposta nesta pagina --}}
						@php($qt_res_bd = 0)
					@endif
					@if($pag=='form' && $participacaoBloqueada) {{-- Não levar em consideração o limite maximo de resposta nesta condição --}}
						@php($qt_res_bd = 0)
					@endif
					@if($qt_res_bd<$max)
						@php($opc[$j] = substr($opc[$j],0,$posi))
						@php($checked = (in_array($opc[$j],$valorInicial)) ? "checked":"")
						<input type="checkbox" value="{{$opc[$j]}}" id='campo{{$index}}_check{{$j}}' name='campo{{$index}}[]' 
							{!!@$required!!} {{@$disabled}} {{@$readonly}} title='{{@$title}}' {{$checked}} class="campo{{$index}}" />
						<label for="campo{{$index}}_check{{$j}}">{{$opc[$j]}}</label> <br/>
					@endif
				@endif
			@endfor
			@if($campo->ST_OBRIGATORIO == 'Sim')
				<script type="text/javascript">
				$(document).on('submit','#f_pesquisa',function (e) {					
					  var checked = $(".campo{{$index}}:checked").length;				
					  if(!checked) {
						e.preventDefault();
						alert("Selecione ao menos um checkbox do campo {{$campo->NO_CAMPO}}");
						return false;
					  }	
				});					
				</script>
			@endif	
		</div>
		@break

	@case(6) {{-- Emoji --}}
		
		@if(!$participacaoBloqueada) @php($class6 = "emoji") @endif {{-- Equivalente a um disabled --}}
		<input type="hidden" id='campo{{$index}}' name='campo{{$index}}' value="{{@$value}}" {{@$required}} />
		
		<a href="javascript:void(0)" class="{{@$class6}}" title="Indiferente">
			<svg width="30px" height="30px" viewBox="0 0 49 50" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
				<g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
					<g id="Group-Copy-2" transform="translate(-47.000000, -22.000000)">
						<g id="Group-3" transform="translate(47.000000, 22.000000)">
							<g id="Group-46">
								<path d="M50,25.0002888 C50,38.8082722 38.8078238,50 25.0005777,50 C11.1921762,50 0,38.8082722 0,25.0002888 C0,11.1923055 11.1921762,0 25.0005777,0 C38.8078238,0 50,11.1923055 50,25.0002888" id="Fill-1" fill="#CCCCCC"/>
								<path d="M37.0624665,32.9787234 L14.0013633,32.9787234 C13.3194379,32.9787234 12.7659574,32.5027268 12.7659574,31.9146423 C12.7659574,31.3275631 13.3194379,30.8510638 14.0013633,30.8510638 L37.0624665,30.8510638 C37.7443919,30.8510638 38.2978723,31.3275631 38.2978723,31.9146423 C38.2978723,32.5027268 37.7443919,32.9787234 37.0624665,32.9787234" id="Fill-3" fill="#61240D"/>
								<path d="M37.2340426,21.8088017 C37.2340426,23.2769963 36.0436661,24.4680851 34.5744681,24.4680851 C33.10527,24.4680851 31.9148936,23.2769963 31.9148936,21.8088017 C31.9148936,20.340025 33.10527,19.1489362 34.5744681,19.1489362 C36.0436661,19.1489362 37.2340426,20.340025 37.2340426,21.8088017" id="Fill-5" fill="#61240D"/>
								<path d="M17.0212766,21.8088017 C17.0212766,23.2769963 15.8298664,24.4680851 14.3625752,24.4680851 C12.8935379,24.4680851 11.7021277,23.2769963 11.7021277,21.8088017 C11.7021277,20.340025 12.8935379,19.1489362 14.3625752,19.1489362 C15.8298664,19.1489362 17.0212766,20.340025 17.0212766,21.8088017" id="Fill-7" fill="#61240D"/>
							</g>
						</g>
					</g>
				</g>
			</svg>                       
		</a>
		<a href="javascript:void(0)" class="{{@$class6}}" title="Curti">
			<svg width="30px" height="30px" viewBox="0 0 49 50" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
				<g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
					<g id="Group-Copy-2" transform="translate(-173.000000, -22.000000)">
						<g id="Group-3" transform="translate(47.000000, 22.000000)">
							<g id="Group-45" transform="translate(126.000000, 0.000000)">
								<g id="Group-47">
									<path d="M48.6575696,24.8421591 C48.6575696,38.4228409 37.8198784,49.4319318 24.4499432,49.4319318 C11.0788893,49.4319318 0.241198062,38.4228409 0.241198062,24.8421591 C0.241198062,11.2609091 11.0788893,0.252386364 24.4499432,0.252386364 C37.8198784,0.252386364 48.6575696,11.2609091 48.6575696,24.8421591" id="Fill-43" fill="#CCCCCC"/>
									<path d="M37.673053,21.824833 C37.9165867,21.9601139 38.2371399,21.8062746 38.2371399,21.5488989 L38.2371399,21.54255 C38.2371399,20.0007381 36.8491606,18.75 35.1368313,18.75 C33.424502,18.75 32.0365226,20.0007381 32.0365226,21.54255 L32.0365226,21.5488989 C32.0365226,21.8062746 32.3576183,21.9601139 32.601152,21.824833 C33.3382617,21.4145948 34.2066302,21.1772427 35.1368313,21.1772427 C36.0675747,21.1772427 36.9359432,21.4145948 37.673053,21.824833" id="Fill-46" fill="#61240D"/>
									<path d="M15.9710898,21.824833 C16.2129117,21.9601139 16.5328106,21.8062746 16.5349794,21.5488989 L16.5349794,21.54255 C16.5349794,20.0007381 15.1469434,18.75 13.4335864,18.75 C11.721856,18.75 10.3343621,20.0007381 10.3343621,21.54255 L10.3343621,21.5488989 C10.3354465,21.8062746 10.6553455,21.9601139 10.898794,21.824833 C11.6361881,21.4145948 12.5037107,21.1772427 13.4335864,21.1772427 C14.3645465,21.1772427 15.2331534,21.4145948 15.9710898,21.824833" id="Fill-48" fill="#61240D"/>
									<path d="M23.9020492,36.4583333 C19.8062024,36.4583333 15.7533329,34.9165103 12.7833774,32.2276812 C12.2987522,31.7893629 12.2716086,31.0520411 12.721739,30.5806838 C13.1707383,30.1093265 13.9284954,30.0812433 14.4131207,30.5206628 C16.9453866,32.8119219 20.4039257,34.1274274 23.9020492,34.1274274 C27.2751993,34.1274274 30.6449565,32.8912157 33.1472514,30.7365181 C33.643752,30.3108648 34.3986816,30.3560182 34.8369367,30.8372872 C35.2746262,31.3196575 35.2276905,32.055878 34.7323209,32.4826326 C31.7561451,35.046464 27.910245,36.4583333 23.9020492,36.4583333" id="Fill-50" fill="#61240D"/>
								</g>
							</g>
						</g>
					</g>
				</g>
			</svg>                        
		</a>
		<a href="javascript:void(0)" class="{{@$class6}}" title="Curti Muito">
			<svg width="30px" height="30px" viewBox="0 0 49 50" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
				<g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
					<g id="Group-Copy-2" transform="translate(-298.000000, -22.000000)">
						<g id="Group-3" transform="translate(47.000000, 22.000000)">
							<g id="Group-11" transform="translate(251.000000, 0.000000)">
								<g id="Group-48">
									<path d="M48.9246663,24.8421591 C48.9246663,38.4228409 38.0869751,49.4319318 24.7164805,49.4319318 C11.3459859,49.4319318 0.507735345,38.4228409 0.507735345,24.8421591 C0.507735345,11.2609091 11.3459859,0.252386364 24.7164805,0.252386364 C38.0869751,0.252386364 48.9246663,11.2609091 48.9246663,24.8421591" id="Fill-9" fill="#CCCCCC"/>
									<path d="M37.2037037,16.2639845 C37.2037037,17.0298317 37.0498754,17.7413026 36.7801186,18.333718 C36.6078978,18.7154969 36.1358233,18.8809153 35.8075449,18.6313567 C35.451399,18.3617648 35.0462064,18.2100835 34.6159331,18.2100835 C34.1917906,18.2100835 33.7888274,18.3611924 33.4337962,18.6313567 C33.1055177,18.8809153 32.6323285,18.7154969 32.4601078,18.333718 C32.1909083,17.7413026 32.0365226,17.0298317 32.0365226,16.2639845 C32.0365226,14.1879547 33.1930215,12.5 34.6159331,12.5 C36.0472049,12.5 37.2037037,14.1879547 37.2037037,16.2639845" id="Fill-12" fill="#61240D"/>
									<path d="M17.5684156,16.2639845 C17.5684156,17.0298317 17.41403,17.7413026 17.1459452,18.333718 C16.9731671,18.7154969 16.4999779,18.8809153 16.1722568,18.6313567 C15.8172256,18.3617648 15.4114756,18.2100835 14.9817597,18.2100835 C14.5570599,18.2100835 14.152982,18.3611924 13.7985081,18.6313567 C13.4702296,18.8809153 12.9970405,18.7154969 12.8242623,18.333718 C12.5561775,17.7413026 12.4012346,17.0298317 12.4012346,16.2639845 C12.4012346,14.1879547 13.5577334,12.5 14.9817597,12.5 C16.4124741,12.5 17.5684156,14.1879547 17.5684156,16.2639845" id="Fill-14" fill="#61240D"/>
									<path d="M11.4019558,26.0416667 C10.6814358,26.0416667 10.184041,26.7232131 10.3756926,27.4075277 C11.6481241,31.9768229 17.1498165,35.4166667 23.7609524,35.4166667 C30.4136783,35.4166667 35.9423481,31.9330843 37.1709414,27.3211579 C37.344046,26.6700624 36.8073092,26.0416667 36.1233211,26.0416667 L11.4019558,26.0416667 Z" id="Fill-16" fill="#61240D"/>
									<path d="M24.8032907,28.125 C28.3204728,28.125 31.5446478,27.3429448 34.1033951,26.0416667 L15.5015432,26.0416667 C18.0602905,27.3429448 21.2855608,28.125 24.8032907,28.125" id="Fill-18" fill="#FFFFFF"/>
									<path d="M25.3688827,33.3333333 C23.5157581,33.3333333 21.8494666,33.9445248 20.6687243,34.9194655 C22.0672577,35.2396575 23.5749581,35.4166667 25.1494623,35.4166667 C26.8526859,35.4166667 28.4755277,35.2062945 29.9696502,34.8337411 C28.7948822,33.9079181 27.1682385,33.3333333 25.3688827,33.3333333" id="Fill-20" fill="#ED312B"/>
								</g>
							</g>
						</g>
					</g>
				</g>
			</svg>                        
		</a cores="CCCCCC,70DBDB,FFCF00">
		<a href="javascript:void(0)" class="{{@$class6}}" title="Amei">
			<svg width="30px" height="30px" viewBox="0 0 49 50" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
				<g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
					<g id="Group-Copy-2" transform="translate(-423.000000, -22.000000)">
						<g id="Group-3" transform="translate(47.000000, 22.000000)">
							<g id="Group-11-Copy-2" transform="translate(376.000000, 0.000000)">
								<g id="Group-48">
									<path d="M48.9246663,24.8421591 C48.9246663,38.4228409 38.0869751,49.4319318 24.7164805,49.4319318 C11.3459859,49.4319318 0.507735345,38.4228409 0.507735345,24.8421591 C0.507735345,11.2609091 11.3459859,0.252386364 24.7164805,0.252386364 C38.0869751,0.252386364 48.9246663,11.2609091 48.9246663,24.8421591" id="Fill-9" fill="#CCCCCC"/>
									<path d="M15.2025782,20.1219512 C15.2025782,20.1219512 16.4362733,19.1572463 17.7844543,17.7352551 C18.6294117,16.8418583 19.7185139,15.7961931 19.961412,14.6074037 C20.4894374,12.0388946 18.7964721,10.9668933 17.1785222,10.9756631 C15.6239428,10.9814918 15.1658398,12.5959107 15.1658398,12.5959107 C15.1658398,12.5959107 14.6943677,10.9857163 13.1391251,10.9943257 C11.5210426,11.0034698 9.83996091,12.0895883 10.3906395,14.6542473 C10.6463761,15.8409512 11.7432769,16.8750926 12.5992691,17.7596661 C13.9606335,19.1692246 15.2025782,20.1219512 15.2025782,20.1219512" id="Fill-1" fill="#ED312B" transform="translate(15.171888, 15.548780) rotate(-7.000000) translate(-15.171888, -15.548780) "/>
									<path d="M33.9556646,20.1219512 C33.9556646,20.1219512 35.1893597,19.1572463 36.5375408,17.7352551 C37.3824982,16.8418583 38.4716003,15.7961931 38.7144984,14.6074037 C39.2425238,12.0388946 37.5495586,10.9668933 35.9316086,10.9756631 C34.3770292,10.9814918 33.9189262,12.5959107 33.9189262,12.5959107 C33.9189262,12.5959107 33.4474541,10.9857163 31.8922115,10.9943257 C30.274129,11.0034698 28.5930473,12.0895883 29.1437259,14.6542473 C29.3994625,15.8409512 30.4963633,16.8750926 31.3523555,17.7596661 C32.71372,19.1692246 33.9556646,20.1219512 33.9556646,20.1219512" id="Fill-1-Copy" fill="#ED312B" transform="translate(33.924975, 15.548780) scale(-1, 1) rotate(-7.000000) translate(-33.924975, -15.548780) "/>
									<path d="M11.4019558,26.0416667 C10.6814358,26.0416667 10.184041,26.7232131 10.3756926,27.4075277 C11.6481241,31.9768229 17.1498165,35.4166667 23.7609524,35.4166667 C30.4136783,35.4166667 35.9423481,31.9330843 37.1709414,27.3211579 C37.344046,26.6700624 36.8073092,26.0416667 36.1233211,26.0416667 L11.4019558,26.0416667 Z" id="Fill-16" fill="#61240D"/>
									<path d="M24.8032907,28.125 C28.3204728,28.125 31.5446478,27.3429448 34.1033951,26.0416667 L15.5015432,26.0416667 C18.0602905,27.3429448 21.2855608,28.125 24.8032907,28.125" id="Fill-18" fill="#FFFFFF"/>
									<path d="M25.3688827,33.3333333 C23.5157581,33.3333333 21.8494666,33.9445248 20.6687243,34.9194655 C22.0672577,35.2396575 23.5749581,35.4166667 25.1494623,35.4166667 C26.8526859,35.4166667 28.4755277,35.2062945 29.9696502,34.8337411 C28.7948822,33.9079181 27.1682385,33.3333333 25.3688827,33.3333333" id="Fill-20" fill="#ED312B"/>
								</g>
							</g>
						</g>
					</g>
				</g>
			</svg>                        
		</a>
		
		@if(!empty($value)) 
			<script>$("#campo{{$index}}").parent("div").find('[title="{{$value}}"]').find("path").first().attr("fill","#FFCF00");</script>		
		@endif
		@break

	@case(7) {{-- Escala de 1 a 10 --}}
		
		@if(!$participacaoBloqueada) @php($class7 = "escala") @endif {{-- Equivalente a um disabled --}}
		<input type="hidden" id='campo{{$index}}' name='campo{{$index}}' value="{{@$value}}" {{@$required}} />
		
		<a href="javascript:void(0)" class="{{@$class7}}" title="Escala 1">
			<svg width="55" height="55" xmlns="http://www.w3.org/2000/svg">
				<!-- Created with Method Draw - http://github.com/duopixel/Method-Draw/ -->						
				<g>
					<ellipse ry="19.5" rx="19.5" cy="27" cx="27.5" stroke-width="1.5" stroke="#000" fill="#D62027"/>
					<text xml:space="preserve" text-anchor="start" font-family="Helvetica, Arial, sans-serif" font-size="24" y="35.5" x="20" stroke-width="0" stroke="#000" fill="#FFF">1</text>
				</g>
			</svg>                       
		</a>
		<a href="javascript:void(0)" class="{{@$class7}}" title="Escala 2">
			<svg width="55" height="55" xmlns="http://www.w3.org/2000/svg">
				<!-- Created with Method Draw - http://github.com/duopixel/Method-Draw/ -->						
				<g>
					<ellipse ry="19.5" rx="19.5" cy="27" cx="27.5" stroke-width="1.5" stroke="#000" fill="#F05223"/>
					<text xml:space="preserve" text-anchor="start" font-family="Helvetica, Arial, sans-serif" font-size="24" y="35.5" x="20" stroke-width="0" stroke="#000" fill="#FFF">2</text>
				</g>
			</svg>                       
		</a>
		<a href="javascript:void(0)" class="{{@$class7}}" title="Escala 3">
			<svg width="55" height="55" xmlns="http://www.w3.org/2000/svg">
				<!-- Created with Method Draw - http://github.com/duopixel/Method-Draw/ -->						
				<g>
					<ellipse ry="19.5" rx="19.5" cy="27" cx="27.5" stroke-width="1.5" stroke="#000" fill="#F36F21"/>
					<text xml:space="preserve" text-anchor="start" font-family="Helvetica, Arial, sans-serif" font-size="24" y="35.5" x="20" stroke-width="0" stroke="#000" fill="#FFF">3</text>
				</g>
			</svg>                       
		</a>
		<a href="javascript:void(0)" class="{{@$class7}}" title="Escala 4">
			<svg width="55" height="55" xmlns="http://www.w3.org/2000/svg">
				<!-- Created with Method Draw - http://github.com/duopixel/Method-Draw/ -->						
				<g>
					<ellipse ry="19.5" rx="19.5" cy="27" cx="27.5" stroke-width="1.5" stroke="#000" fill="#FAA823"/>
					<text xml:space="preserve" text-anchor="start" font-family="Helvetica, Arial, sans-serif" font-size="24" y="35.5" x="20" stroke-width="0" stroke="#000" fill="#FFF">4</text>
				</g>
			</svg>                       
		</a>
		<a href="javascript:void(0)" class="{{@$class7}}" title="Escala 5">
			<svg width="55" height="55" xmlns="http://www.w3.org/2000/svg">
				<!-- Created with Method Draw - http://github.com/duopixel/Method-Draw/ -->						
				<g>
					<ellipse ry="19.5" rx="19.5" cy="27" cx="27.5" stroke-width="1.5" stroke="#000" fill="#FFCA27"/>
					<text xml:space="preserve" text-anchor="start" font-family="Helvetica, Arial, sans-serif" font-size="24" y="35.5" x="20" stroke-width="0" stroke="#000" fill="#FFF">5</text>
				</g>
			</svg>                       
		</a>
		<a href="javascript:void(0)" class="{{@$class7}}" title="Escala 6">
			<svg width="55" height="55" xmlns="http://www.w3.org/2000/svg">
				<!-- Created with Method Draw - http://github.com/duopixel/Method-Draw/ -->						
				<g>
					<ellipse ry="19.5" rx="19.5" cy="27" cx="27.5" stroke-width="1.5" stroke="#000" fill="#ECDB12"/>
					<text xml:space="preserve" text-anchor="start" font-family="Helvetica, Arial, sans-serif" font-size="24" y="35.5" x="20" stroke-width="0" stroke="#000" fill="#FFF">6</text>
				</g>
			</svg>                       
		</a>
		<a href="javascript:void(0)" class="{{@$class7}}" title="Escala 7">
			<svg width="55" height="55" xmlns="http://www.w3.org/2000/svg">
				<!-- Created with Method Draw - http://github.com/duopixel/Method-Draw/ -->						
				<g>
					<ellipse ry="19.5" rx="19.5" cy="27" cx="27.5" stroke-width="1.5" stroke="#000" fill="#E8E73D"/>
					<text xml:space="preserve" text-anchor="start" font-family="Helvetica, Arial, sans-serif" font-size="24" y="35.5" x="20" stroke-width="0" stroke="#000" fill="#FFF">7</text>
				</g>
			</svg>                       
		</a>
		<a href="javascript:void(0)" class="{{@$class7}}" title="Escala 8">
			<svg width="55" height="55" xmlns="http://www.w3.org/2000/svg">
				<!-- Created with Method Draw - http://github.com/duopixel/Method-Draw/ -->						
				<g>
					<ellipse ry="19.5" rx="19.5" cy="27" cx="27.5" stroke-width="1.5" stroke="#000" fill="#C5D92D"/>
					<text xml:space="preserve" text-anchor="start" font-family="Helvetica, Arial, sans-serif" font-size="24" y="35.5" x="20" stroke-width="0" stroke="#000" fill="#FFF">8</text>
				</g>
			</svg>                       
		</a>
		<a href="javascript:void(0)" class="{{@$class7}}" title="Escala 9">
			<svg width="55" height="55" xmlns="http://www.w3.org/2000/svg">
				<!-- Created with Method Draw - http://github.com/duopixel/Method-Draw/ -->						
				<g>
					<ellipse ry="19.5" rx="19.5" cy="27" cx="27.5" stroke-width="1.5" stroke="#000" fill="#AFD136"/>
					<text xml:space="preserve" text-anchor="start" font-family="Helvetica, Arial, sans-serif" font-size="24" y="35.5" x="20" stroke-width="0" stroke="#000" fill="#FFF">9</text>
				</g>
			</svg>                       
		</a>
		<a href="javascript:void(0)" class="{{@$class7}}" title="Escala 10">
			<svg width="55" height="55" xmlns="http://www.w3.org/2000/svg">
				<!-- Created with Method Draw - http://github.com/duopixel/Method-Draw/ -->						
				<g>
					<ellipse ry="19.5" rx="19.5" cy="27" cx="27.5" stroke-width="1.5" stroke="#000" fill="#64B64D" />
					<text fill="#FFF" stroke="#000" stroke-width="0" x="13" y="35.5" font-size="24" font-family="Helvetica, Arial, sans-serif" text-anchor="start" xml:space="preserve">10</text>
				</g>
			</svg>                       
		</a>
		
		@if(!empty($value)) 
			<script>$("#campo{{$index}}").parent("div").find('[title="{{$value}}"]').find("ellipse").first().attr("stroke-width","3.5").attr("stroke","#119ADE");</script>
		@endif
		@break
	
	@case(8) {{-- Label --}}
		
		{!!@$campo->DS_OPC_RESPOSTA!!}
		@break
	
	@case(9) {{-- Anexo --}}
		
		@break
	
@endswitch

