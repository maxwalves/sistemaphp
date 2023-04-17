@extends('layouts.main')

@section('title', 'Paranacidade')
@section('content')

    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Cadastro de Autorizações de Viagens</h5>
            
            @if (count($avs) >0)
                <table class="table table-ordered table-hover" id="tabelaAvs">
                    {{-- Cabeçalho da tabela ------------------------------------------------------- --}}
                    <thead>
                        <th>Código</th>
                        <th>Data de Criação</th>
                        <th>Prioridade</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </thead>
                    <tbody>
                    {{-- Corpo da tabela que será preenchido via javascript-------------------------- --}}
                    </tbody>
                </table>
            @else
                <h5>Não existem AVs cadastrados!</h5>
            @endif
            
            <div class="card-footer">
                <a class="btn btn-sm btn-primary" 
                role="button" onClick="novaAv()">Nova AV</a>
            </div>
        </div>
    </div>

    {{-- Implementação da DIALOG ------------------------------------------------------- --}}
    <div class="modal fade" tabindex="-1" role="dialog" id="dlgAvs" >
        <div class="modal-dialog" role="document" >
            <div class="modal-content">

                {{-- Formulário ------------------------------------------------------- --}}
                <form class="form-horizontal" id="formAv">
                    <div class="modal-header">
                        <h5 class="modal-title">Nova Av</h5>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="id" class="form-control">
                        
                        <div class="form-group">
                            {{-- Aqui iria a data, como a data será a atual, tem que programar no backend pra puxar a do computador --}}
                        </div>

                        <div class="form-group">
                            <label for="objetivos" class="control-label">Qual é o Objetivo da viagem</label>
                            <div class="input-group">
                                <select class="form-control" id="objetivos">

                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="prioridade" class="control-label">Qual é a Prioridade da sua viagem? (selecione)</label>
                            <div class="input-group">
                                <select class="form-control" id="prioridade">
                                    <option value="Alta"> Alta</option>
                                    <option value="Média"> Média</option>
                                    <option value="Baixa"> Baixa</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="dataCriacao">Data da viagem:</label>
                            <input type="date" name="dataCriacao" id="dataCriacao" class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="banco" class="control-label">Banco</label>
                            <div class="input-group">
                                <input type="number" class="form-control" 
                                id="banco" placeholder="Banco">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="agencia" class="control-label">Agência</label>
                            <div class="input-group">
                                <input type="number" class="form-control" 
                                id="agencia" placeholder="Agência">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="conta" class="control-label">Conta</label>
                            <div class="input-group">
                                <input type="number" class="form-control" 
                                id="conta" placeholder="Conta">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="pix" class="control-label">Pix</label>
                            <div class="input-group">
                                <input type="number" class="form-control" 
                                id="pix" placeholder="Pix">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="comentarios" class="control-label">Comentários</label>
                            <div class="input-group">
                                <input type="text" class="form-control" 
                                id="comentarios" placeholder="Comentário">
                            </div>
                        </div>

                        <div class="form-group">
                            {{-- Aqui iria o Status, mas isso será preenchido automaticamente no Back End--}}
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Salvar</button>
                        <button type="reset" class="btn btn-secondary" onclick="$('#dlgAvs').modal('hide')" >Cancel</button>
                    </div>
                </form>
                {{-- Fim do formulário ------------------------------------------------------- --}}
            </div>
        </div>
    </div>
@endsection

{{-- JAVASCRIPTS ------------------------------------------------------- --}}
@section('javascript')
    <script type="text/javascript">

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        });
        //Fechamento do modal ------------------------
        function fecharModal(){
            $('#dlgAvs').modal('close');
        }
        //Nova AV ------------------------
        function novaAv() {
            $('#id').val('');
            $('#dataCriacao').val('');
            $('#prioridade').val('');
            $('#banco').val('');
            $('#agencia').val('');
            $('#conta').val('');
            $('#pix').val('');
            $('#comentarios').val('');

            $('#dlgAvs').modal('show');
        }
        
        function carregarObjetivos(){
            //Implementar a api de objetivos *******************************************TODO ********************************
            $.getJSON('api/objetivos', function(data){
                
                for(i=0; i<data.length; i++){
                    opcao = '<option value="' + data[i].id + '">' + data[i].nomeObjetivo + '</option>';
                    $('#objetivos').append(opcao);
                }
            });
        }
        //Alimenta as linhas da tabela principal ------------------------
        function montarLinha(av){
            var linha = "<tr>" +
                    "<td>" + av.id + "</td>" +
                    "<td>" + date('d/m/Y', strtotime(av.dataCriacao)) + "</td>" +
                    "<td>" + av.prioridade + "</td>" +
                    "<td>" + av.status + "</td>" +
                    "<td>" +
                        '<button class="btn btn-xs btn-primary" onclick="editar('+ av.id +')"> Editar </button>' +
                        '<button class="btn btn-xs btn-danger" onclick="remover('+ av.id +')"> Apagar </button>' +
                    "</td>" +
                "</tr>";
            return linha;
        }
        //Acionamento do botão editar ------------------------
        function editar(id){
            //Implementar essa parte na API de Avs **************************** TODO ****************************
            $.getJSON('api/avs/' + id, function(data){
                $('#id').val(data.id);
                $('#prioridade').val(data.prioridade);
                $('#banco').val(data.banco);
                $('#agencia').val(data.agencia);
                $('#conta').val(data.conta);
                $('#pix').val(data.pix);
                $('#comentarios').val(data.comentarios);
                $('#objetivos').val(data.objetivo_id);
                $('#dlgAvs').modal('show');
            });
        }
        //Acionamento do botão remover ------------------------
        function remover(id) {
            $.ajax({
                type: "DELETE",
                //Implementar essa parte na API de AVs ******************************** TODO ********************************
                url: "/api/avs/" + id,
                context: this,
                success: function(){
                    console.log('Apagou OK');
                    linhas = $("#tabelaAvs>tbody>tr");
                    e = linhas.filter(function(i, elemento){
                        return elemento.cells[0].textContent == id;
                    });
                    if(e){
                        e.remove();
                    }
                },
                error: function(error){
                    console.log(error);
                }
            });
        }
        //Carrega as Avs na tela ------------------------
        function carregarAvs(){

            $.getJSON('api/avs', function(data){
                
                for(i=0; i<data.length; i++){
                    linha = montarLinha(data[i]);
                    $('#tabelaAvs>tbody').append(linha);
                }
            });
        }
        //Acionamento do botão de criar nova AV ------------------------
        function criarAv(){
            av = { 
                dataCriacao: $('#dataCriacao').val(), 
                prioridade: $('#prioridade').val(), 
                banco: $('#banco').val(), 
                agencia: $('#agencia').val(), 
                conta: $('#conta').val(), 
                pix: $('#pix').val(),
                comentarios: $('#comentarios').val(),
                status: "Em preenchimento pelo usuário",
                objetivo_id: $('#objetivos').val()
            };

            $.post("/api/avs", av, function(data){
            
            avFormatada = JSON.parse(data);
            linha = montarLinha(avFormatada);
            $('#tabelaAvs>tbody').append(linha);

            });
        }
        //Acionamento do botão de salvar nova AV ------------------------
        function salvarAv(){
            av = { 
                dataCriacao: $('#dataCriacao').val(), 
                prioridade: $('#prioridade').val(), 
                banco: $('#banco').val(), 
                agencia: $('#agencia').val(), 
                conta: $('#conta').val(), 
                pix: $('#pix').val(),
                comentarios: $('#comentarios').val(),
                status: "Em preenchimento pelo usuário",
                categoria_id: $('#objetivos').val()
            };

            //Configuração do AJAX ------------------------
            $.ajax({
                type: "PUT",
                url: "/api/avs/" + av.id,
                context: this,
                data: av,
                success: function(data){
                    av = JSON.parse(data);
                    linhas = $("#tabelaAvs>tbody>tr");
                    e = linhas.filter( function(i, e){
                        return ( e.cells[0].textContent == prod.id );
                    });
                    if(e){
                        e[0].cells[0].textContent = av.id;
                        e[0].cells[1].textContent = av.dataCriacao;
                        e[0].cells[2].textContent = av.prioridade;
                        e[0].cells[3].textContent = av.status;
                    }


                },
                error: function(error){
                    console.log(error);
                }
            });

        }

        $("#formAv").submit(function(av){
            av.preventDefault();

            //Verifica se está criando um novo ou editando
            if($("#id").val() != ''){
                salvarAv();
            }
            else{
                carregarAvs();
            }
            
            $("#dlgAvs").modal('hide');
        });

        //Assim que a tela carrega, aciona automaticamente essas duas funções ------------------------
        $(function(){
            carregarObjetivos();
            carregarAvs();
        })
    </script>
@endsection