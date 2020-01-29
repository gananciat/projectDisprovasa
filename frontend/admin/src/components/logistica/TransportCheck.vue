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
            <div class="col-md-12 col-sm-12 col-12"  style="background: #17a2b8;" v-if="!ocultar">
              <div class="form-group">
                <label>Vehículo</label>
                <multiselect v-model="vehicle"
                    v-validate="'required'" 
                    data-vv-name="vehicle"
                    data-vv-as="un vehículo"
                    :options="vehicles" placeholder="seleccione un vehículo"  
                    :searchable="true"
                    :allow-empty="false"
                    :show-labels="false"
                    label="name" track-by="id">
                    <span slot="noResult">No se encontro ningún registro</span>
                    </multiselect>
                    <FormError :attribute_name="'vehicle'" :errors_form="errors"> </FormError>
              </div>                
            </div>
            &nbsp;
            <div class="col-md-12 col-sm-12 col-12">
                <div class="table-responsive">
                    <table class="table table-striped hover bordered" style="background: #17a2b8;">
                        <thead>
                            <tr class="text-center" style="font-size: 18px;">
                                <th>#</th>
                                <th>Cantidad pedida</th>  
                                <th>Cantidad para entregar</th>                                
                                <th>Producto</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="(item, index) in items">
                                <tr v-bind:key="index">
                                    <td style="vertical-align:middle; font-size: 14px; text-align: center; font-weight: bold;">
                                        {{ index+1 }}
                                    </td>
                                    <td style="vertical-align:middle; font-size: 14px; text-align: center; font-weight: bold;">
                                        {{ Number(item.original) }}
                                    </td>   
                                    <td style="vertical-align:middle; font-size: 14px; text-align: center; font-weight: bold;">
                                        {{ Number(item.asign) }}
                                    </td>                                      
                                    <td style="vertical-align:middle; font-size: 14px; text-align: left; font-weight: bold;">
                                        <span>{{ item.product+' / '+item.marca }}</span>    
                                    </td>                                                                       
                                    <td style="vertical-align:middle; font-size: 14px; text-align: center; font-weight: bold;">
                                        <button v-if="!item.on_route" type="button" @click="check(item)" class="btn btn-success btn-sm" v-b-tooltip title="check"><i class="fa fa-save"></i></button>
                                        <button v-if="item.on_route" type="button" @click="destroy(item,index+1)" class="btn btn-danger btn-sm" v-b-tooltip title="eliminar"><i class="fa fa-trash"></i></button>
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
  name: "transportcheck",
  components: {
      FormError
  },
  data() {
    return {
      loading: false,
      order_id: 0,
      ocultar: false,
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
      vehicle: '',
      vehicles: []
    };
  },
  created() {
      let self = this
      self.order_id = self.$route.params.id
      self.getAll()
      self.getVehicle()
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

      self.$store.state.services.checkdeliverymanService
        .get(self.order_id)
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
        })
        .catch(r => {});
    },
    getVehicle(){
      let self = this;
      self.loading = true;

      self.$store.state.services.deliverymanService
        .getAll()
        .then(r => {
          self.loading = false; 
          r.data.data.forEach(function (item) {
              self.vehicles.push({id: item.vehicle.id, name: item.vehicle.model.brand_model+' / '+item.vehicle.plate.type+'-'+item.vehicle.placa})
          });
        })
        .catch(r => {});
    },
    check(item){
        let self = this

        self.$validator.validateAll().then((result) => {
            if (result) {

                self.$swal({
                    title: "ADVERTENCIA",
                    text: "¿ESTA SEGURO QUE QUIERE AGREGAR EL PRODUCTO "+item.product+' / '+item.marca+ ', AL VEHICULO?',
                    type: "warning",
                    showCancelButton: true
                }).then((result) => { 
                    if (result.value) { 
                        self.loading = true
                        let data = {}
                        data.progress_orders_id = item.progress_orders_id
                        data.vehicles_id = self.vehicle.id
                        
                        self.$store.state.services.checkdeliverymanService
                            .create(data)
                            .then(r => {
                                self.loading = false
                                if( self.interceptar_error(r) == 0) return 
                                if(r.data.ready)
                                {
                                    self.$router.push('/transport') 
                                }
                                else
                                {
                                    self.$toastr.success('producto ingresado al vehículo', 'exito')  
                                    self.getAll()  
                                    self.ocultar = true
                                    self.vehicles.forEach(function (history) {
                                        if(history.id == r.data.vehicle)
                                        {
                                            self.vehicle = history
                                        }
                                    }); 
                                }                                      
                            })
                            .catch(r => {}); 
                    }
                });
            }
        });       
    },
    destroy(item,index){
      let self = this

      self.$swal({
        title: "ADVERTENCIA",
        text: "¿ESTA SEGURO QUE DESEA ELIMINAR EL CHECK EN LA POSICION #"+index+"?",
        type: "warning",
        showCancelButton: true
      }).then((result) => { 
          if (result.value) { 
              self.loading = true
              let data = {}
              data.id = item.check
              self.$store.state.services.checkdeliverymanService
                .destroy(data)
                .then(r => {
                  self.loading = false
                  if( self.interceptar_error(r) == 0) return
                  self.$toastr.success('registro eliminado con exito', 'exito')
                  self.getAll()
                })
                .catch(r => {});
          }
      });
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