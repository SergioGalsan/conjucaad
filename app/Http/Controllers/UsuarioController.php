<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\UsuarioRequest;
use App\Mail\InscricaoRealizadaComSucesso;
use App\Usuario;
use App\Evento;
use App\UsuarioPerfil;
use App\Inscricao;
use App\InscricaoPedido;
use App\Classes\MyUtil;
use Session;

class UsuarioController extends Controller{
    
	/*
	public function __construct(){
		$this->middleware('IsAdmPesquisa', 
			['only' => ['criar', 'editarLista','editar']]);
			
	}
	*/
	
	
	public function index(){		
		/*
		$usuario = Usuario::find(Kerberos::getAdMatric());
		
		if(empty($usuario) || $usuario->CD_PERFIL != 1){
			$msgHome = array(	"type"=>"danger",
								"text"=>"Você não tem autorização para acessar a página requisitada.");
			
			return redirect()->action('PesquisaController@index')->with('msgHome', $msgHome);
		}
		*/
		$perfilList = UsuarioPerfil::with(['usuario','perfil','congregacao','status'])/*->sortable()*/->paginate(4); //dd($usuarioList);
		
		return view("usuario.usuarios")->with('perfilList',$perfilList);									  	
	}
	/*
	public function buscarUsuario(UsuarioRequest $request){
		$usuarioList = Usuario::request($request)->sortable()->paginate(20);
		return view("usuario.usuarios")->with('usuarioList',$usuarioList);
	}
	*/
	
	public function login(Request $request){ 
		//dd($request->all());
		$usuarioPerfil = UsuarioPerfil::with(['usuario','perfil','congregacao','status'])->request($request)->orderBy('CD_PERFIL','asc')->first(); 
		//dd($usuario);
		Session::put('perfilLogado', $usuarioPerfil);
		
        return redirect()->action('CongressoController@index');
	}
	  
	public function logout(){ 
		Session::forget('perfilLogado'); 
				
        return redirect()->action('CongressoController@index');
  	}	
	
	public function getAlterarSenha(){ 
		$perfilLogado = Session::get('perfilLogado');		
		if(empty($perfilLogado)){
			$msgHome = array(	"type"=>"danger",
								"text"=>"Você não tem autorização para acessar a página requisitada.");		
			return redirect()->action('CongressoController@index')->with('msgHome',$msgHome);
		}

		return view('usuario.alterarSenha')->with('perfilLogado',$perfilLogado);	
  	}

	public function postAlterarSenha(Request $request){ 
		$this->validate($request, [
			'CD_USUARIO' => 'required',						
			'TX_SENHA' => 'required|min:6|confirmed',
		],[
			'CD_USUARIO.required' => "Usuário não encontrado, faca o login novamente e tente de novo.",
			'TX_SENHA.required' => "Faltou preencher a nova senha.",
			'TX_SENHA.min' => "As senhas devem possuir ao menos :min dígitos.",
			'TX_SENHA.confirmed' => "A confirmação da senha está diferente da nova senha informada."
		]);
		
		$novaSenha = Usuario::where("CD_USUARIO",$request["CD_USUARIO"])->update(["TX_SENHA"=>base64_encode($request["TX_SENHA"])]);
		$msgSenha = array(	"type"=>"success",
							"text"=>"Senha alterada com sucesso.");	

		$perfilLogado = Session::get('perfilLogado');					
		return view('usuario.alterarSenha')->with('perfilLogado',$perfilLogado)->with("msgSenha",$msgSenha);	
  	}    

		

	public function congregacaoCoordenadores($cdCongregacao){		
		/*
		$usuario = Usuario::find(Kerberos::getAdMatric());
		
		if(empty($usuario) || $usuario->CD_PERFIL != 1){
			$msgHome = array(	"type"=>"danger",
								"text"=>"Você não tem autorização para acessar a página requisitada.");
			
			return redirect()->action('PesquisaController@index')->with('msgHome', $msgHome);
		}
		*/
		$usuarioList = UsuarioPerfil::with('usuario:CD_USUARIO,NO_USUARIO')->where([["CD_CONGREGACAO",$cdCongregacao],["CD_PERFIL",2],["CD_STATUS",1]])/*->sortable()*//*->select(['CD_USUARIO','NO_USUARIO'])*/->get();/*->toSql(); */
		//dd($usuarioList);
		return response()->json($usuarioList);
		//return view("usuario.usuarios")->with('usuarioList',$usuarioList);									  	
	}
	
	public function findDadosUsuario($mail, $nascimento){		
		/*
		$usuario = Usuario::find(Kerberos::getAdMatric());
		
		if(empty($usuario) || $usuario->CD_PERFIL != 1){
			$msgHome = array(	"type"=>"danger",
								"text"=>"Você não tem autorização para acessar a página requisitada.");
			
			return redirect()->action('PesquisaController@index')->with('msgHome', $msgHome);
		}
		*/		
		//$usuario = Usuario::exclude(['TX_SENHA'])->where("NO_MAIL",$mail)->first(); dd($usuario); /*->toSql(); */
		$usuarioPerfil = UsuarioPerfil::request(['NO_MAIL' => $mail, 'DT_NASCIMENTO' => $nascimento])->first(); // ->toSql();
		//dd($usuarioPerfil);
		if(!empty($usuarioPerfil)){
			$usuarioPerfil->usuario[0]->DT_NASCIMENTO = date("d/m/Y", strtotime($usuarioPerfil->usuario[0]->DT_NASCIMENTO));			
			$usuarioPerfil->usuario[0]->NR_TELEFONE = MyUtil::mask($usuarioPerfil->usuario[0]->NR_TELEFONE,'mFone');
			$usuarioPerfil->usuario[0]->NR_CELULAR = MyUtil::mask($usuarioPerfil->usuario[0]->NR_CELULAR,'mFone');
			unset($usuarioPerfil->usuario[0]->TX_SENHA);
		}
		//dd($usuarioPerfil->usuario[0]->NR_CELULAR);
		return response()->json($usuarioPerfil);
		//return view("usuario.usuarios")->with('usuarioList',$usuarioList);									  	
	}

	public function postFormInscricao(UsuarioRequest $request){
		$evento = Evento::where('CD_STATUS',1)->first(); 
		$req = MyUtil::convArrDt($request->all()); 
		$req["NR_CELULAR"] = MyUtil::clearMask($req["NR_CELULAR"]);	
		$req["NR_TELEFONE"] = MyUtil::clearMask($req["NR_TELEFONE"]);
		//$req["TX_SENHA"] = base64_encode($req["TX_SENHA"]);
		//dd($request->getPathInfo());
		$inscricaoPedidos = MyUtil::parseInscricaoPedido($req); //dd($inscricaoPedidos);
		
		$usuario = Usuario::where("NO_MAIL",$req["NO_MAIL"])->first(); //dd($usuario);
		
		if(empty($usuario)){
			$usuario = Usuario::create($req);			
		}else{
			$usuario->fill($req);
			$usuario->save();
		}
		
		$usuarioPerfil = UsuarioPerfil::where([["CD_USUARIO",$usuario->CD_USUARIO],["CD_CONGREGACAO",$req["CD_CONGREGACAO"]],
												["CD_STATUS",1],["CD_PERFIL",3]])->first();
		if(empty($usuarioPerfil))
			$usuarioPerfil = UsuarioPerfil::create(["CD_USUARIO"=>$usuario->CD_USUARIO,"CD_PERFIL"=>3,"CD_CONGREGACAO"=>$req["CD_CONGREGACAO"],"CD_STATUS"=>1]);
		
		// Se a inscrição estiver validada, não permitir refazê-la
		$inscricao = Inscricao::where([["CD_USUARIO_PERFIL",$usuarioPerfil->CD_USUARIO_PERFIL],["CD_EVENTO",$evento->CD_EVENTO],["CD_STATUS",5]])->first(); //dd($inscricao);
		if(!empty($inscricao)){
			if($request->getPathInfo() == "/alterar-inscricao"){
				$msg = array(	"type"=>"danger",
									"text"=>"Está inscrição já está validada, não é possivel fazer alterações com ela nesse status. É necessário alterar seu status primeiro e o inscrito receberá um e-mail de notificação caso o status seja alterado.");
				return back()->with('msg', $msg);					
			}
			
			
			$msgCadastro = array(	"type"=>"danger",
									"text"=>"<strong>{$req['NO_USUARIO']}</strong>, sua inscrição já está validada e por isso não é possível refazê-la. Procure seu líder para colocá-la novamente com status de pendência e aí será possível refazê-la.");
			return redirect()->action('CongressoController@index')->with('msgCadastro', $msgCadastro);						
		}

		$inscricao = Inscricao::where([["CD_USUARIO_PERFIL",$usuarioPerfil->CD_USUARIO_PERFIL],["CD_EVENTO",$evento->CD_EVENTO]])->first(); //dd($inscricao);

		if(empty($inscricao)){
			$inscricao = Inscricao::create(["CD_USUARIO_PERFIL"=>$usuarioPerfil->CD_USUARIO_PERFIL,"CD_EVENTO"=>$evento->CD_EVENTO,"CD_STATUS"=>3,"ST_ALIMENTACAO"=>@$req["ST_ALIMENTACAO"]]);
		}else{
			InscricaoPedido::where("CD_INSCRICAO",$inscricao->CD_INSCRICAO)->delete();
			$inscricao->fill(["CD_USUARIO_PERFIL"=>$usuarioPerfil->CD_USUARIO_PERFIL,"CD_EVENTO"=>$evento->CD_EVENTO,"CD_STATUS"=>3,"ST_ALIMENTACAO"=>(@$req["ST_ALIMENTACAO"]?:'Sim')]);
			$inscricao->save();
		}

		//$inscricao = Inscricao::where([["CD_USUARIO_PERFIL",$usuarioPerfil->CD_USUARIO_PERFIL],["CD_EVENTO",1]])->delete();
		
		if(!empty($inscricaoPedidos))
			$inscricao->inscricaoPedido()->createMany($inscricaoPedidos);
		
		//dd($inscricaoPedidos);						
		if($request->getPathInfo() == "/alterar-inscricao"){
			$msg = array(	"type"=>"success",
								"text"=>"Inscrição e pedido alterados com sucesso.");
			return back()->with('msg', $msg);					
		}

		// Envio do e-mail para o inscrito		
		$cdInscricao = $inscricao->CD_INSCRICAO;
		$inscricaoCompleta = Inscricao::with(['usuarioPerfil','status','evento','inscricaoPedido'])->where('CD_INSCRICAO',$cdInscricao)->first();
		//Mail::to($usuario->NO_MAIL)->send(new InscricaoRealizadaComSucesso($inscricaoCompleta));

		$msgCadastro = array(	"type"=>"success",
								"text"=>"<strong>{$req['NO_USUARIO']}</strong>, sua inscrição foi realizada com sucesso. Procure seu líder para validá-la.<br>Você também receberá um e-mail de confirmação no endereço {$req['NO_MAIL']} em breve.");

    return redirect()->action('CongressoController@index')->with('msgCadastro', $msgCadastro);
	}
	
	
		

	
	
	
	










	
	public function preenchimentosQuantitativos($cdPesquisaHash){
		$cdPesquisa = MyUtil::b64UrlDecode($cdPesquisaHash) - 10000;
		$usuario = Usuario::find(Kerberos::getAdMatric());
		// Confirmar que o usuario pode enxergar o resultado da pesquisa indicada
		if(empty($usuario) || $usuario->CD_PERFIL == 3)
			$pesquisa = Pesquisa::where('CD_PESQUISA','=',$cdPesquisa)
								   ->where('ST_ACESSO_RESULTADO','=','Público')
							   	   ->orWhere('DS_ACESSO_R_MATRICULAS','like','%'.Kerberos::getAdMatric().'%')							   	   
								   ->get();		
		elseif($usuario->CD_PERFIL == 2)
			$pesquisa = Pesquisa::where('CD_PESQUISA','=',$cdPesquisa)
								   ->where('ST_ACESSO_RESULTADO','=','Público')
							   	   ->orWhere('DS_ACESSO_R_MATRICULAS','like','%'.$usuario->NR_MATRICULA.'%')
							   	   ->orWhere('CD_DEPENDENCIA','=',$usuario->CD_DEPENDENCIA)
								   ->get();
		elseif($usuario->CD_PERFIL == 1)
			$pesquisa = true;
			
		if(empty($pesquisa)){
			$msgHome = array(	"type"=>"danger",
								"text"=>"Você não tem autorização para acessar a página requisitada.");
			Session::flash('msgHome', $msgHome);
			
			return redirect('/');
		}else{
			$pesquisa = Pesquisa::find($cdPesquisa);
			$campos = Campo::where('CD_PESQUISA','=',$cdPesquisa)->get();			
			
			return view("pesquisa.preenchimento-quantitativo")->with('pesquisa',$pesquisa)
														  ->with('campos',$campos);
											   	   	
		}						   
	}
	
	public function preenchimentosIndividuais($cdPesquisaHash){
		$cdPesquisa = MyUtil::b64UrlDecode($cdPesquisaHash) - 10000;
		$usuario = Usuario::find(Kerberos::getAdMatric());
		// Confirmar que o usuario pode enxergar o resultado da pesquisa indicada
		if(empty($usuario) || $usuario->CD_PERFIL == 3)
			$pesquisa = Pesquisa::where('CD_PESQUISA','=',$cdPesquisa)
								   ->where('ST_ACESSO_RESULTADO','=','Público')
							   	   ->orWhere('DS_ACESSO_R_MATRICULAS','like','%'.Kerberos::getAdMatric().'%')							   	   
								   ->get();		
		elseif($usuario->CD_PERFIL == 2)
			$pesquisa = Pesquisa::where('CD_PESQUISA','=',$cdPesquisa)
								   ->where('ST_ACESSO_RESULTADO','=','Público')
							   	   ->orWhere('DS_ACESSO_R_MATRICULAS','like','%'.$usuario->NR_MATRICULA.'%')
							   	   ->orWhere('CD_DEPENDENCIA','=',$usuario->CD_DEPENDENCIA)
								   ->get();
		elseif($usuario->CD_PERFIL == 1)
			$pesquisa = true;
			
		if(empty($pesquisa)){
			$msgHome = array(	"type"=>"danger",
								"text"=>"Você não tem autorização para acessar a página requisitada.");
			Session::flash('msgHome', $msgHome);
			
			return redirect('/');
		}else{
			$pesquisa = Pesquisa::find($cdPesquisa);
			$campos = Campo::where('CD_PESQUISA','=',$cdPesquisa)->get();			
			$participantePreenchimento = ParticipantePreenchimento::with('preenchimento')->where('CD_PESQUISA','=',$cdPesquisa)->paginate(20);
			//dd($participantePreenchimento);
			return view("pesquisa.preenchimento-individual")->with('pesquisa',$pesquisa)
														->with('campos',$campos)
														->with('participantePreenchimento',$participantePreenchimento);
											   	   	
		}
	}
		
	public function preenchimentosIndividuaisBuscarPost(PreenchimentoIndividualRequest $request){
		$cdPesquisa = MyUtil::b64UrlDecode($request->route('cdPesquisaHash')) - 10000;
		$usuario = Usuario::find(Kerberos::getAdMatric());
		
		$participantePreenchimento = ParticipantePreenchimento::request($request)->get();
		$pesquisa = Pesquisa::find($cdPesquisa);
		$campos = Campo::where('CD_PESQUISA','=',$cdPesquisa)->get();
		//dd($participantePreenchimentopp);
		return view("pesquisa.preenchimento-individual")->with('pesquisa',$pesquisa)
														->with('campos',$campos)
														->with('participantePreenchimento',$participantePreenchimento);	
	}
		
}
