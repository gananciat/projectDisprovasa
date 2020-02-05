<template>
<div v-loading="loading">
  <div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Vehículos</h1> 
          </div>
        </div>
      </div>
    </div>

    <div class="content">
      <div class="container-fluid">

        <!-- Modal para nuevo registro -->
        <b-modal ref="nuevo" :title="title" size="md" hide-footer class="modal-backdrop" no-close-on-backdrop>
          <form>
              <div class="row">
                    <div class="col-sm-12 col-md-12">
                          <label for="vehicle_models_id">Modelo</label>
                          <multiselect v-model="form.vehicle_models_id"
                              v-validate="'required'" 
                              data-vv-name="vehicle_models_id"
                              data-vv-as="modelo del vehículo"
                              :options="modelos" placeholder="seleccione un modelo"  
                              :searchable="true"
                              :allow-empty="false"
                              :show-labels="false"
                              label="brand_model" track-by="id">
                              <span slot="noResult">Ninguna registro encontrado</span>
                        </multiselect>
                        <FormError :attribute_name="'vehicle_models_id'" :errors_form="errors"> </FormError>
                    </div>
                    <div class="col-sm-12 col-md-12">
                          <label for="license_plates_id">Tipo de placa</label>
                          <multiselect v-model="form.license_plates_id"
                              v-validate="'required'" 
                              data-vv-name="license_plates_id"
                              data-vv-as="tipo de placa"
                              :options="placas" placeholder="seleccione un tipo de placa"  
                              :searchable="true"
                              :allow-empty="false"
                              :show-labels="false"
                              label="type" track-by="id">
                              <span slot="noResult">Ninguna registro encontrado</span>
                        </multiselect>
                        <FormError :attribute_name="'license_plates_id'" :errors_form="errors"> </FormError>
                    </div>
                    <div class="col-sm-12 col-md-12">
                        <div class="form-group">
                            <label>Número de placa</label>
                            <input type="text" class="form-control" placeholder="número de placa"
                            name="placa"
                            v-model="form.placa"
                            data-vv-as="placa"
                            v-validate="'required|min:6|max:6'"
                            :class="{'input':true,'has-errors': errors.has('placa')}">
                            <FormError :attribute_name="'placa'" :errors_form="errors"> </FormError>
                        </div>                        
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div class="form-group">
                            <label>Número de Chasis</label>
                            <input type="text" class="form-control" placeholder="número de chasis"
                            name="chasis"
                            v-model="form.chasis"
                            data-vv-as="chasis"
                            v-validate="'required|min:10|max:15'"
                            :class="{'input':true,'has-errors': errors.has('chasis')}">
                            <FormError :attribute_name="'chasis'" :errors_form="errors"> </FormError>
                        </div>                        
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div class="form-group">
                            <label>Número de VIN</label>
                            <input type="text" class="form-control" placeholder="número de vin"
                            name="vin"
                            v-model="form.vin"
                            data-vv-as="vin"
                            v-validate="'required|min:10|max:15'"
                            :class="{'input':true,'has-errors': errors.has('vin')}">
                            <FormError :attribute_name="'vin'" :errors_form="errors"> </FormError>
                        </div>                        
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <div class="form-group">
                            <label>Motor C.C.</label>
                            <input type="text" class="form-control" placeholder="motor"
                            name="motor"
                            v-model="form.motor"
                            data-vv-as="motor"
                            v-validate="'required|min:4|max:4|integer'"
                            :class="{'input':true,'has-errors': errors.has('motor')}">
                            <FormError :attribute_name="'motor'" :errors_form="errors"> </FormError>
                        </div>                        
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <div class="form-group">
                            <label>Año</label>
                            <input type="text" class="form-control" placeholder="año"
                            name="anio"
                            v-model="form.anio"
                            data-vv-as="anio"
                            v-validate="'required|min:4|max:4|integer'"
                            :class="{'input':true,'has-errors': errors.has('anio')}">
                            <FormError :attribute_name="'anio'" :errors_form="errors"> </FormError>
                        </div>                        
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <div class="form-group">
                            <label>Color</label>
                            <input type="text" class="form-control" placeholder="color"
                            name="color"
                            v-model="form.color"
                            data-vv-as="color"
                            v-validate="'required|max:15'"
                            :class="{'input':true,'has-errors': errors.has('color')}">
                            <FormError :attribute_name="'color'" :errors_form="errors"> </FormError>
                        </div>                        
                    </div>
              </div>
              <div class="row">
                <div class="col-12 text-right">
                  <button type="button" class="btn btn-danger btn-sm" @click="close"><i class="fa fa-undo"></i> Cancelar</button>
                  <button type="button" class="btn btn-primary btn-sm" @click="createOrEdit"><i class="fa fa-save"></i> Guardar</button>
                </div>
              </div>
            </form>
        </b-modal>

        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header no-border">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">Lista de vechículos 
                    <b-button variant="success" @click="open" size="sm"><i class="fa fa-plus"></i> nuevo</b-button></h3>
                </div>
              </div>
              <div class="card-body">
                <div class="d-flex">
                  <p class="d-flex flex-column">
                  </p>
                </div>
                <!-- /.d-flex -->
                 <template>

                    <div class="col-sm-12">
                      <b-row>

                      <b-col md="4" class="my-1 form-inline">
                        <label>mostrando: </label>
                            <b-form-select :options="pageOptions" v-model="perPage" />
                          <label>entradas </label>
                      </b-col>

                      <b-col  class="my-1 form-group pull-right">
                          <b-input-group>
                            <b-form-input v-model="filter" placeholder="buscar" />
                          </b-input-group>
                      </b-col>
                    </b-row>
                    <b-table responsive hover small flex
                        bordered
                       :fields="fields" 
                       :items="items"
                       :filter = "filter"
                       :current-page="currentPage"
                       :per-page="perPage"
                       @filtered="onFiltered">
                      <!-- A virtual column -->
                      <template v-slot:cell(placa)="data">
                        {{ data.item.type_plate+'-'+data.item.placa }}
                      </template>
                      <template v-slot:cell(option)="data">
                          <button type="button" class="btn btn-info btn-sm" @click="mapData(data.item)" v-b-tooltip title="editar">
                              <i class="fa fa-pencil">
                              </i>
                          </button>
                          <button type="button" class="btn btn-danger btn-sm" @click="destroy(data.item)" v-b-tooltip title="eliminar">
                              <i class="fa fa-trash">
                              </i>
                          </button>
                      </template>

                    </b-table>
                    <b-row>
                        <b-col md="12" class="my-1">
                           <label v-if="totalRows > 0">total: {{totalRows}} registros</label> 
                           <div class="text-center">
                              <label v-if="totalRows === 0">No hay registros que mostrar</label> 
                           </div>   
                         </b-col>
                         <div class="pull-right col-md-12">
                          <div class="mt-3" v-if="totalRows > 0">
                              <b-pagination 
                                v-model="currentPage" 
                                :per-page="perPage" 
                                :total-rows="totalRows" 
                                align="right"
                                first-text="⏮"
                                prev-text="⏪"
                                next-text="⏩"
                                last-text="⏭">
                              </b-pagination>
                            </div>
                         </div>
                    </b-row>
                    </div>
                  </template>
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
import FormError from '../shared/FormError'
export default {
  name: "category",
  components: {
      FormError
  },
  data() {
    return {
      loading: false,
      items: [],
      placas: [],
      modelos: [],
      fields: [
        { key: 'brand_model', label: 'Vechículo', sortable: true },
        { key: 'placa', label: 'Placa', sortable: true },
        { key: 'chasis', label: 'Chasis', sortable: true },
        { key: 'motor', label: 'Motor C.C.', sortable: true },
        { key: 'color', label: 'Color', sortable: true },
        { key: 'anio', label: 'Año', sortable: true },
        { key: 'option', label: 'Opciones', sortable: false },
      ],
      filter: null,
      currentPage: 1,
      perPage: 5,
      totalRows: 0,
      pageOptions: [ 5, 10, 25 ],
      showStringEmpty: 'no hay registros que mostrar',

      form: {
          id: null,
          placa: '',
          anio: '',
          vin: '',
          chasis: '',
          motor: '',
          license_plates_id: null,
          vehicle_models_id: null
      }
    };
  },
  created() {
    let self = this;
    self.getAll();
    self.getPlaca();
    self.getModelo()
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

    onFiltered (filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length
      this.currentPage = 1
    },

    getAll() {
      let self = this;
      self.loading = true;

      self.$store.state.services.vehicleService
        .getAll()
        .then(r => {
          self.loading = false; 
          self.items = r.data.data;
          self.totalRows = self.items.length
          
          self.close()
        })
        .catch(r => {});
    },

    //funcion para guardar registro
    create(){
      let self = this
      self.loading = true
      let data = {}
      data.placa = self.form.placa
      data.color = self.form.color
      data.anio = self.form.anio
      data.vin = self.form.vin
      data.chasis = self.form.chasis
      data.motor = self.form.motor
      data.license_plates_id = self.form.license_plates_id.id
      data.vehicle_models_id = self.form.vehicle_models_id.id

      self.$store.state.services.vehicleService
        .create(data)
        .then(r => {
          self.loading = false
          if( self.interceptar_error(r) == 0) return
          self.$toastr.success('registro agregado con exito', 'exito')
          self.getAll()
        })
        .catch(r => {});
    },

    //funcion para actualizar registro
    update(){
      let self = this
      self.loading = true
      let data = {}
      data.id = self.form.id
      data.placa = self.form.placa
      data.color = self.form.color
      data.anio = self.form.anio
      data.vin = self.form.vin
      data.chasis = self.form.chasis
      data.motor = self.form.motor
      data.license_plates_id = self.form.license_plates_id.id
      data.vehicle_models_id = self.form.vehicle_models_id.id
       
      self.$store.state.services.vehicleService
        .update(data)
        .then(r => {
          self.loading = false
          if( self.interceptar_error(r) == 0) return
          self.$toastr.success('registro modificado con exito', 'exito')
          self.getAll()
        })
        .catch(r => {});
    },

    //funcion para eliminar registro
    destroy(data){
      let self = this

      self.$swal({
        title: "¿ELIMINAR REGISTRO?",
        text: "¿ESTA SEGURO QUE DESEA ELIMINAR EL VEHICULO CON PLACAS "+data.type_plate+'-'+data.placa+ "?",
        type: "warning",
        showCancelButton: true
      }).then((result) => {
          if (result.value) {
              self.loading = true
              self.$store.state.services.vehicleService
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

    //funcion, validar si se guarda o actualiza
    createOrEdit(){
      this.$validator.validateAll().then((result) => {
          if (result) {
              self.pasarMayusculas()
              self.form.id === null ? self.create() : self.update()
           }
      });

      let self = this
    },

    //pasar a mayusculas
    pasarMayusculas(){
        let self = this

        Object.keys(self.form).forEach(function(key,index) {
          
          if(typeof self.form[key] === "string") 
            self.form[key] = self.form[key].toUpperCase()

        });
    }, 

     //mapear datos a formulario
    mapData(data){
        let self = this
        self.form.id = data.id
        self.form.placa = data.placa
        self.form.color = data.color
        self.form.vin = data.vin
        self.form.anio = data.anio
        self.form.chasis = data.chasis
        self.form.motor = data.motor
        self.form.license_plates_id = {id: data.license_plates_id, type: data.type_plate}
        self.form.vehicle_models_id = {id: data.vehicle_models_id, brand_model: data.brand_model}
        this.$refs['nuevo'].show()
    },

    //limpiar data de formulario
    clearData(){
        let self = this

        Object.keys(self.form).forEach(function(key,index) {
          if(typeof self.form[key] === "string") 
            self.form[key] = ''
          else if (typeof self.form[key] === "boolean") 
            self.form[key] = true
          else if (typeof self.form[key] === "number") 
            self.form[key] = null
        });

        self.form.license_plates_id = null
        self.form.vehicle_models_id = null

        self.$validator.reset()
        self.$validator.reset()
    },

    open(){
        let self = this
        this.$refs['nuevo'].show()
        self.clearData()
    },

    //cerrar modal limpiar registros
    close(){
        let self= this
        self.$refs['nuevo'].hide()
    },
    
    getPlaca(){
        let self = this
        self.loading = true
        self.placas = []

        self.$store.state.services.licenseplateService
        .getAll()
        .then(r => {
            self.placas = r.data.data;
            self.loading = false; 
        })
        .catch(r => {});
    },
    
    getModelo(){
        let self = this
        self.loading = true
        self.modelos = []

        self.$store.state.services.vehiclemodelService
        .getAll()
        .then(r => {
            self.loading = false; 
            self.modelos = r.data.data
        })
        .catch(r => {});
    }
  },

  mounted(){
        $("body").resize()
  },

  computed:{
        title(){
            let self = this
            return self.form.id == null ? 'Nuevo vehículo' : 'Editar vehículo con placas'+self.form.license_plates_id.type+'-'+self.form.placa
        }
  }
};
</script>