<template>
<div>

  <div class="content-wrapper" v-loading="loading">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Menús</h1> 
          </div>
        </div>
      </div>
    </div>

    <div class="content">
      <div class="container-fluid">
        <div class="row">

          <div class="col-lg-12" v-if="edit_form">
            <div class="card text-white">
              <div class="card-header bg-primary text-center" style="font-size: 18px;"><div>Editar menú</div></div>
              <div class="card-body">
<form>
  <div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="form-group">
        <label>Menú</label>
        <input type="text" class="form-control" placeholder="titulo del menú"
        name="title"
        v-model="form.title"
        data-vv-as="titulo del menú"
        v-validate="'required|min:5|max:125'"
        data-vv-scope="menu"
        :class="{'input':true,'has-errors': errors.has('menu.title')}">
        <FormError :attribute_name="'menu.title'" :errors_form="errors"> </FormError>
        </div>
    </div>  
    <div class="col-md-12 col-sm-12">
        <div class="form-group">
        <label>Descripción</label>
        <textarea class="form-control" 
        cols="10" rows="3" 
        placeholder="descripción del menú"
        name="description"
        v-model="form.description"
        data-vv-as="descripción del menú"
        v-validate="'required|min:5|max:1000'"
        data-vv-scope="menu"
        :class="{'input':true,'has-errors': errors.has('menu.description')}">
        </textarea>
        <FormError :attribute_name="'menu.description'" :errors_form="errors"> </FormError>
        </div>
    </div>              
  </div>
  <div class="row">
    <div class="col-12 text-right">
      <button type="button" class="btn btn-danger btn-sm" v-b-tooltip.hover title="cancelar" @click="close"><i class="fa fa-undo"></i> Cancelar</button>
      <button type="button" class="btn btn-primary btn-sm" v-b-tooltip.hover title="guardar" @click="update"><i class="fa fa-save"></i> Guardar</button>
    </div>
  </div>
</form>
              </div>
            </div>
          </div>

          <div class="col-lg-12">
            <div class="card">
              <div class="card-header no-border">
                <div class="d-flex justify-content-between">
                    <h3 class="card-title">Lista de menús sugeridos
                        <router-link class="btn btn-success btn-sm" v-b-tooltip title="agregar" to="/new/menu" ><i class="fa fa-plus"></i> nuevo</router-link>
                    </h3>                
                </div>
              </div>
              <div class="card-body">
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

                        <template v-slot:row-details="data">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-12">
                                <div class="table-responsive">
                                    <table class="table table-striped hover bordered">
                                        <thead style="background: #28a745;">
                                            <tr class="text-center" style="font-size: 16px;">
                                                <th style="vertical-align:middle;">#</th>
                                                <th style="vertical-align:middle;">Producto</th>
                                                <th style="vertical-align:middle;">Observación</th>
                                            </tr>
                                        </thead>
                                        <tbody style="background: #17a2b8; font-size: 11px;">
                                            <template v-for="(row, index) in data.item.details">
                                                <tr v-if="row.current" v-bind:key="index">
                                                    <td style="vertical-align:middle; text-align: center; font-weight: bold;">
                                                        {{ index+1 }}
                                                    </td>   
                                                    <td style="vertical-align:middle; text-align: left; font-weight: bold;">
                                                        {{ row.product.name+' / '+row.product.presentation.name }}
                                                    </td>  
                                                    <td style="vertical-align:middle; text-align: left; font-weight: bold;">
                                                        {{ row.observation }}
                                                    </td>  
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>   
                                </div>                   
                            </div>
                        </div>
                        </template>

                        <template v-slot:cell(title)="data">
                        <div class="col-sm-12 text-center">
                            <b-button size="md" @click="data.toggleDetails" class="mr-2 btn-info">
                                {{ data.item.title }}
                            </b-button>
                        </div>
                        </template> 
                        <template v-slot:cell(person)="data">
                            <div class="text-center">{{ data.item.person.name_one+' '+data.item.person.last_name_one }}</div>
                        </template> 
                        <template v-slot:cell(created_at)="data">
                            <div class="text-center">{{ data.item.created_at | moment('dddd DD MMMM YYYY') }}</div>
                        </template>    
                        <template v-slot:cell(option)="data">
                            <router-link class="btn btn-success btn-sm" v-b-tooltip.hover v-b-tooltip.rightbottom title="mostrar información" :to="'/edit/menu/'+data.item.id"><i class="fa fa-eye"></i></router-link>
                            <button type="button" class="btn btn-warning btn-sm" v-b-tooltip.hover v-b-tooltip.rightbottom title="editar" @click="mapData(data.item)">
                                <i class="fa fa-pencil">
                                </i>
                            </button>                                                          
                            <button type="button" class="btn btn-danger btn-sm" v-b-tooltip.hover v-b-tooltip.rightbottom title="eliminar" @click="destroy(data.item)">
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
            
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</template>

<script>
import moment from 'moment'
import FormError from '../shared/FormError'
export default {
  name: "MenuSugge",
  components: {
      FormError,
      moment
  },
  data() {
    return {
      loading: false,
      modalidad: '',
      items: [],
      edit_form: false,
      fields: [
        { key: 'title', label: 'Título', sortable: true },
        { key: 'description', sortable: true },
        { key: 'person', label: 'Creador', sortable: true },
        { key: 'created_at', label: 'Fecha de creación', sortable: true },
        { key: 'option', label: 'Opción', sortable: false }
      ],
      form: {
        id: '',
        title: '',
        description: ''
      },
      filter: null,
      currentPage: 1,
      perPage: 5,
      totalRows: 0,
      pageOptions: [ 5, 10, 25 ],
      showStringEmpty: 'no hay registros que mostrar',    
    };
  },
  created() {
    let self = this;
    self.getAll();
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
      self.items = []

      self.$store.state.services.menusuggestionService
        .getAll()
        .then(r => {
          self.loading = false; 
          self.items = r.data.data;
          self.totalRows = self.items.length
        })
        .catch(r => {});
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
        self.form.title = data.title
        self.form.description = data.description
        self.edit_form = true
    },   
    
    //cerrar modal limpiar registros
    close(){
        let self= this
        self.edit_form = false
    },

    //funcion, validar si se guarda o actualiza
    update(){
      let self = this      
      let data = self.form
      self.$validator.validateAll('menu').then((result) => {
          if (result) {
              self.loading = true
              self.pasarMayusculas()
              self.$store.state.services.menusuggestionService
                .update(data)
                .then(r => {
                  self.loading = false
                  if( self.interceptar_error(r) == 0) return 
                  self.$toastr.success('registro actualizado con exito', 'exito') 
                  self.getAll()
                  self.close()
                })
                .catch(r => {});
           }
      });
    },    

    //funcion para eliminar registro
    destroy(data){
      let self = this

      self.$swal({
        title: "¿ELIMINAR MENU?",
        text: "¿ ESTA SEGURO QUE DESEA ELIMINAR EL MENU ?",
        type: "warning",
        showCancelButton: true
      }).then((result) => {
          if (result.value) { 
              self.loading = true
              self.$store.state.services.menusuggestionService
                .destroy(data)
                .then(r => {
                    self.loading = false
                    if( self.interceptar_error(r) == 0) return
                    self.$toastr.success('registro eliminado con exito', 'exito') 
                    self.getAll()
                    self.close()
                })
                .catch(r => {});
          }
      });
    },

  },
  mounted(){
    $("body").resize()
  },
};
</script>