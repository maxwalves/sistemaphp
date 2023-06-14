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
        $search = null;

        return view('wiki.welcome', ['search' => $search]);
    }

    public function pesquisar(Request $request)
    {
        // Obtenha os dados de pesquisa do $request
        $pesquisa = $request->input('pesquisa');

        $resultados = Arquivo::where('texto', 'like', '%'.$pesquisa. '%')->get();

        $resultados2 = DB::table('arquivos')
        ->select(DB::raw("id, nome, texto, anexo, data, tipo, textoHtml, SUBSTRING(texto, LOCATE('" . $pesquisa . "', texto) - 50, 100) AS trecho"))
        ->where('texto', 'LIKE', '%' . $pesquisa . '%')
        ->get();

        //dd($resultados);
        // Retorne a visualização com os resultados da pesquisa
        return view('wiki.welcome', compact('resultados', 'resultados2'));
    }
    public function pesquisarAdmin(Request $request)
    {
        // Obtenha os dados de pesquisa do $request
        $pesquisa = $request->input('pesquisa');

        $resultados = Arquivo::where('texto', 'like', '%'.$pesquisa. '%')->get();

        $resultados2 = DB::table('arquivos')
        ->select(DB::raw("id, nome, texto, anexo, data, tipo, textoHtml, SUBSTRING(texto, LOCATE('" . $pesquisa . "', texto) - 50, 100) AS trecho"))
        ->where('texto', 'LIKE', '%' . $pesquisa . '%')
        ->get();

        // Retorne a visualização com os resultados da pesquisa
        return view('wiki.admin', compact('resultados', 'resultados2'));
    }

    public function acessarNormas(){

        $resultados = Arquivo::where('tipo', 'Norma')->get();
        return view('wiki.normas', compact('resultados'));
    }
    public function pesquisarNormas(Request $request)
    {
        // Obtenha os dados de pesquisa do $request
        $pesquisa = $request->input('pesquisa');

        $resultados = Arquivo::where('tipo', 'Norma')->get();

        $resultados2 = DB::table('arquivos')
        ->select(DB::raw("id, nome, texto, anexo, data, tipo, textoHtml, SUBSTRING(texto, LOCATE('" . $pesquisa . "', texto) - 50, 100) AS trecho"))
        ->where('texto', 'LIKE', '%' . $pesquisa . '%')
        ->where('tipo', 'Norma')
        ->get();

        // Retorne a visualização com os resultados da pesquisa
        return view('wiki.normas', compact('resultados', 'resultados2'));
    }

    public function acessarInstrucoesNormativas(){

        $resultados = Arquivo::where('tipo', 'Instrução Normativa')->get();
        return view('wiki.instrucoesNormativas', compact('resultados'));
    }

    public function pesquisarInstrucoesNormativas(Request $request)
    {
        // Obtenha os dados de pesquisa do $request
        $pesquisa = $request->input('pesquisa');

        $resultados = Arquivo::where('tipo', 'Instrução Normativa')->get();

        $resultados2 = DB::table('arquivos')
        ->select(DB::raw("id, nome, texto, anexo, data, tipo, textoHtml, SUBSTRING(texto, LOCATE('" . $pesquisa . "', texto) - 50, 100) AS trecho"))
        ->where('texto', 'LIKE', '%' . $pesquisa . '%')
        ->where('tipo', 'Instrução Normativa')
        ->get();

        // Retorne a visualização com os resultados da pesquisa
        return view('wiki.instrucoesNormativas', compact('resultados', 'resultados2'));
    }

    public function acessarLegislacao(){

        $resultados = Arquivo::where('tipo', 'Legislação')->get();
        return view('wiki.legislacao', compact('resultados'));
    }

    public function pesquisarLegislacao(Request $request)
    {
        // Obtenha os dados de pesquisa do $request
        $pesquisa = $request->input('pesquisa');

        $resultados = Arquivo::where('tipo', 'Legislação')->get();

        $resultados2 = DB::table('arquivos')
        ->select(DB::raw("id, nome, texto, anexo, data, tipo, textoHtml, SUBSTRING(texto, LOCATE('" . $pesquisa . "', texto) - 50, 100) AS trecho"))
        ->where('texto', 'LIKE', '%' . $pesquisa . '%')
        ->where('tipo', 'Legislação')
        ->get();

        // Retorne a visualização com os resultados da pesquisa
        return view('wiki.legislacao', compact('resultados', 'resultados2'));
    }

    public function acessarNormasGestao(){

        $resultados = Arquivo::where('tipo', 'Norma de Gestão')->get();
        return view('wiki.normasGestao', compact('resultados'));
    }

    public function pesquisarNormasGestao(Request $request)
    {
        // Obtenha os dados de pesquisa do $request
        $pesquisa = $request->input('pesquisa');

        $resultados = Arquivo::where('tipo', 'Norma de Gestão')->get();

        $resultados2 = DB::table('arquivos')
        ->select(DB::raw("id, nome, texto, anexo, data, tipo, textoHtml, SUBSTRING(texto, LOCATE('" . $pesquisa . "', texto) - 50, 100) AS trecho"))
        ->where('texto', 'LIKE', '%' . $pesquisa . '%')
        ->where('tipo', 'Norma de Gestão')
        ->get();

        // Retorne a visualização com os resultados da pesquisa
        return view('wiki.legislacao', compact('resultados', 'resultados2'));
    }

    public function acessarAdmin(){

        $resultados = Arquivo::all();
        return view('wiki.admin', compact('resultados'));
    }

    public function create(){
        return view('wiki.create');
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
        $arquivo = Arquivo::findOrFail($id);

        return view('wiki.edit', ['arquivo' => $arquivo]);
    }

    public function update(Request $request)
    {
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

        $arquivo = Arquivo::findOrFail($id);

        return view('wiki.show', ['arquivo' => $arquivo]);
    }
}
