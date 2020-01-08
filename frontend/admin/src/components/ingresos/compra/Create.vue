<template>
<div>
  <div class="content-wrapper" v-loading="loading">

    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">NUEVA COMPRA</h1> 
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#/purchase">COMPRAS</a></li>
              <li class="breadcrumb-item active">NUEVA COMPRA</li>
            </ol>
          </div>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header no-border">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">Formulario de ingreso </h3>
                </div>
              </div>
              <div class="card-body">
                  <form>
                    <div class="row">
                        <div class="form-group col-md-4 col-lg-4 col-xs-12">
                            <label>NIT proveedor</label>
                            <input type="text" class="form-control" placeholder="NIT proveedor"
                            name="nit"
                            v-model="form.nit"
                            data-vv-as="nit proveedor"
                            v-validate="'required|max:13|min:7'"
                            @keyup.enter="onKeyProvider()"
                        :class="{'input':true,'has-errors': errors.has('nit')}">
                        <FormError :attribute_name="'nit'" :errors_form="errors"> </FormError>
                        </div>
                        <div class="form-group col-md-8 col-lg-8 col-xs-12">
                            <label>Proveedor</label>
                            <input type="text" class="form-control" placeholder="nombre de proveedor"
                            name="name"
                            v-model="form.provider" readonly>
                        </div>
                    </div>

                    <div v-if="form.provider_id !== null">
                        <div class="row">
                            <div class="form-group col-md-3 col-lg-3 col-xs-12">
                                <label>No Factura</label>
                                <input type="text" class="form-control" placeholder="numero factura"
                                name="no_prof"
                                v-model="form.no_prof"
                                data-vv-as="numero de factura"
                                v-validate="'required'"
                            :class="{'input':true,'has-errors': errors.has('no_prof')}">
                            <FormError :attribute_name="'no_prof'" :errors_form="errors"> </FormError>
                            </div>
                            <div class="form-group col-md-3 col-lg-3 col-xs-12">
                                <label>Fecha</label>
                                <input type="date" class="form-control" placeholder="ingrese fecha compra"
                                name="date"
                                v-model="form.date"
                                data-vv-as="fecha compra"
                                v-validate="'required'"
                            :class="{'input':true,'has-errors': errors.has('date')}">
                            <FormError :attribute_name="'date'" :errors_form="errors"> </FormError>
                            </div>
                            <div class="form-group col-md-5 col-sm-5 col-lg-5">
                                <label for="product">Producto</label>
                                <multiselect v-model="product"
                                    :options="products" placeholder="agregue producto"  
                                    :searchable="true"
                                    label="name"
                                    :allow-empty="false">
                                    <span slot="noResult">Ningun producto encontrada</span>
                                    </multiselect>
                            </div>
                            <div class="form-group col-md-1 col-sm-1 col-lg-1">
                                <br />
                                <button type="button" @click="addDetail()" class="btn btn-success btn-sm"><i class="fa fa-plus"></i></button>
                              </div>
                        </div>
                        <div class="row">
                            
                        <el-divider></el-divider>
                                <h3 class="card-title">Detalle de compra</h3>
                                <div class="table-responsive">
                                    <table class="table table-condensed table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Producto</th>
                                                <th>Faltante</th>
                                                <th>Precio compra</th>
                                                <th>Cantidad</th>
                                                <th>Subtotal</th>
                                                <th>Opción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(item, index) in form.details" :key="item.product_id">
                                                <td>{{item.product}}</td>
                                                <td>{{item.subtraction}}</td>
                                                <td>
                                                   <el-input-number v-model="item.purcharse_price" :precision="2" :step="0.1" size="small" :min="0.1"></el-input-number>
                                                </td>
                                                <td>
                                                    <el-input-number v-model="item.quantity" size="small" :min="1"></el-input-number>
                                                </td>
                                                <td>{{subTotal(item)| currency('Q ')}}</td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm" @click="removeDetail(item)"><i class="fa fa-minus"></i></button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-md-12">
                                    <div class="col-4 pull-right">
                                        <p class="lead" v-if="form.date !== null">Compra del {{form.date | moment('DD/MM/YYYY')}}</p>

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
                        </div>
                    
                        <div class="row" v-if="form.details.length > 0">
                            <!-- /.col -->
                            <div class="col-12 text-right">
                            <button type="button" class="btn btn-danger btn-sm" @click="$router.push('/purchase')"><i class="fa fa-undo"></i> Volver</button>
                            <button type="button" class="btn btn-primary btn-sm" @click="beforeCreate"><i class="fa fa-save"></i> Guardar</button>
                            </div>
                            <!-- /.col -->
                        </div>

                    </div>
                </form>
              </div>
            </div>
            <!-- /.card -->
            <!-- /.card -->
          </div>
          <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
</div>
</template>

<script>
import FormError from '../../shared/FormError'
export default {
  name: "create_purcharse",
  components: {
      FormError
  },
  data() {
    return {
      loading: false,
      products: [],
      product: null,
      form: {
          id: null,
          nit: '',
          provider: '',
          date: null,
          provider_id: null,
          total: null,
          no_prof: '',
          details: []
      },

      form_d: {
          purcharse_price: 0.1,
          quantity: 1
      }
    };
  },
  created() {
    let self = this
    self.getProducts()
  },

  methods: {
    create(){
        let self = this
        var data = self.form
        self.$store.state.services.purchaseService
        .create(data)
        .then(r => {
          self.loading = false
          if(r.response){
            this.$toastr.error(r.response.data.error, 'error')
            return
          }
          this.$toastr.success('registro agregado con exito', 'exito')
          self.$router.push('/purchase')
        })
        .catch(r => {});
    },

    //get provider by nit
    getProvider(nit) {
      let self = this;
      self.loading = true;
      self.$store.state.services.providerService
        .getByNit(nit)
        .then(r => {
          self.loading = false
          if(r.response){
            self.$swal({
                title: "¿información?",
                confirmButtonText: 'Si ir a proveedores!',
                cancelButtonText: 'No!',
                text: r.response.data.error+' desea ir a proveedores, y registrar nuevo proveedor?',
                type: "info",
                showCancelButton: true
            }).then((result) => { // <--
                if (result.value) { // <-- if confirmed
                    self.$router.push('/provider')
                }
            });
            return
          }
          self.form.provider_id = r.data.data.id,
          self.form.provider = r.data.data.name
        })
        .catch(r => {});
    },

    getProducts(){
        let self = this
        self.loading = true;

        self.$store.state.services.productService
        .getAll()
            .then(r => {
            self.loading = false
            self.products = r.data.data
        })
        .catch(r => {});
    },

    onKeyProvider(){
        let self = this
        self.$validator.validateAll().then((result) => {
          if (result) {
              self.getProvider(self.form.nit)
           }
      });
    },

    //agregar detalle de compra
    addDetail(){
        let self = this
        if(self.product === null){
            self.$toastr.error('debe seleccionar un producto','error')
            return
        }
        var exists = self.form.details.some(x=>x.product_id == self.product.id)
        if(exists){
            return
        }
        self.form.details.push({
            product: self.product.name,
            product_id: self.product.id,
            subtraction: self.product.quantify.subtraction,
            purcharse_price: 0.1,
            quantity: 1
        })
    },

    //remover detalle de compra
    removeDetail(item){
        let self = this
        var i = self.form.details.indexOf(item)
        self.form.details.splice(i,1)
    },

    //funcion, validar si se guarda o actualiza
    beforeCreate(){
      let self = this
      self.$validator.validateAll().then((result) => {
          if (result) {
              self.create()
           }
      });
    },

    subTotal(item){
        return (item.quantity * item.purcharse_price).toFixed(2)
    }

  },

  computed:{
      totalAmount(){
          let self = this
          var total = self.form.details.reduce((a,b)=>{
              return a + (b.quantity * b.purcharse_price)
          },0)
          self.form.total = total
          return total.toFixed(2)
      }
  }
};
</script>