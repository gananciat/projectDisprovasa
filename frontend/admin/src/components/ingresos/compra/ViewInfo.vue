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
                      <th>Cantidad</th>
                      <th width="15%">Merma</th>
                      <th>Precio compra</th>
                      <th>Subtotal</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item) in purchase.details" :key="item.id">
                      <td>{{item.product.name}}</td>
                      <td>{{item.quantity}}</td>
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
                      <td>{{item.purcharse_price}}</td>
                      <td>{{item.purcharse_price * item.quantity | currency('Q ')}}</td>
                    </tr>
                    </tbody>
                  </table>
                </div>
                
                  <h1 v-if="purchase.cancel" id="anulado">
                        ANULADA
                    </h1>
                <div class="col-md-12">
                  <div class="col-8 pull-left">
                        <p class="lead">Actualizar merma de productos defectuosos</p>
                        <button type="button" class="btn btn-primary" @click="beforeUpdate"> <i class="fa fa-refresh"></i> actualizar</button>
                    </div>
                    <div class="col-4 pull-right">
                        <p class="lead">Compra del {{purchase.date | moment('DD/MM/YYYY')}}</p>

                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th><h4>TOTAL:</h4></th>
                                        <td><h4 class="text-primary">{{purchase.total | currency('Q ')}}</h4></td>
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
      purchase: null
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
    }
  },

  computed:{
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