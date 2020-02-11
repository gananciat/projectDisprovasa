<template>
  <div v-loading="loading">
  
  <button @click="print" type="button" class="btn btn-default"><i class="fa fa-print" v-b-tooltip title="imprimir factura"> imprimir</i></button>
    <div class="invoice p-3 mb-3" v-loading="loading">
            <!-- title row -->
        <div class="row" v-if="invoice !== null">
            <div class="col-12">
            <h5>
                <div class="row">
                  <div class="col-md-4">
                    </div>
                    <div class="col-md-4">
                        <div class="widget-user-image text-center">
                            <img class="elevation-2 img-circle" style="width: 100%; height: 80px;" src="../../../assets/logo.jpeg" alt="User Avatar">
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                     <label>Factura:</label> <label class="text-danger">{{getFullInvoice(invoice)}}</label> </small>
                    </div>
                </div>
              </h5>
            </div>
            <!-- /.col -->
            </div>

             <div v-if="invoice !== null" class="row invoice-info text-sm" style="margin: 1px;">
                <div class="col-sm-5 invoice-col bg-blue" >
                  <address class="text-right">
                    <strong>Disprovasa, Sociedad Anónima.</strong><br>
                    <strong>Centro Comercial, local25, Zona 10 Residenciales.</strong><br>
                    <strong>Naciones Unidas, Villa Nueva, Guatemala.</strong><br>
                  </address>
                </div>
                 <div class="col-sm-4 invoice-col bg-blue">
                  <address class="text-right">
                   <br>
                    <strong>e-mail: disprovasa@disprovasa.com</strong><br>
                    <strong>Teléfono: 6630-7145</strong><br>
                  </address>
                </div>
                <div class="col-sm-3 invoice-col bg-orange text-white">
                  <address class="text-center">
                    <strong>FACTURA CAMBIARIA</strong><br>
                    <strong>SERIE {{ invoice.serie.serie }}</strong><br>
                    <strong>NIT: 6652675-9</strong><br>
                  </address>
                </div>
              </div>
              <div class="progress progress-xxs">
                <div class="progress-bar bg-orange" style="width: 100%"></div>
              </div>
              <div class="row invoice-info" v-if="invoice !== null">
                <div class="col-sm-12 invoice-col">
                  <address class="text-primary" style="margin-left: 5%;">
                    <strong>Fecha: {{ invoice.date| moment('DD/MM/YYYY') }}</strong><br />
                    <strong>Nombre: {{ invoice.order.school.name }}</strong><br />
                    <strong>Dirección: {{ invoice.order.school.direction }}</strong><br />
                    <strong>
                      <div class="row">
                        <div class="col-md-4">Nit: {{ invoice.order.school.nit }}</div>
                        <div class="col-md-4">Tel: </div>
                        <div class="col-md-4">Email: </div>
                      </div>
                      </strong>
                  </address>
                </div>
              </div>
              <div class="progress progress-xxs">
                <div class="progress-bar bg-orange" style="width: 100%"></div>
              </div>
            <!-- info row -->
            <div class="row invoice-info" v-if="invoice !== null">
            <address>
                <div class="col-md-12">
                    <strong>Fecha:</strong>{{invoice.date | moment('DD/MM/YYYY')}}
                </div>
                <div class="col-md-12">
                    <strong>Nombre:</strong> {{invoice.order.school.name}}
                </div>
                <div class="col-md-12">
                    <strong>Dirección:</strong> {{invoice.order.school.direction}}
                </div>
                <div class="col-md-12">
                    <strong>NIT:</strong> {{invoice.order.school.nit}}
                </div>
            </address>
            <!-- Table row -->
            <div class="col-12 table-responsive">
                <table class="table table-sm table-bordered">
                <thead class="bg-blue">
                <tr>
                    <th>Cantidad</th>
                    <th>Descripcion</th>
                    <th class="columns_orange">Valor</th>
                </tr>
                </thead>
                <tbody class="bg-light color-palette">
                    <tr v-for="(p, i) in invoice.products" :key="i">
                      <td>{{p.progress.purchased_amount}}</td>
                      <td>
                      <span v-if="p.progress.product.camouflage">{{p.invoiced_as}}</span>
                      <span v-else>{{p.progress.product.name}}</span>
                      </td>
                      <td>{{p.progress.purchased_amount * p.progress.detail.sale_price | currency('Q ')}}</td>
                    </tr>
                </tbody>
                </table>
            </div>

            <h1 v-if="invoice.cancel" id="anulado">
                ANULADA
            </h1>

            <div class="col-md-12">
                  <div class="col-md-6"></div>
                <div class="col-md-6 col-lg-6 pull-right">
                  <p class="lead">Factura del {{invoice.date | moment('DD/MM/YYYY')}}</p>

                  <div class="table-responsive">
                    <table class="table table-sm">
                      <tbody><tr>
                        <th style="width:50%">Subtotal:</th>
                        <td>{{invoice.total | currency('Q ')}}</td>
                      </tr>
                      <tr>
                        <th>Iva ({{invoice.vat.value}}%)</th>
                        <td>{{invoice.total_iva | currency('Q ')}}</td>
                      </tr>
                      <tr>
                        <th>Total (iva incluido):</th>
                        <td>{{invoice.total - invoice.total_iva | currency('Q ')}}</td>
                      </tr>
                    </tbody></table>
                  </div>
                </div>
              </div>
        </div>
  </div>
  </div>
</template>

<script>
import moment from 'moment'
export default {
  name: 'print_invoice',
  data() {
      return {
          loading: false,
          invoice_name: '',
          invoice: null,
          total_string: ''
      }
  },

  created() {
    let self = this
    events.$on('get_invoice',self.onEventInvoice)
  },

  beforeDestroy(){
      events.$off('get_invoice',self.onEventInvoice)
  },

  methods: {
      onEventInvoice(id){
          let self = this
          self.get(id)
      },

      //imprimir factura
      print(){
          let self = this
          self.total_string = self.totalString(150500.50)
          self.loading = true
          self.$store.state.services.invoiceService
            .printInvoice(self.invoice.id,self.total_string)
            .then(r => {
            self.loading = false
            var name = 'factura_'+self.getFullInvoice(self.invoice);
            if(r.response){
                this.$toastr.error(r.response.data.error, 'error')
                return
            }
            const url = window.URL.createObjectURL(new Blob([r.data], { type: 'application/pdf' }));
            const link = document.createElement('a');
            link.href = url
            //link.download = name
            link.target = '_blank'
            link.click();
            })
            .catch(r => {});
      },

      get(id){
        let self = this
        self.loading = true
        self.$store.state.services.invoiceService.get(id)
        .then(r=>{
            self.loading = false
            self.invoice = r.data.data
        }).catch(e=>{

        })
      },

       getFullInvoice(item){
            let self = this
            var invoice = self.formatCode(item.invoice,String(item.serie.total).length)
            return invoice
        },

        //formatear codigo para tarjeta de reponsabilidades
        formatCode(n, len = 4) {
            return (new Array(len + 1).join('0') + n).slice(-len)
        },

        totalString(total){
          let self = this
          return self.$store.state.global.numeroALetras(total)
        }
  },
   computed: {

    },
}
</script>

<style scoped>
  #anulado {
        color: rgba(255, 0, 0, 0.5);
        top: 60%;
        left: 40%;
        font-size: 50px;
        -webkit-transform: rotate(-45deg); 
        -moz-transform: rotate(-45deg);    
        width:100px; text-align: center; position: absolute;
    }

  .columns_orange {
    background-color:rgb(196, 92, 18)
  }

  .progress-xxs {
      height: 5px;
  }
  
</style>
