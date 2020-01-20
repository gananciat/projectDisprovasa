<template>
<div>

  <div class="content-wrapper" v-loading="loading">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          </div>
        </div>
      </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <b-jumbotron :header="'Detalle de la orden #'+information.order">
        <hr>
        
        <div class="row">
            <div class="col-md-12 col-sm-12 col-12">
                <div class="card card-widget widget-user">
                    <div class="widget-user-header bg-info">
                        <h3 class="widget-user-username">{{ information.school }}</h3>
                        <h5 class="widget-user-desc">{{ information.code }}</h5>
                    </div>
                    <div class="widget-user-image">
                        <img class="img-circle elevation-2" :src="getLogo(information.logo)" alt="User Avatar">
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-sm-3 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">{{ information.title }}</h5>
                                    <span class="description-text">Menú</span>
                                </div>
                            </div>

                            <div class="col-sm-3 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">{{ information.type_order }}</h5>
                                    <span class="description-text">Pedido</span>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="description-block">
                                <h5 class="description-header">{{ information.date | moment('dddd DD MMMM YYYY') }}</h5>
                                <span class="description-text">Fecha de entrega</span>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="description-block">
                                <h5 class="description-header">{{ information.total | currency('Q ',',',2,'.','front',true) }}</h5>
                                <span class="description-text">Monto</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                
            </div>

            <div class="col-md-12 col-sm-12 col-12">
                <div class="table-responsive">
                    <table class="table table-striped hover bordered" style="background: #17a2b8;">
                        <thead>
                            <tr class="text-center" style="font-size: 18px;">
                                <th>#</th>
                                <th>Producto</th>
                                <th>Cantidad requerida</th>
                                <th>Cantidad confirmada</th>
                                <th>Faltante</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="(item, index) in items">
                                <tr v-bind:key="index">
                                    <td style="vertical-align:middle; font-size: 14px; text-align: center; font-weight: bold;">
                                        {{ index+1 }}
                                    </td>
                                    <td style="vertical-align:middle; font-size: 14px; text-align: left; font-weight: bold;">
                                        <span v-b-tooltip :title="'stock '+item.product.stock">{{ item.product.name+' / '+item.product.presentation.name }}</span>    
                                    </td>                                     
                                    <td style="vertical-align:middle; font-size: 14px; text-align: center; font-weight: bold;">
                                        {{ Number(item.progress.original_quantity) }}
                                    </td>                                    
                                    <td style="vertical-align:middle; text-align: center;">
                                        <el-input-number v-model="item.progress.purchased_amount" 
                                        :precision="0" :step="1" :min="0" :max="Number(item.progress.original_quantity)"
                                        step-strictly
                                        :data-vv-name="'validate'+index+'.quantity'"
                                        data-vv-as="cantidad"
                                        @change="stock_validate(item.product.stock,item.progress.purchased_amount,index)"
                                        v-validate="'required|between:0,'+Number(item.progress.original_quantity)"
                                        :data-vv-scope="'validate'+index"
                                        :class="{'input':true,'has-errors': errors.has('validate'+index+'.quantity')}"></el-input-number> <br>
                                        <FormError :attribute_name="'validate'+index+'.quantity'" :errors_form="errors"> </FormError>                                        
                                    </td>                                     
                                    <td style="vertical-align:middle; font-size: 14px; text-align: center; font-weight: bold;">
                                        {{ Number(item.progress.original_quantity - item.progress.purchased_amount) }}
                                    </td>
                                    <td style="vertical-align:middle; font-size: 14px; text-align: center; font-weight: bold;">
                                        <button type="button" @click="update(index,item)" class="btn btn-success btn-sm" v-b-tooltip title="guardar"><i class="fa fa-save"></i></button>
                                        <button type="button" @click="entregar(item)" class="btn btn-info btn-sm" v-b-tooltip title="subir al vehículo"><i class="fa fa-suitcase"></i></button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>   
                </div>                   
            </div>
        </div>
      
        </b-jumbotron>
      </div>
    </div>
    <!-- /.content -->
  </div>
</div>
</template>

<script>
import FormError from '../shared/FormError'
export default {
  name: "assignproduct",
  components: {
      FormError
  },
  data() {
    return {
      loading: false,
      order_id: 0,
      information: {
          order: '',
          school: '',
          code: '',
          logo: '',
          title: '',
          type_order: '',
          date: new Date(),
          total: ''
      },
      items: [],
    };
  },
  created() {
      let self = this
      self.order_id = self.$route.params.id
      self.getAll()
  },

  methods: {
    //Clasificar error
    interceptar_error(r){
      let self = this
      let error = 1;

        if(r.response){
            if(r.response.status === 422){
                this.$toastr.info(r.response.data.error, 'Mensaje')
                error = 0
            }

            if(r.response.status != 201 && r.response.status != 422){
                for (let value of Object.values(r.response.data)) {
                    self.$toastr.error(value, 'Mensaje')
                }
                error = 0
            }
        }
      
      return error
    },

    getAll() {
      let self = this;
      self.loading = true;

      self.$store.state.services.progressorderService
        .getProgress(self.order_id)
        .then(r => {
          self.loading = false; 
          self.items = r.data.data;
          if(self.items.length != 0){
              self.information.order = self.items[0].orders.order
              self.information.school = self.items[0].orders.school.name
              self.information.code = self.items[0].orders.code
              self.information.logo = self.items[0].orders.school.logo
              self.information.title = self.items[0].orders.title
              self.information.type_order = self.items[0].orders.type_order
              self.information.date = self.items[0].orders.date
              self.information.total = self.items[0].orders.total
          }
          self.information.
          self.view_u = true
        })
        .catch(r => {});
    },

    stock_validate(stock,cantidad,index){
        let self = this
        if(stock < cantidad)
        {
            self.$swal({
                title: "Advertencia",
                text: "No hay suficiente stock.",
                type: "warning",
                showCancelButton: false
            })

            return true
        }

        return false
    },

    update(index, item){
        let self = this
        if(self.stock_validate(item.product.stock,item.progress.purchased_amount))
            return

        self.$validator.validateAll('validate'+index).then((result) => {
            if (result) {
                self.loading = true
                let data = {}
                data.id = item.progress.id
                data.purchased_amount = item.progress.purchased_amount
                
                self.$store.state.services.progressorderService
                    .update(data)
                    .then(r => {
                        self.loading = false
                        if( self.interceptar_error(r) == 0) return  
                        if(r.data.data.complete){ 
                            self.$toastr.success('producto listo para entregar', 'exito')  
                            self.getAll() 
                        }
                        else{
                            self.$toastr.success('registro actualizado con exito', 'exito')
                        }
                    })
                    .catch(r => {});
            }
        });
    },

    entregar(data){
        let self = this
        self.loading = true
        let data = {}
        data.detail_orders_id = item.id
        
        self.$store.state.services.progressorderService
            .create(data)
            .then(r => {
                self.loading = false
                if( self.interceptar_error(r) == 0) return
                self.$toastr.success('producto listo para entregar', 'exito')  
                if(r.data.data.complete){ self.getAll() }
            })
            .catch(r => {});
    },

    getLogo(logo){
      let self = this
      return logo !== null ? self.$store.state.base_url+logo : self.$store.state.base_url+'img/logo_empty.png'
    }    
  },

  mounted(){
        $("body").resize()
  },  
};
</script>