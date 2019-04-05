<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\InscritosRequest;
use App\Http\Requests\AlterarStatusRequest;
use App\Usuario;
use App\Evento;
use App\Congregacao;
use App\Inscricao;
use App\Status;
use App\Classes\MyUtil;
use Session;

class CongressoController extends Controller{
    
	public function __construct(){
		$this->middleware('IsAdmPesquisa', 
			['only' => ['criar', 'editarLista','editar']]);
		 $this->middleware('AutorizadorPostForm', 
			['only' => ['postForm']]);	
			
	}
		
	public function index(){
		$perfilLogado = Session::get('perfilLogado');	
		$msgHome = Session::get('msgHome');	Session::forget('msgHome');
		$msgCadastro = Session::get('msgCadastro');	Session::forget('msgCadastro');    	
		$congregacao = Congregacao::all();
		return view('congresso.home')->with('perfilLogado',$perfilLogado)
									 ->with('msgHome',$msgHome)
									 ->with('msgCadastro',$msgCadastro)
									 ->with('congregacao',$congregacao);
			
  	}
	
	public function getFiltroEventosAtivosEncerrados($cdEvento = null){
		//dd(Request::getPathInfo());	
		$perfilLogado = Session::get('perfilLogado');
		$eventoList = Evento::with('status')->whereIn('CD_STATUS',[1,3])/*->sortable()*/->get(); 
		
		if(!empty($cdEvento)){
			if(strpos(Request::url(),"/inscritos") !== false)	
				return $this->getInscritos($cdEvento);
			elseif(strpos(Request::url(),"/pedidos") !== false)
				return $this->getPedidos($cdEvento);
		}elseif($eventoList->isEmpty())
			return view("filtros.eventos");
		elseif(count($eventoList)==1){
			if(strpos(Request::url(),"/inscritos") !== false)	
				return $this->getInscritos($eventoList[0]->CD_EVENTO);
			elseif(strpos(Request::url(),"/pedidos") !== false)
				return $this->getPedidos($eventoList[0]->CD_EVENTO);	
		}	
		return view("filtros.eventos")->with('perfilLogado',$perfilLogado)
									  ->with('eventoList',$eventoList);									  	
	}  

	public function getInscritos($cdEvento){		
		$perfilLogado = Session::get('perfilLogado');
		if(empty($perfilLogado) || $perfilLogado->CD_PERFIL == 3){
			$msgHome = array(	"type"=>"danger",
								"text"=>"Você não tem autorização para acessar a página requisitada.");
		
			return redirect()->action('CongressoController@index')->with('msgHome', $msgHome);
		}
				
		$evento = Evento::find($cdEvento);		
		if($perfilLogado->CD_PERFIL == 1){
			$inscricaoList = Inscricao::with(['usuarioPerfil','status','evento','inscricaoPedido'])->where('CD_EVENTO',$cdEvento)/*->sortable()*/->paginate(100); 
			$congregacao = Congregacao::get();
		}else{
			$inscricaoList = Inscricao::with(['usuarioPerfil','status','evento','inscricaoPedido'])->where('CD_EVENTO',$cdEvento)->request(['CD_CONGREGACAO'=>$perfilLogado->CD_CONGREGACAO])/*->sortable()*/->paginate(100);
			$congregacao = Congregacao::where("CD_CONGREGACAO",$perfilLogado->CD_CONGREGACAO)->get();	
		}
		$status = Status::where('DS_TIPO_STATUS',2)->get();
		//dd($inscricaoList[0]->usuarioPerfil[0]->usuario[0]);

		return view("congresso.inscritos")	->with('perfilLogado',$perfilLogado)
										   	->with('inscricaoList',$inscricaoList)
										   	->with('evento',$evento)
										   	->with('congregacao',$congregacao)
										   	->with('status',$status);
										   									  	
	}
	
	public function getInscricaoDetalhes($cdInscricaoHash){
		$cdInscricao = is_numeric(MyUtil::b64UrlDecode($cdInscricaoHash))?(MyUtil::b64UrlDecode($cdInscricaoHash)-10000):null;		
		$perfilLogado = Session::get('perfilLogado'); //dd($perfilLogado);
		$msg = Session::get('msg');	Session::forget('msg');
		/*
		if(empty($perfilLogado) || $perfilLogado->CD_PERFIL == 3){
			$msgHome = array(	"type"=>"danger",
								"text"=>"Você não tem autorização para acessar a página requisitada.");
		
			return redirect()->action('CongressoController@index')->with('msgHome', $msgHome);
		}
		*/
		$inscricao = Inscricao::with(['usuarioPerfil','status','evento','inscricaoPedido'])->where('CD_INSCRICAO',$cdInscricao)->first(); 
		//dd($inscricao->inscricaoPedido->sum("NR_QUANTIDADE"));
		if(empty($inscricao)){
			$msgHome = array(	"type"=>"danger",
								"text"=>"Inscrição não localizada.");
		
			return redirect()->action('CongressoController@index')->with('msgHome', $msgHome);
		}
		
		$congregacao = Congregacao::get();
		$status = Status::where('DS_TIPO_STATUS',2)->get();

		return view("congresso.inscricao-detalhes")->with('perfilLogado',$perfilLogado)
											   ->with('inscricao',$inscricao)
												 ->with('congregacao',$congregacao)
												 ->with('status',$status)
											   ->with('msg',$msg);
										   									  	
	}

	public function buscarInscricao($cdEvento = null, InscritosRequest $request){ //dd($request->all());
		$perfilLogado = Session::get('perfilLogado');		
		$inscricaoList = Inscricao::with(['usuarioPerfil','status','evento','inscricaoPedido'])->where("CD_EVENTO",$request["CD_EVENTO"])->request($request)/*->sortable()*/
			//->toSql(); 
			->paginate(100);
		//dd($inscricaoList);
		$evento = Evento::find($request["CD_EVENTO"]);		
		if($perfilLogado->CD_PERFIL == 1)
			$congregacao = Congregacao::get();
		else
			$congregacao = Congregacao::where("CD_CONGREGACAO",$perfilLogado->CD_CONGREGACAO)->get();	
				
		$status = Status::where('DS_TIPO_STATUS',2)->get();
		return view("congresso.inscritos")	->with('perfilLogado',$perfilLogado)
										   	->with('inscricaoList',$inscricaoList)
										   	->with('evento',$evento)
										   	->with('congregacao',$congregacao)
										   	->with('status',$status)
										   	->with('request',$request);										   
	}

	public function getPedidos($cdEvento){		
		$perfilLogado = Session::get('perfilLogado');
		if(empty($perfilLogado) || $perfilLogado->CD_PERFIL == 3){
			$msgHome = array(	"type"=>"danger",
								"text"=>"Você não tem autorização para acessar a página requisitada.");
		
			return redirect()->action('CongressoController@index')->with('msgHome', $msgHome);
		}
		
		$inscricaoList = Inscricao::select("CD_INSCRICAO")->where("CD_EVENTO",$cdEvento)->request(["CD_CONGREGACAO"=>$perfilLogado->CD_CONGREGACAO]);
		$pedidoList = DB::table("TB_INSCRICAO_PEDIDO as t1")
						->join('TB_PRODUTO as t2', 't1.CD_PRODUTO', '=', 't2.CD_PRODUTO')
						->join('TB_INSCRICAO as t3', 't1.CD_INSCRICAO', '=', 't3.CD_INSCRICAO')
						->join('TB_STATUS as t4', 't3.CD_STATUS', '=', 't4.CD_STATUS')
						->join('TB_USUARIO_PERFIL as t5', 't3.CD_USUARIO_PERFIL', '=', 't5.CD_USUARIO_PERFIL')
						->join('TB_USUARIO as t6', 't5.CD_USUARIO', '=', 't6.CD_USUARIO')
						->join('TB_CONGREGACAO as t7', 't5.CD_CONGREGACAO', '=', 't7.CD_CONGREGACAO')
						->select('t1.*','t2.*','t3.CD_STATUS','t4.NO_STATUS','t6.NO_USUARIO','t7.NO_CONGREGACAO')
						->whereIn('t1.CD_INSCRICAO',$inscricaoList)->paginate(100); 
		
		//dd($pedidoList);
		
		$evento = Evento::find($cdEvento);		
		if($perfilLogado->CD_PERFIL == 1)
			$congregacao = Congregacao::get();
		else
			$congregacao = Congregacao::where("CD_CONGREGACAO",$perfilLogado->CD_CONGREGACAO)->get();
		$status = Status::where('DS_TIPO_STATUS',2)->get();
		//dd($inscricaoList[0]->usuarioPerfil[0]->usuario[0]);

		return view("congresso.pedidos")	->with('perfilLogado',$perfilLogado)
										   	->with('pedidoList',$pedidoList)
										   	->with('evento',$evento)
										   	->with('congregacao',$congregacao)
										   	->with('status',$status);
										   									  	
	}

	public function buscarPedidos(InscritosRequest $request){		
		$perfilLogado = Session::get('perfilLogado');				
		$inscricaoList = Inscricao::select("CD_INSCRICAO")->where("CD_EVENTO",$request["CD_EVENTO"])->request($request);
		$pedidoList = DB::table("TB_INSCRICAO_PEDIDO as t1")
						->join('TB_PRODUTO as t2', 't1.CD_PRODUTO', '=', 't2.CD_PRODUTO')
						->join('TB_INSCRICAO as t3', 't1.CD_INSCRICAO', '=', 't3.CD_INSCRICAO')
						->join('TB_STATUS as t4', 't3.CD_STATUS', '=', 't4.CD_STATUS')
						->join('TB_USUARIO_PERFIL as t5', 't3.CD_USUARIO_PERFIL', '=', 't5.CD_USUARIO_PERFIL')
						->join('TB_USUARIO as t6', 't5.CD_USUARIO', '=', 't6.CD_USUARIO')
						->join('TB_CONGREGACAO as t7', 't5.CD_CONGREGACAO', '=', 't7.CD_CONGREGACAO')
						->select('t1.*','t2.*','t3.CD_STATUS','t4.NO_STATUS','t6.NO_USUARIO','t7.NO_CONGREGACAO')
						->whereIn('t1.CD_INSCRICAO',$inscricaoList)->paginate(100); 
		
		//dd($pedidoList);
		
		$evento = Evento::find($request["CD_EVENTO"]);		
		if($perfilLogado->CD_PERFIL == 1)
			$congregacao = Congregacao::get();
		else
			$congregacao = Congregacao::where("CD_CONGREGACAO",$perfilLogado->CD_CONGREGACAO)->get();
		$status = Status::where('DS_TIPO_STATUS',2)->get();
		//dd($inscricaoList[0]->usuarioPerfil[0]->usuario[0]);

		return view("congresso.pedidos")	->with('perfilLogado',$perfilLogado)
										   	->with('pedidoList',$pedidoList)
										   	->with('evento',$evento)
										   	->with('congregacao',$congregacao)
											->with('status',$status)
											->with('request',$request);
										   									  	
	}

	
	public function postAlterarStatusInscricao(AlterarStatusRequest $request){ 
		//dd($request);
		/*
		$this->validate($request, [
			'CD_INSCRICAO' => 'required',
			'CD_STATUS' => 'required',									
		],[]);
		*/
		$novaStatus = Inscricao::where("CD_INSCRICAO",$request["CD_INSCRICAO"])->update(["CD_STATUS"=>$request["CD_STATUS"]]);	
		$cdInscricaoHash = MyUtil::b64UrlEncode($request["CD_INSCRICAO"]+10000);
		$msg = array(	"type"=>"success",
						"text"=>"Status alterado com sucesso.");	

		$inscricaoCompleta = Inscricao::with(['usuarioPerfil','status','evento','inscricaoPedido'])->where('CD_INSCRICAO',$request["CD_INSCRICAO"])->first();		
		Mail::to($inscricaoCompleta->usuarioPerfil[0]->usuario[0]->NO_MAIL)->send(new InscricaoRealizadaComSucesso($inscricaoCompleta));

		return redirect()->action('CongressoController@getInscricaoDetalhes',['cdInscricaoHash' => $cdInscricaoHash])->with('msg', $msg);

  	}














	
	
	public function formCriar(){ 
		$usuario = Usuario::find(Kerberos::getAdMatric());
		// Vai enviar um objeto empty, apenas para não dar erro na view
		$participantePreenchimento = ParticipantePreenchimento::with('preenchimento')->whereRaw('CD_PESQUISA=0')->paginate(20); //dd($participantePreenchimento);   	
		return view("pesquisa.criar-editar")->with('QtParticipantePreenchimento',0)
										    ->with('usuario',$usuario);
  	}
	
	public function postCriar(PesquisaRequest $request){ 
		$req = MyUtil::convArrDt($request->all()); //dd($req);
		$campos = MyUtil::parseCampo($req);
		$pesquisa = Pesquisa::create($req);
		$pesquisa->campo()->createMany($campos);
		
		$msgHome = array(	"type"=>"success",
							"text"=>"Pesquisa <strong>{$req['NO_TITULO']}</strong> criada com sucesso, veja o link na listagem abaixo.");
		
        return redirect()->action('PesquisaController@index')->with('msgHome', $msgHome);
  	}
	
	public function form($exibicao,$cdPesquisaHash){ 
		$cdPesquisa = MyUtil::b64UrlDecode($cdPesquisaHash) - 10000; 
		$usuario = Usuario::find(Kerberos::getAdMatric());
		$pesquisa = Pesquisa
					::whereRaw("(DT_INICIO <= '".date("Y-m-d H:i:s")."' or DT_INICIO is null or DT_INICIO = '0000-00-00 00:00:00') ")
					->whereRaw("(DT_FIM >= '".date("Y-m-d H:i:s")."' or DT_FIM is null or DT_FIM = '0000-00-00 00:00:00' ) ")
					->where(function($query) use ($usuario) { // Para envolver com parenteses
							$query->whereNull('DS_ACESSO_F_MATRICULAS')
							->orWhere('DS_ACESSO_F_MATRICULAS','like',"%{$usuario->NR_MATRICULA}%");
					})
					->where('CD_PESQUISA','=',$cdPesquisa)
					->whereRaw('CD_STATUS=1')				
					//->toSql();
					->first();
					//dd($pesquisa);
		if(empty($pesquisa)){
			$msgHome = array(	"type"=>"warning",
								"text"=>"Pesquisa/Formulário não encontrada ou não disponível para essa data e hora ou não disponível para sua matrícula ou inativa.");
			return redirect()->action('PesquisaController@index')->with('msgHome', $msgHome);
		}else{
			//dd($pesquisa);
			if($pesquisa->NO_TIPO_PESQUISA == "Externa"){ 
				/* To do... */ 
			}elseif($pesquisa->NO_TIPO_PESQUISA == "Interna"){
				$participantePreenchimento = ParticipantePreenchimento::with('preenchimento')
											 ->where('CD_PESQUISA','=',$cdPesquisa)
											 ->where('NR_MATRICULA','=',$usuario->NR_MATRICULA)
											 ->first();
				if($pesquisa->ST_MULTIPLA_RESPOSTA == "Não" and !empty($participantePreenchimento))
					$participacaoBloqueada = true;
				else
					$participacaoBloqueada = false;
				$qtdPaginas = Campo::where('CD_PESQUISA','=',$cdPesquisa)->max("NR_PAGINA");
				$campos = Campo::where('CD_PESQUISA','=',$cdPesquisa)->orderBy('CD_CAMPO')->get(); 
			}							 
		}
		
		//dd($participantePreenchimento);   	
		return view("pesquisa.formulario")->with('pesquisa',$pesquisa)
										  ->with('participantePreenchimento',$participantePreenchimento)
										  ->with('participacaoBloqueada',$participacaoBloqueada)
										  ->with('qtdPaginas',$qtdPaginas)
										  ->with('campos',$campos)
										  ->with('usuario',$usuario)
										  ->with('exibicao',$exibicao);
  	}
	
	public function postForm(FormRequest $request){ 
		
		$cdPesquisaHash = $request->route('cdPesquisaHash');
		$cdPesquisa = MyUtil::b64UrlDecode($cdPesquisaHash) - 10000;
		$contexto = $request->route('contexto');
		$req = MyUtil::convArrDt($request->all()); //dd($req);
		//dd($GLOBALS['FormRequest.campos']);
		$campos = $GLOBALS['FormRequest.campos'];
		// Verificar se tem campo com limite de respostas e validar antes de prosseguir com a inserções
		for($l=0;$l<count($campos);$l++){
			if($campos[$l]->CD_TIPO_CAMPO==3 or $campos[$l]->CD_TIPO_CAMPO==7){
				$opc = explode(';',$campos[$l]->DS_OPC_RESPOSTA); // var_dump($opc);
				for($j=0;$j<count($opc);$j++){
					$posi = strpos($opc[$j],'['); 
					if($posi === false){/*Nada a fazer*/}else{
						$posf = strpos($opc[$j],']') - $posi - 1;
						$max = substr($opc[$j],$posi+1,$posf); // Max de respostas permitidas para o item
						$respostaAValidar = substr($opc[$j],0,$posi);
						if($respostaAValidar==$req['campo'.$l]){ // Verificar se o usuario respondeu a opção que tem limites antes de ir no banco  						
							$qt_res_bd = DB::table('TB_SEP_PREENCHIMENTO')->where([['CD_CAMPO',$campos[$l]->CD_CAMPO],['TX_PREENCHIMENTO',$respostaAValidar]])->count('TX_PREENCHIMENTO');
							if($qt_res_bd>=$max){ // bloquear o post pq o max de resposta do campo foi atingido
								$bloqueioPorQtdMaxRespostas['CAMPO'] = $campos[$l]['NO_CAMPO'];
								$bloqueioPorQtdMaxRespostas['VALOR'] = $req['campo'.$l];
								break 2; //Para o segundo for			
							}
						}
					}
				}
			}						
		}
		
		if(empty($bloqueioPorQtdMaxRespostas)){
			$req["CD_PESQUISA"] = $cdPesquisa;
			$req["NR_MATRICULA"] = Kerberos::getAdMatric();
			$req["NR_CPF_CNPJ"] = MyUtil::clearMask($req["NR_CPF_CNPJ"]);
			$req["NO_PARTICIPANTE"] = MyUtil::clearMask($req["NO_PARTICIPANTE"]);
			//dd($req);
			$camposForm = MyUtil::parseCampoForm($req);
			
			$participantePreenchimento = ParticipantePreenchimento::create($req);
			
			$participantePreenchimento->preenchimento()->createMany($camposForm);
			
			$pesquisa = Pesquisa::find($cdPesquisa);
			
			$msgForm = array(	"type"=>"success",
								"text"=>"<strong>Formulário enviado com sucesso!</strong> protocolo de envio nº {$participantePreenchimento->CD_PARTICIPANTE_PREENCHIMENTO}.<br>{$pesquisa->TX_ENVIO}");
			
			$urlPaths = array(	'exibicao'=>$request->route('exibicao'),
								'cdPesquisaHash'=>$request->route('cdPesquisaHash'),
								'contexto'=>$request->route('contexto'));
			return redirect()->action('PesquisaController@form',$urlPaths)->with('msgForm', $msgForm);
			
		} else {
			//dd($bloqueioPorQtdMaxRespostas);
			$msgForm = array(	"type"=>"danger",
								"text"=>"O valor <strong>{$bloqueioPorQtdMaxRespostas['VALOR']}</strong> não pode ser gravado no
								campo <strong>{$bloqueioPorQtdMaxRespostas['CAMPO']}</strong> porque não está mais disponível
								(limite de envios esgotado), tente novamente com outro valor.");
			
			return back()->with('msgForm', $msgForm);
			
		}
		
		$msgForm = array(	"type"=>"danger",
							"text"=>"Erro interno inesperado, tente novamente mais tarde.");
			
		return back()->with('msgForm', $msgForm);
		
  	}  

	public function editarLista(){
		$usuario = Session::get('usuario');	Session::forget('usuario');			
		if($usuario->CD_PERFIL == 1)
			$pesquisas = Pesquisa::paginate(20); 
		elseif($usuario->CD_PERFIL == 2)
			$pesquisas = Pesquisa::where('CD_DEPENDENCIA','=',$usuario->CD_DEPENDENCIA)->paginate(20);	
		
		return view('pesquisa.editar-lista')->with('pesquisas',$pesquisas);
  	}
	
	public function editar($cdPesquisaHash){
		$cdPesquisa = MyUtil::b64UrlDecode($cdPesquisaHash) - 10000;
		$usuario = Usuario::find(Kerberos::getAdMatric());
		
		// Confirmar se o usuario pode editar a pesquisa indicada
		if($usuario->CD_PERFIL == 1)
			$pesquisa = Pesquisa::find($cdPesquisa);
		elseif($usuario->CD_PERFIL == 2)
			$pesquisa = Pesquisa::where([['CD_DEPENDENCIA',$usuario->CD_DEPENDENCIA],['CD_PESQUISA',$cdPesquisa]])->first();
		
		//dd($pesquisa);
			
		if(empty($pesquisa)){
			$msgHome = array(	"type"=>"danger",
								"text"=>"Você não tem autorização para acessar a página requisitada.");
			return redirect()->action('PesquisaController@index')->with('msgHome', $msgHome);
		}else{
			$campos = Campo::where('CD_PESQUISA','=',$cdPesquisa)->get();			
			$QtParticipantePreenchimento = ParticipantePreenchimento::where('CD_PESQUISA','=',$cdPesquisa)->count();
			//dd($QtParticipantePreenchimento);
			return view("pesquisa.criar-editar")->with('pesquisa',$pesquisa)
												->with('campos',$campos)
												->with('QtParticipantePreenchimento',$QtParticipantePreenchimento)
												->with('usuario',$usuario);
											   	   	
		}
	}

	public function postEditar(PesquisaRequest $request){ 
		$req = MyUtil::convArrDt($request->all()); //dd($req);		
		$cdPesquisaHash = $request->route('cdPesquisaHash');
		$cdPesquisa = MyUtil::b64UrlDecode($cdPesquisaHash) - 10000;
		$QtParticipantePreenchimento = ParticipantePreenchimento::where('CD_PESQUISA','=',$cdPesquisa)->count();
		
		$pesquisa = Pesquisa::find($cdPesquisa);
		$pesquisa->update($req);
		$campos = MyUtil::parseCampo($req);

		if($QtParticipantePreenchimento==0) // Se não houve participações é permitido modificar os campos, estes são deletados e recriados 
			$pesquisa->campo()->delete();
		
		$pesquisa->campo()->createMany($campos);
		
		$msgHome = array(	"type"=>"success",
							"text"=>"Pesquisa <strong>{$req['NO_TITULO']}</strong> modificada com sucesso, veja o link na listagem abaixo.");
		
        return redirect()->action('PesquisaController@index')->with('msgHome', $msgHome);
  	}
	
	
		
}
