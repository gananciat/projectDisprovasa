<template>
<div v-loading="loading">

  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Companias</h1> 
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <!-- Modal para nuevo registro -->
        <b-modal ref="nuevo" :title="title" hide-footer class="modal-backdrop" no-close-on-backdrop>
          <form>
              <div class="form-group">
                <label>Nombre</label>
                <input type="text" class="form-control" placeholder="nombre"
                name="name"
                v-model="form.name"
                data-vv-as="nombre"
                v-validate="'required'"
              :class="{'input':true,'has-errors': errors.has('name')}">
              <FormError :attribute_name="'name'" :errors_form="errors"> </FormError>
              </div>
              <div class="row">
                <!-- /.col -->
                <div class="col-12 text-right">
                  <button type="button" class="btn btn-danger btn-sm" @click="close"><i class="fa fa-undo"></i> Cancelar</button>
                  <button type="button" class="btn btn-primary btn-sm" @click="createOrEdit"><i class="fa fa-save"></i> Guardar</button>
                </div>
                <!-- /.col -->
              </div>
            </form>
        </b-modal>

        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header no-border">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">Lista de companias 
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
                      <template v-slot:cell(name)="data">
                        {{ data.item.name }}
                      </template>
                      <template v-slot:cell(description)="data">
                        {{data.item.description}}
                      </template>
                      <template v-slot:cell(option)="data">
                          <button type="button" class="btn btn-info btn-sm" @click="mapData(data.item)" v-tooltip="'editar'">
                              <i class="fa fa-pencil">
                              </i>
                          </button>
                          <button type="button" class="btn btn-danger btn-sm" @click="destroy(data.item)" v-tooltip="'eliminar'">
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
  name: "company",
  components: {
      FormError
  },
  data() {
    return {
      loading: false,
      items: [],
      fields: [
        { key: 'name', label: 'Nombre', sortable: true },
        { key: 'option', label: 'Opciones', sortable: true },
      ],
      filter: null,
      currentPage: 1,
      perPage: 5,
      totalRows: 0,
      pageOptions: [ 5, 10, 25 ],
      showStringEmpty: 'no hay registros que mostrar',

      form: {
          id: null,
          name: '',
      }
    };
  },
  created() {
    let self = this;
    self.getAll();
  },

  methods: {

    onFiltered (filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length
      this.currentPage = 1
    },

    getAll() {
      let self = this;
      self.loading = true;

      self.$store.state.services.companyService
        .getAll()
        .then(r => {
          self.loading = false; 
          self.items = r.data.data;
          self.totalRows = self.items.length
        })
        .catch(r => {});
    },

    //funcion para guardar registro
    create(){
      let self = this
      self.loading = true
      let data = self.form
      self.$store.state.services.companyService
        .create(data)
        .then(r => {
          self.loading = false
          if(r.response){
            this.$toastr.error(r.response.data.error, 'error')
            return
          }
          this.$toastr.success('registro agregado con exito', 'exito')
          self.close()
          self.getAll()
        })
        .catch(r => {});
    },

    //funcion para actualizar registro
    update(){
      let self = this
      self.loading = true
      let data = self.form
       
      self.$store.state.services.companyService
        .update(data)
        .then(r => {
          self.loading = false
          if(r.response){
            this.$toastr.error(r.response.data.error, 'error')
            return
          }
          this.$toastr.success('registro actualizado con exito', 'exito')
          self.getAll()
          self.close()
        })
        .catch(r => {});
    },

    //funcion para eliminar registro
    destroy(data){
      let self = this

      self.$swal({
        title: "¿Eliminar registro?",
        text: "Esta seguro de elminar "+data.name + '?',
        type: "warning",
        showCancelButton: true
      }).then((result) => { // <--
          if (result.value) { // <-- if confirmed
              self.loading = true
              self.$store.state.services.companyService
                .destroy(data)
                .then(r => {
                  self.loading = false
                  if(r.response){
                        this.$toastr.error(r.response.data.error, 'error')
                        return
                    }
                  this.$toastr.success('registro eliminado con exito', 'exito')
                  self.getAll()
                  self.close()
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

     //mapear datos a formulario
    mapData(data){
        let self = this
        self.form.id = data.id
        self.form.name = data.name
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

        self.$validator.reset()
        self.$validator.reset()
    },

    //pasar a mayusculas
    pasarMayusculas(){
        let self = this

        Object.keys(self.form).forEach(function(key,index) {
          
          if(typeof self.form[key] === "string") 
            self.form[key] = self.form[key].toUpperCase()

        });
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
    }
  },

  mounted(){
        $("body").resize()
  },

  computed:{
      title(){
          let self = this
          return self.form.id == null ? 'Nueva compañia' : 'Editar '+self.form.name
      }
  }
};
</script>