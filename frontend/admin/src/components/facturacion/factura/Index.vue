<template>
<!--Contenido-->
      <!-- Content Wrapper. Contains page content -->
        <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">LISTA DE PEDIDOS Y FACTURACIÓN</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid" style="height:100px;">
        <div class="row" >
            <div class="col-md-12 col-lg-12">
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn bg-olive active" @click="changeComponent">
                    <input type="radio"  name="options" id="option1" autocomplete="off"><i class="fa fa-barcode"></i> Pedidos pendientes
                    </label>
                    <label class="btn bg-olive" @click="changeComponent(false)">
                    <input type="radio" name="options" id="option2" autocomplete="off"><i class="fa fa-barcode"></i> Facturas emitidas
                    </label>
                </div>
            </div>
            <el-divider></el-divider>
            <div class="col-md-12 col-lg-12">
                <orders v-if="is_orders"></orders>
                <invoices v-else></invoices>
            </div>
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!--Fin-Contenido-->
</template>

<script>
import moment from 'moment'
import orders from './Orders'
import invoices from './Invoice'
export default {
  name: 'index_invoice',
  components: {
      orders,
      invoices
  },
  data() {
      return {
          vat: {},
          serie: {},
          is_orders: true
      }
  },

  created() {
    let self = this
  },

    methods: {
        //get vat actual
        getVat(){
            let self = this
            self.$store.state.services.vatService.getAll()
            .then(r=>{
                self.vat = r.data.data.find(x=>x.actual)
                if(self.vat == undefined){
                    self.$toastr.error('no existe ningun valor de iva activo','error')
                }
            }).catch(e=>{

            })
        },

        //get serie actual
        getSerie(){
            let self = this
            self.$store.state.services.serieService.getAll()
            .then(r=>{
                self.serie = r.data.data.find(x=>x.actual)
                if(self.serie == undefined){
                    self.$toastr.error('no existe ninguna serie activa o bien se agoto el numero de facturas','error')
                }
            }).catch(e=>{

            })
        },

    //get serie actual
        getOrders(){
            let self = this
            self.$store.state.services.orderService.getAll()
            .then(r=>{
                console.log(r.data.data)
            }).catch(e=>{

            })
        },

        changeComponent(option = true){
            let self = this
            self.is_orders = option
        }
    },
}
</script>
