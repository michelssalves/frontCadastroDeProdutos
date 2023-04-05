import Vue from "./vueJsFramework.js";
const app = new Vue({
  el: "#app",
  data() {
    return {
      menu: "Cadastro De Produtos",
      produtos: [],
      produto: [],
      idProduto:'',
      descricao:'',
      marca:'',
      modelo:'',
      referencia:'',
      minimo:'',
      maximo:'',
      saldo:'',
      endereco:'',
      valor:'',
      messageModal: '',
      messageTable:'',
      page: 1,
      ultimaPagina:'',
      iconSave:
        "./assets/img/icons/salvar.gif",
      iconClose:
        "./assets/img/icons/fechar.png",
      iconCreate:
        "./assets/img/icons/create.png",
      iconLimpar:
        "./assets/img/icons/x-filter.png",
      iconExcluir:
        "./assets/img/icons/excluir.gif",
    };
  },
  filters: {
    upper: function (value) {
      return value.toUpperCase();
    },
    dataFormatada: function (value) {
      return value.split("-").reverse().join("/");
    },
    duasCasasDecimais: function (value) {
      return Number(value).toFixed(2);
    },
  },
  methods: {
    onlyNumber($event) {
      let keyCode = $event.keyCode ? $event.keyCode : $event.which;
      if ((keyCode < 48 || keyCode > 57) && keyCode !== 46) {
         // 46 é ponto e 44 é virgula 45 é hifen
        $event.preventDefault();
      }
    },
    dataAtual() {
      const data = new Date();
      const dia = String(data.getDate()).padStart(2, "0");
      const mes = String(data.getMonth() + 1).padStart(2, "0");
      const ano = data.getFullYear();
      const dataAtual = `${ano}-${mes}-${dia}`;
      return dataAtual;
    },
    limpar() {

      this.produto = []
      this.idProduto = ''
      this.descricao = ''
      this.marca = ''
      this.modelo = ''
      this.referencia = ''
      this.minimo = ''
      this.maximo = ''
      this.saldo = ''
      this.endereco = ''
      this.valor = ''
      this.getProdutos();

    },
    getProdutos() {

      axios
        .get(
          `http://localhost:1000/api/produto?page=${this.page}`,
        
        )
        .then((res) => {
          console.log(res.data.last_page)
          this.produtos = res.data.data
          this.ultimaPagina = res.data.last_page
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getProduto(id) {

      axios
        .get(
          `http://localhost:1000/api/produto/${id}`,
        
        )
        .then((res) => {
          console.log(res.data.id)
          this.produto = res.data
          this.idProduto = res.data.id
        })
        .catch((err) => {
          console.log(err);
        });
    },
    updateProduto(form, id) {

      const formFiltroPagamentos = document.getElementById(
        form
      );
   
      const formulario = new FormData(formFiltroPagamentos)

      axios
        .post(
          `http://localhost:1000/api/produto/${id}`,
          formulario,{
          params: {
            _method: 'PUT'
          }
        }
        
        )
        .then((res) => {
          console.log(res.data.id)
          if(res.data.id){

            this.messageTable = 'Alterado com sucesso!'

          }else{
            this.messageTable = 'Houve algum erro na atualização!'
          }
        })
        .catch((err) => {
          console.log(err)
          this.messageTable = 'Campos obrigatório não foram preenchidos!'
        });

        this.limpar()
    },
    deleteProduto(id) {

      if(window.confirm("Deseja realmente excluir?")){
      axios
        .delete(
          `http://localhost:1000/api/produto/${id}`,
        
        )
        .then((res) => {
          console.log(res)
          if(res.data.msg === 'Removido com sucesso'){

            this.messageTable = res.data.msg

          }else{
            this.messageTable = 'Houve algum erro na exclusão!'
          }
        })
        .catch((err) => {
          console.log(err);
          this.messageTable = 'Erro na exclusão!'
        });

        this.limpar()
      }else{

        this.abrirModal('alterarProduto', 'visualizar', id)
      } 
    },
    abrirModal(modal, action, id) {

      const modalSolicitado = new bootstrap.Modal(document.getElementById(modal));
      modalSolicitado.show();

      if(action  === 'visualizar'){

          this.getProduto(id)
      }

    },
    salvar(form) {

      const formFiltroPagamentos = document.getElementById(
        form
      );
   
      const formulario = new FormData(formFiltroPagamentos);
      console.log(formulario)
      axios
        .post(
          `http://localhost:1000/api/produto`,
           formulario
        )
        .then((res) => {
          console.log(res.data.id)
          if(res.data.id){

            this.messageModal = 'Cadastrado com sucesso!'

          }else{
            this.messageModal = 'Houve algum erro no cadastro!'
          }
        })
        .catch((err) => {
          console.log(err)
          this.messageModal = 'Campos obrigatório não foram preenchidos!'
        });

        this.limpar()
        this.getProdutos();
    },


  },
  watch: {
    page() {
      this.getProdutos();
    },
    messageTable() {
      setTimeout(() => {
        this.messageTable = "";
      }, 2000);
    },
    messageModal() {
      setTimeout(() => {

        this.messageModal = "";
      }, 2000);
    },
  },
  mounted: function () {
    this.getProdutos();
  },
});
