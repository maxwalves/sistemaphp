<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Arquivo;
use DateTime;
use Illuminate\Support\Facades\DB;
use DateTimeZone;

class ArquivosController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $search = null;

        return view('wiki.welcome', ['search' => $search, 'user' => $user]);
    }

    public function pesquisar(Request $request)
    {
        $user = auth()->user();
        // Obtenha os dados de pesquisa do $request
        $pesquisa = $request->input('pesquisa');

        $resultados = Arquivo::where('texto', 'like', '%'.$pesquisa. '%')->get();

        $resultados2 = DB::table('arquivos')
        ->select(DB::raw("id, nome, texto, anexo, data, tipo, textoHtml, SUBSTRING(texto, LOCATE('" . $pesquisa . "', texto) - 50, 100) AS trecho"))
        ->where('texto', 'LIKE', '%' . $pesquisa . '%')
        ->get();

        //dd($resultados);
        // Retorne a visualização com os resultados da pesquisa
        return view('wiki.welcome', compact('resultados', 'resultados2', 'user'));
    }
    public function pesquisarAdmin(Request $request)
    {
        $user = auth()->user();
        // Obtenha os dados de pesquisa do $request
        $pesquisa = $request->input('pesquisa');

        $resultados = Arquivo::where('texto', 'like', '%'.$pesquisa. '%')->get();

        $resultados2 = DB::table('arquivos')
        ->select(DB::raw("id, nome, texto, anexo, data, tipo, textoHtml, SUBSTRING(texto, LOCATE('" . $pesquisa . "', texto) - 50, 100) AS trecho"))
        ->where('texto', 'LIKE', '%' . $pesquisa . '%')
        ->get();

        // Retorne a visualização com os resultados da pesquisa
        return view('wiki.admin', compact('resultados', 'resultados2', 'user'));
    }

    public function acessarNormas(){

        $user = auth()->user();
        $resultados = Arquivo::where('tipo', 'Norma')->get();
        return view('wiki.normas', compact('resultados', 'user'));
    }
    public function pesquisarNormas(Request $request)
    {
        $user = auth()->user();
        // Obtenha os dados de pesquisa do $request
        $pesquisa = $request->input('pesquisa');

        $resultados = Arquivo::where('tipo', 'Norma')->get();

        $resultados2 = DB::table('arquivos')
        ->select(DB::raw("id, nome, texto, anexo, data, tipo, textoHtml, SUBSTRING(texto, LOCATE('" . $pesquisa . "', texto) - 50, 100) AS trecho"))
        ->where('texto', 'LIKE', '%' . $pesquisa . '%')
        ->where('tipo', 'Norma')
        ->get();

        // Retorne a visualização com os resultados da pesquisa
        return view('wiki.normas', compact('resultados', 'resultados2', 'user'));
    }

    public function acessarInstrucoesNormativas(){

        $user = auth()->user();
        $resultados = Arquivo::where('tipo', 'Instrução Normativa')->get();
        return view('wiki.instrucoesNormativas', compact('resultados', 'user'));
    }

    public function pesquisarInstrucoesNormativas(Request $request)
    {
        $user = auth()->user();
        // Obtenha os dados de pesquisa do $request
        $pesquisa = $request->input('pesquisa');

        $resultados = Arquivo::where('tipo', 'Instrução Normativa')->get();

        $resultados2 = DB::table('arquivos')
        ->select(DB::raw("id, nome, texto, anexo, data, tipo, textoHtml, SUBSTRING(texto, LOCATE('" . $pesquisa . "', texto) - 50, 100) AS trecho"))
        ->where('texto', 'LIKE', '%' . $pesquisa . '%')
        ->where('tipo', 'Instrução Normativa')
        ->get();

        // Retorne a visualização com os resultados da pesquisa
        return view('wiki.instrucoesNormativas', compact('resultados', 'resultados2', 'user'));
    }

    public function acessarLegislacao(){

        $user = auth()->user();
        $resultados = Arquivo::where('tipo', 'Legislação')->get();
        return view('wiki.legislacao', compact('resultados', 'user'));
    }

    public function pesquisarLegislacao(Request $request)
    {
        $user = auth()->user();
        // Obtenha os dados de pesquisa do $request
        $pesquisa = $request->input('pesquisa');

        $resultados = Arquivo::where('tipo', 'Legislação')->get();

        $resultados2 = DB::table('arquivos')
        ->select(DB::raw("id, nome, texto, anexo, data, tipo, textoHtml, SUBSTRING(texto, LOCATE('" . $pesquisa . "', texto) - 50, 100) AS trecho"))
        ->where('texto', 'LIKE', '%' . $pesquisa . '%')
        ->where('tipo', 'Legislação')
        ->get();

        // Retorne a visualização com os resultados da pesquisa
        return view('wiki.legislacao', compact('resultados', 'resultados2', 'user'));
    }

    public function acessarNormasGestao(){

        $user = auth()->user();
        $resultados = Arquivo::where('tipo', 'Norma de Gestão')->get();
        return view('wiki.normasGestao', compact('resultados', 'user'));
    }

    public function pesquisarNormasGestao(Request $request)
    {
        $user = auth()->user();
        // Obtenha os dados de pesquisa do $request
        $pesquisa = $request->input('pesquisa');

        $resultados = Arquivo::where('tipo', 'Norma de Gestão')->get();

        $resultados2 = DB::table('arquivos')
        ->select(DB::raw("id, nome, texto, anexo, data, tipo, textoHtml, SUBSTRING(texto, LOCATE('" . $pesquisa . "', texto) - 50, 100) AS trecho"))
        ->where('texto', 'LIKE', '%' . $pesquisa . '%')
        ->where('tipo', 'Norma de Gestão')
        ->get();

        // Retorne a visualização com os resultados da pesquisa
        return view('wiki.legislacao', compact('resultados', 'resultados2', 'user'));
    }

    public function acessarAdmin(){

        $user = auth()->user();
        $resultados = Arquivo::all();
        return view('wiki.admin', compact('resultados', 'user'));
    }

    public function create(){
        $user = auth()->user();
        return view('wiki.create', compact('user'));
    }

    public function store(Request $request)
    {
        $arquivo = new Arquivo();
        
        $timezone = new DateTimeZone('America/Sao_Paulo');
        $arquivo->data = new DateTime('now', $timezone);
        $arquivo->nome = $request->nome;
        $arquivo->textoHtml = $request->texto;
        $textoSemTags = strip_tags($arquivo->textoHtml);
        $arquivo->texto = str_replace('&nbsp;', ' ', $textoSemTags);
        $arquivo->tipo = $request->tipo;

        $regras = [
            'nome' => 'required',
            'tipo' => 'required',
            'texto' => 'required',
            'arquivo1' => 'required'
        ];

        $mensagens = [
            'required' => 'Este campo não pode estar em branco',
        ];

        $request->validate($regras, $mensagens);

        if($request->hasFile('arquivo1') && $request->file('arquivo1')->isValid())
        {
            $requestFile = $request->arquivo1;

            $extension = $requestFile->extension();

            $fileName = md5($requestFile->getClientOriginalName() . strtotime("now")) . "." . $extension;
            
            $requestFile->move(public_path('arquivos/'), $fileName);

            $arquivo->anexo = $fileName;
        }
        $arquivo->save();

        return redirect('/admin')->with('msg', 'Arquivo cadastrado com sucesso!');
    }

    public function destroy($id)
    {
        $arquivo = Arquivo::findOrFail($id);
        $id = $arquivo->id;
        $arquivo->delete();

        return redirect('/admin')->with('msg', 'Arquivo excluído com sucesso!');
    }

    public function edit($id)
    {
        $user = auth()->user();
        $arquivo = Arquivo::findOrFail($id);

        return view('wiki.edit', ['arquivo' => $arquivo, 'user' => $user]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $dados = array(
            "nome" => $request->nome,
            "texto" => "",
            "anexo" => "",
            "data" => "",
            "textoHtml" => $request->texto,
            "tipo" => $request->tipo
        );

        if($request->hasFile('arquivo1') && $request->file('arquivo1')->isValid())
        {
            $requestFile = $request->arquivo1;

            $extension = $requestFile->extension();

            $fileName = md5($requestFile->getClientOriginalName() . strtotime("now")) . "." . $extension;
            
            $requestFile->move(public_path('arquivos/'), $fileName);

            $dados["anexo"] = $fileName;
        }

        $timezone = new DateTimeZone('America/Sao_Paulo');
        $dados["data"] = new DateTime('now', $timezone);
        $textoSemTags = strip_tags($request->texto);
        $dados["texto"] = str_replace('&nbsp;', ' ', $textoSemTags);

        Arquivo::findOrFail($request->id)->update($dados);

        return redirect('/admin')->with('msg', 'Arquivo editado com sucesso!');
    }
    public function show($id){
        $user = auth()->user();
        $arquivo = Arquivo::findOrFail($id);

        return view('wiki.show', ['arquivo' => $arquivo, 'user' => $user]);
    }
}
