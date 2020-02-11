<template>
<!--Contenido-->
      <!-- Content Wrapper. Contains page content -->
        <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" v-loading="loading">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark" v-if="purchase !== null">DETALLE DE COMPRA {{purchase.no_prof}}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#/purchase">COMPRAS</a></li>
              <li class="breadcrumb-item active">INFORMACIÓN COMPRA</li>
            </ol>
          </div>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content" v-if="purchase !== null">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <!-- Main content -->
            <div class="invoice p-3 mb-3">
              <!-- title row -->
              <div class="row">
                <div class="col-12">
                  <h4>
                    <i class="fa fa-globe"></i> {{purchase.provider.name}}
                    <small class="float-right">Fecha: {{purchase.date | moment('DD/MM/YYYY')}}<br />
                        <label>Factura:</label> <label class="text-danger">{{purchase.no_prof}}</label> 
                    </small>
                  </h4>
                </div>
                <!-- /.col -->
              </div>
              <!-- info row -->
              <div class="row invoice-info">
                <div class="col-sm-12 invoice-col">
                  <address>
                    <strong>{{purchase.provider.name}}.</strong><br>
                    <strong>NIT: </strong> {{purchase.provider.nit}}<br>
                    <strong>DIRECCION: </strong>{{purchase.provider.direction}}, MUNICIPIO DE  {{purchase.provider.municipality.name}} 
                    DEPARTAMENTO DE {{purchase.provider.municipality.departament.name}}<br>
                    <strong>TELEFONOS: </strong> {{getPhones(purchase.provider.phones)}}<br>
                  </address>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <!-- Table row -->
              <div class="row">
                <div class="col-12 table-responsive">
                  <table class="table table-striped table-sm">
                    <thead>
                    <tr>
                      <th>Producto</th>
                      <th width="15%">Fecha vencimiento</th>
                      <th width="15%">Cantidad</th>
                      <th width="15%">Merma</th>
                      <th width="15%">Precio compra</th>
                      <th>Subtotal</th>
                      <th>Acción</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item) in purchase.details" :key="item.id">
                      <td>{{item.product.name}}</td>
                      <td>
                        <input type="date" class="form-control" placeholder="ingrese fecha vencimiento"
                            :name="item.product.name+'fec'"
                            v-model="item.expiry_date"
                            :data-vv-as=" 'fecha vencimiento '+item.product.name "
                            v-validate="'required'"
                        :class="{'input':true,'has-errors': errors.has(item.product.name+'fec')}">
                        <FormError :attribute_name="item.product.name+'fec'" :errors_form="errors"> </FormError>
                      </td>
                      <td v-if="!purchase.cancel">
                        <input class="form-control input-sm" v-model="item.quantity" 
                        :name="item.product.name+'cant'"
                        :data-vv-as="'cantidad '+item.product.name | lowercase "
                        v-validate="'required|integer|min_value:0'"
                        :class="{'input':true,'has-errors': errors.has(item.product.name+'cant')}">
                        <FormError :attribute_name="item.product.name+'cant'" :errors_form="errors"> </FormError>
                      </td>
                      <td v-if="!purchase.cancel">
                        <input class="form-control input-sm" v-model="item.decrease" 
                        :name="item.product.name"
                        :data-vv-as="'merma '+item.product.name | lowercase "
                        v-validate="'required|integer|min_value:0|max_value:'+item.quantity"
                        :class="{'input':true,'has-errors': errors.has(item.product.name)}">
                        <FormError :attribute_name="item.product.name" :errors_form="errors"> </FormError>
                      </td>
                      <td v-else>
                        {{item.decrease}}
                      </td>
                        <td v-if="!purchase.cancel">
                        <input class="form-control input-sm" v-model="item.purcharse_price" 
                        :name="item.product.name+'pur'"
                        :data-vv-as="'precio compra '+item.product.name | lowercase "
                        v-validate="'required|decimal|min_value:0.1'"
                        :class="{'input':true,'has-errors': errors.has(item.product.name+'pur')}">
                        <FormError :attribute_name="item.product.name+'pur'" :errors_form="errors"> </FormError>
                      </td>
                      <td>{{item.purcharse_price * item.quantity | currency('Q ')}}</td>
                      <td><button @click="destroy(item)" type="button" class="btn btn-danger btn-sm" v-tooltip="'eliminar'"><i class="fa fa-close"></i></button></td>
                    </tr>
                    </tbody>
                  </table>
                </div>
                
                  <h1 v-if="purchase.cancel" id="anulado">
                        ANULADA
                    </h1>
                <div class="col-md-12">
                  <div class="col-8 pull-left">
                        <p class="lead">Actualizar cantidad y merma de productos defectuosos</p>
                        <button type="button" class="btn btn-primary" @click="beforeUpdate"> <i class="fa fa-refresh"></i> actualizar</button>
                    </div>
                    <div class="col-4 pull-right">
                        <p class="lead">Compra del {{purchase.date | moment('DD/MM/YYYY')}}</p>

                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th><h4>TOTAL:</h4></th>
                                        <td><h4 class="text-primary">{{totalAmount | currency('Q ')}}</h4></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
            <!-- /.invoice -->
          </div><!-- /.col -->
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
import FormError from '../../shared/FormError'
export default {
  name: 'view_info_compra',
  components: {
    FormError
  },
  
  data() {
    return {
      loading: false,
      purchase: null,
      total: 0
    }
  },

  created() {
    let self = this
    self.get(self.$route.params.id)
  },

  methods: {
    onFiltered (filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length
      this.currentPage = 1
    },

    get(id) {
      let self = this;
      self.loading = true;

      self.$store.state.services.purchaseService
        .get(id)
        .then(r => {
          self.loading = false
          self.purchase = r.data.data
        })
        .catch(r => {});
    },

    update(){
        let self = this
        var data = {
          purchase_id: self.purchase.id,
          total: self.total,
          items: self.purchase.details
        }
        self.$store.state.services.purchaseService
        .updateDetails(data)
        .then(r => {
          self.loading = false
          if(r.response){
            this.$toastr.error(r.response.data.error, 'error')
            return
          }
          this.$toastr.success('compra actualizada con exito', 'exito')
          self.$router.push('/purchase')
        })
        .catch(r => {});
    },

     //funcion para eliminar registro
    destroy(data){
      let self = this

      self.$swal({
        title: "¿Anular compra?",
        text: "Esta seguro de remover producto?",
        type: "warning",
        showCancelButton: true
      }).then((result) => { // <--
          if (result.value) { // <-- if confirmed
              self.loading = true
              self.$store.state.services.purchaseService
                .destroyDetail(data)
                .then(r => {
                  self.loading = false
                  if(r.response){
                        this.$toastr.error(r.response.data.error, 'error')
                        return
                    }
                  this.$toastr.success('producto ah sido eliminado', 'exito')
                  self.get(self.purchase.id)
                })
                .catch(r => {});
          }
      });
    },

    beforeUpdate(){
      let self = this
      self.$validator.validateAll().then((result) => {
          if (result) {
              self.update()
           }
      });
    },

    getPhones(phones){
      let self = this
      return phones.map(e => e.number).join(", ")
    },
  },

  computed:{
    totalAmount(){
          let self = this
          var total = self.purchase.details.reduce((a,b)=>{
              return a + (b.quantity * b.purcharse_price)
          },0)
          self.total = total
          return total.toFixed(2)
      }
  }
}
</script>

<style scoped>
  #anulado {
        color: rgba(255, 0, 0, 0.5);
        top: 30%;
        left: 50%;
        font-size: 50px;
        -webkit-transform: rotate(-45deg); 
        -moz-transform: rotate(-45deg);    
        width:100px; text-align: center; position: absolute;
    }
</style>