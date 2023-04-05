<!DOCTYPE html>
<html lang="pt">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produtos</title>
    <link rel="stylesheet" href="./assets/css/custom.css">
    <link rel="stylesheet" href="./assets/css/w3.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/fontawesome.all.min.6.2.1.css">
    <script src="./assets/js/fontawesome.all.min.js"></script>
    <script src="./assets/js/bootstrap.bundle.min.v5.2.3.js"></script>
    <script src="./assets/js/jquery-3.6.1.min.js"></script>
    <script src="./assets/js/main.js"></script>
    <script src="./assets/js/axios.min.js"></script>
    <script src="./assets/js/vue.global.js"></script>
    <script type="module" src="./assets/js/cadastroDeProdutosVue.js"></script>
</head>

<body id="idBody" class="app sidebar-mini">
    <!--INICIO DIV APP VUE JS-->
    <div id="app">
        <aside class="app-sidebar">
        </aside>
        <header class="app-header">
            <a class="app-header__logo" href="?p=1"><i class="fa-solid fa-warehouse"></i> Estoque</a>
            <a onclick="esconderSideBar('idBody')" class="app-sidebar__toggle" data-toggle="sidebar" aria-label="Hide Sidebar"></a>
        </header>
        <main class="app-content">
            <!--AREA ONDE ESTÁ A TABELA-->
            <div class="tableArea">
                <form method='POST' id='formFiltroPagamentos'>
                    <div class="d-flex justify-content-center mt-1">
                            <button  v-show="messageTable.length == 0"type="button" class='btn btn-light' @click="abrirModal('criarProduto')"><img class="iconeSize" :src="iconCreate"></button>
                            <div v-show="messageTable.length > 0" class="rounded-circle text-dark fs-6 p-3">
                                <h2>{{messageTable}}</h2>
                            </div>
                        </div>
                    <div class="table-wrapper">
                        <table class="table table-striped table-hover mt-1 ">
                            <thead class="header-tabela">
                                <tr>
                                    <th>Código</th>
                                    <th>Descrição</th>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>Referência</th>
                                    <th>Min</th>
                                    <th>Max</th>
                                    <th>Saldo</th>
                                    <th>End</th>
                                    <th>Valor </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(produto, i) in produtos" @click="abrirModal('alterarProduto', 'visualizar' , produto.id)" style="cursor:pointer">
                                    <td>{{produto.id}}</td>
                                    <td>{{produto.descricao}}</td>
                                    <td>{{produto.marca}}</td>
                                    <td>{{produto.modelo}}</td>
                                    <td>{{produto.referencia}}</td>
                                    <td>{{produto.minimo}}</td>
                                    <td>{{produto.maximo}}</td>
                                    <td>{{produto.saldo}}</td>
                                    <td>{{produto.endereco}}</td>
                                    <td>{{produto.valor || duasCasasDecimais}}</td>
                                </tr>
                            </tbody>
                        </table>
                        <nav aria-label="Page navigation example" style="cursor:pointer">
                            <ul class="pagination pagination-sm justify-content-center">
                                <li class="page-item">
                                    <a class="page-link" @click="page = 1">Primeira</a>
                                </li>
                                <li class="page-item" v-if="page - 1 > 0" @click="page--">
                                    <a class="page-link">{{page - 1}}</a>
                                </li>
                                <li class="page-item active">
                                    <a class="page-link">{{ page }}</a>
                                </li>
                                <li class="page-item" v-if="page + 1 <= ultimaPagina" @click="page++">
                                    <a class="page-link">{{page + 1}}</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" @click="page = ultimaPagina">Ultima</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </form>
            </div>
            <!--/AREA ONDE ESTÁ A TABELA-->
        </main>
        <!--MODAL CRIAR PRODUTO-->
        <div class="modal fade" id="criarProduto" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="criarProdutoModalLabel" aria-hidden="true">
            <form id="formCriarProduto" method="POST">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header fundo-cabecalho">
                            <div class="d-flex flex-row">
                                <div class="d-none d-md-block">
                                    <h2 class="p-2 bg-dark rounded text-light fs-2">Cadastrar</h2>
                                </div>
                            </div>
                            <div class="d-flex flex-row">
                                <div class="p-1"><button type="button" title="Salvar" @click="salvar('formCriarProduto')" class="btn btn-light btn-sm"><img class="iconeSize" :src="iconSave" /></button></div>
                                <div class="p-1"><button type="button" title="Fechar" @click="limpar()" id="botaoFechar" class="btn btn-sm" data-bs-dismiss="modal"><img class="iconeSize" :src="iconClose"></button></div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center mt-1">
                            <div v-show="messageModal.length > 0" class="rounded-circle text-dark fs-6 p-3">
                                <h2>{{messageModal}}</h2>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing">Descricao:</span>
                                <input v-model="descricao" id="descricao" name="descricao" type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" required>
                                <span class="input-group-text" id="inputGroup-sizing">Marca:</span>
                                <input v-model="marca" id="marca" name="marca" type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" required>
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing">Modelo:</span>
                                <input v-model="modelo" id="modelo" name="modelo" type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" required>
                                <span class="input-group-text" id="inputGroup-sizing">Referencia:</span>
                                <input v-model="referencia" id="referencia" name="referencia" type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" required>
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing">Min:</span>
                                <input @keypress="onlyNumber($event)" v-model="minimo" id="minimo" name="minimo" type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" required>
                                <span class="input-group-text" id="inputGroup-sizing">Max:</span>
                                <input @keypress="onlyNumber($event)" v-model="maximo" id="maximo" name="maximo" type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" required>
                                <span class="input-group-text" id="inputGroup-sizing">Saldo:</span>
                                <input @keypress="onlyNumber($event)" v-model="saldo" id="saldo" name="saldo" type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" required>
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing">Endereco:</span>
                                <input v-model="endereco" id="endereco" name="endereco" type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" required>
                                <span class="input-group-text" id="inputGroup-sizing">R$:</span>
                                <input @keypress="onlyNumber($event)" v-model="valor" id="valor" name="valor" type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!--/MODAL CRIAR PRODUTO-->
        <!--MODAL ALTERAR PRODUTO-->
        <div class="modal fade" id="alterarProduto" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="alterarProdutoModalLabel" aria-hidden="true">
            <form id="formAlterarProduto" method="POST">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header fundo-cabecalho">
                            <div class="d-flex flex-row">
                                <div class="d-none d-md-block">
                                    <h2 class="p-2 bg-dark rounded text-light fs-2">Visualiza/Alterar</h2>
                                </div>
                            </div>
                            <div class="d-flex flex-row">
                                <div class="p-1"><button type="button" title="Salvar" @click="updateProduto('formAlterarProduto', idProduto)" class="btn btn-light btn-sm" data-bs-dismiss="modal"><img class="iconeSize" :src="iconSave" /></button></div>
                                <div class="p-1"><button type="button" title="Excluir" @click="deleteProduto(idProduto)" class="btn btn-light btn-sm" data-bs-dismiss="modal"><img class="iconeSize" :src="iconExcluir" /></button></div>
                                <div class="p-1"><button type="button" title="Fechar" @click="limpar()" id="botaoFechar" class="btn btn-sm" data-bs-dismiss="modal"><img class="iconeSize" :src="iconClose"></button></div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center mt-1">
                            <div v-show="messageModal.length > 0" class="rounded-circle text-dark fs-6 p-3">
                                <h2>{{messageModal}}</h2>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="input-group input-group-sm mb-3">

                                <span class="input-group-text" id="inputGroup-sizing">Descricao:</span>
                                <input v-model="produto.descricao" id="descricao" name="descricao" type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" required>
                                <span class="input-group-text" id="inputGroup-sizing">Marca:</span>
                                <input v-model="produto.marca" id="marca" name="marca" type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" required>
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing">Modelo:</span>
                                <input v-model="produto.modelo" id="modelo" name="modelo" type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" required>
                                <span class="input-group-text" id="inputGroup-sizing">Referencia:</span>
                                <input v-model="produto.referencia" id="referencia" name="referencia" type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" required>
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing">Min:</span>
                                <input v-model="produto.minimo" @keypress="onlyNumber($event)" id="minimo" name="minimo" type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" required>
                                <span class="input-group-text" id="inputGroup-sizing">Max:</span>
                                <input v-model="produto.maximo" @keypress="onlyNumber($event)" id="maximo" name="maximo" type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" required>
                                <span class="input-group-text" id="inputGroup-sizing">Saldo:</span>
                                <input v-model="produto.saldo" @keypress="onlyNumber($event)" id="saldo" name="saldo" type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" required>
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text" id="inputGroup-sizing">Endereco:</span>
                                <input v-model="produto.endereco" id="endereco" name="endereco" type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" required>
                                <span class="input-group-text" id="inputGroup-sizing">$RS Unitário:</span>
                                <input v-model="produto.valor" @keypress="onlyNumber($event)" id="valor" name="valor" type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!--/MODAL CRIAR PRODUTO-->
        <!--FIM DIV APP VUE JS-->
    </div>
</body>

</html>