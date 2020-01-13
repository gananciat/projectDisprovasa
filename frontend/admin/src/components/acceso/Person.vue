<template>
<div v-loading="loading">
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Usuarios</h1> 
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">

        <div class="row">
          <div class="col-lg-12">
            <!-- Modal para nuevo registro -->
            <b-modal ref="nuevo" :title="title" hide-footer class="modal-backdrop" no-close-on-backdrop size="xl">
              <form data-vv-scope="form">
                  <div class="row">
                      <div class="form-group col-md-6 col-sm-6 col-xs-12 col-lg-6">
                      <label>Primer nombre</label>
                      <input type="text" class="form-control" placeholder="primer nombre"
                      name="name_one"
                      v-model="form.name_one"
                      data-vv-as="primer nombre"
                      v-validate="'required'"
                      :class="{'input':true,'has-errors': errors.has('form.name_one')}">
                      <FormError :attribute_name="'form.name_one'" :errors_form="errors"> </FormError>
                      </div>
                      <div class="form-group col-md-6 col-sm-6 col-xs-12 col-lg-6">
                      <label>Segundo nombre</label>
                      <input type="text" class="form-control" placeholder="segundo nombre"
                      name="name_two"
                      v-model="form.name_two">
                      </div>
                  </div>
                  <div class="row">
                      <div class="form-group col-md-6 col-sm-6 col-xs-12 col-lg-6">
                      <label>Primer apellido</label>
                      <input type="text" class="form-control" placeholder="primer apellido"
                      name="last_name_one"
                      v-model="form.last_name_one"
                      data-vv-as="primer apellido"
                      v-validate="'required'"
                      :class="{'input':true,'has-errors': errors.has('form.last_name_one')}">
                      <FormError :attribute_name="'form.last_name_one'" :errors_form="errors"> </FormError>
                      </div>
                      <div class="form-group col-md-6 col-sm-6 col-xs-12 col-lg-6">
                      <label>Segundo apellido</label>
                      <input type="text" class="form-control" placeholder="segundo nombre"
                      name="last_name_two"
                      v-model="form.last_name_two">
                      </div>
                  </div>
                  <div class="row">
                      <div class="form-group col-md-6 col-sm-6 col-xs-12 col-lg-6">
                          <label>CUI</label>
                          <input type="text" class="form-control" placeholder="CUI"
                          name="cui"
                          v-model="form.cui"
                          data-vv-as="cui"
                          v-validate="'required|max:13|min:3'"
                          :class="{'input':true,'has-errors': errors.has('form.cui')}">
                          <FormError :attribute_name="'form.cui'" :errors_form="errors"> </FormError>
                      </div>
                      <div class="form-group col-md-6 col-sm-6 col-lg-6">
                          <label for="categoria">Departamento/Municipio</label>
                          <multiselect v-model="municipality"
                              v-validate="'required'" 
                              data-vv-name="municipality"
                              data-vv-as="departamento y municipio"
                              :options="municipalities" placeholder="seleccione departamento municipio"  
                              :searchable="true"
                              :allow-empty="false"
                              :custom-label="nameWithLang"
                              label="name" track-by="id"
                              >
                              <span slot="noResult">Ninguna categoría encontrada</span>
                              </multiselect>
                              <FormError :attribute_name="'form.municipality'" :errors_form="errors"> </FormError>
                      </div>
                  </div>
                   <div class="row">
                      <div class="form-group col-md-6 col-sm-6 col-lg-6">
                          <label for="email">Correo electronico</label>
                          <input type="text" class="form-control" placeholder="dirección"
                          name="email"
                          v-model="form.email"
                          data-vv-as="email"
                          v-validate="'required|email'"
                          :class="{'input':true,'has-errors': errors.has('form.email')}">
                              <FormError :attribute_name="'form.email'" :errors_form="errors"> </FormError>
                      </div>
                      <div class="form-group col-md-6 col-sm-6 col-lg-6">
                          <label for="categoria">Rol</label>
                          <multiselect v-model="rol"
                              v-validate="'required'" 
                              data-vv-name="rol"
                              data-vv-as="rol usuario"
                              :options="rols" placeholder="seleccione rol"  
                              :searchable="true"
                              :allow-empty="false"
                              label="name" track-by="id">
                              <span slot="noResult">Ninguna rol encontrada</span>
                              </multiselect>
                              <FormError :attribute_name="'form.rol'" :errors_form="errors"> </FormError>
                      </div>
                      
                  </div>
                  <div class="row">
                      <div class="form-group col-md-12 col-sm-12 col-lg-12">
                          <label for="categoria">Dirección</label>
                          <input type="text" class="form-control" placeholder="dirección"
                          name="direction"
                          v-model="form.direction"
                          data-vv-as="dirección"
                          v-validate="'required'"
                          :class="{'input':true,'has-errors': errors.has('form.direction')}">
                              <FormError :attribute_name="'form.direction'" :errors_form="errors"> </FormError>
                      </div>
                      
                  </div>
                  
                </form>
                  <div class="row">
                    
                      <div class="col-md-8 col-sm-8 col-lg-8">
                        <div class="card">
                            <div class="card-header">
                              <h3 class="card-title">Telefonos</h3>
                            </div>

                            <div class="card-body">
                              <form data-vv-scope="form_telefono">
                                <div class="row">
                                  <div class="input-group input-group-sm form-group col-md-6">
                                    <multiselect v-model="company"
                                      v-validate="'required'" 
                                      data-vv-name="company"
                                      data-vv-as="compania telefonica"
                                      :options="companies" placeholder="compania"  
                                      :searchable="true"
                                      :allow-empty="false"
                                      label="name" track-by="id">
                                      <span slot="noResult">Ninguna compania encontrada</span>
                                      </multiselect>
                                      <FormError :attribute_name="'form_telefono.company'" :errors_form="errors"> </FormError>
                                  </div>
                                  
                                  <div class="input-group input-group-sm form-group col-md-6">
                                    <input placeholder="numero de telefono" type="number" class="form-control" v-model="phone"
                                    data-vv-as="telefono"
                                    name="telefono"
                                    v-validate="'required'"
                                    :class="{'input':true,'has-errors': errors.has('form_telefono.telefono')}">
                                    <span class="input-group-append">
                                      <button @click="addPhone" type="button" class="btn btn-success btn-flat"><i class="fa fa-plus"></i></button>
                                    </span>

                                    
                                    <FormError :attribute_name="'form_telefono.telefono'" :errors_form="errors"> </FormError>
                                  </div>
                                </div>
                              </form>

                              <table class="table table-bordered">
                                <thead>                  
                                  <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Número</th>
                                    <th>Compania</th>
                                    <th></th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr v-for="(item, index) in form.phones" :key="item.id">
                                    <td>{{index+1}}</td>
                                    <td>{{item.number}}</td>
                                    <td>{{item.company.name}}</td>
                                    <td>
                                      <button type="button" class="btn btn-danger btn-sm" @click="removePhone(item)" v-tooltip="'eliminar'">
                                          <i class="fa fa-minus">
                                          </i>
                                      </button>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                          </div>

                            </div>
                            <!-- /.card-header -->
                            
                      </div>
                  </div>
                  <div class="row">
                    <!-- /.col -->
                    <div class="col-12 text-right">
                      <button type="button" class="btn btn-danger btn-sm" @click="close"><i class="fa fa-undo"></i> Cancelar</button>
                      <button type="button" class="btn btn-primary btn-sm" @click="createOrEdit"><i class="fa fa-save"></i> Guardar</button>
                    </div>
                    <!-- /.col -->
                  </div>
            </b-modal>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header no-border">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">Lista de usuarios
                    <b-button variant="success" @click="open" size="sm"><i class="fa fa-plus"></i> Nuevo</b-button>
                  </h3>
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
                      <template v-slot:cell(cui)="data">
                        {{ data.item.cui }}
                      </template>
                      <template v-slot:cell(name)="data">
                        {{ data.item.name_one }} {{ data.item.last_name_one }}
                      </template>
                      <template v-slot:cell(email)="data">
                        {{data.item.email}}
                      </template>
                       <template v-slot:cell(rol)="data">
                        {{data.item.user.rol.name}}
                      </template>
                      <template v-slot:cell(phones)="data">
                        <span v-if="getPhones(data.item.phones).length == 0"> ninguno</span>
                        {{getPhones(data.item.phones)}}
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
  name: "provider",
  components: {
      FormError
  },
  data() {
    return {
      loading: false,
      items: [],
      municipalities: [],
      municipality: null,
      rols: [],
      rol: null,
      companies: [],
      company: null,
      phone: '',
      fields: [
        { key: 'cui', label: 'CUI', sortable: true },
        { key: 'name', label: 'Nombre', sortable: true },
        { key: 'email', label: 'Email', sortable: true },
        { key: 'rol', label: 'Rol', sortable: true },
        { key: 'phones', label: 'Telefonos', sortable: true },
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
          name_one: '',
          name_two: '',
          last_name_one: '',
          last_name_two: '',
          rols_id: '',
          email: '',
          cui: '',
          municipalities_id: null,
          direction: '',
          phones: []
      }
    };
  },

  created() {
    let self = this
    self.getAll()
    self.getMunicipalities()
    self.getRols()
    self.getCompanies()
  },

  methods: {

    onFiltered (filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length
      this.currentPage = 1
    },

    //retornar departamento y municipio
    nameWithLang ({ name, departament }) {
      return `${departament.name} / ${name}`
    },

    getAll() {
      let self = this;
      self.loading = true;

      self.$store.state.services.personService
        .getAll()
        .then(r => {
          self.loading = false; 
          self.items = r.data.data;
          self.totalRows = self.items.length
        })
        .catch(r => {});
    },

    getMunicipalities(){
      let self = this
      self.loading = true

      self.$store.state.services.municipalityService
        .getAll()
        .then(r => {
          self.loading = false
          self.municipalities = r.data.data
        })
        .catch(r => {});
    },

    getRols(){
      let self = this
      self.loading = true

      self.$store.state.services.rolService
        .getAll()
        .then(r => {
          self.loading = false
          r.data.data = r.data.data.filter(x=>x.administrative)
          self.rols = r.data.data
        })
        .catch(r => {});
    },

    getCompanies(){
      let self = this
      self.loading = true

      self.$store.state.services.companyService
        .getAll()
        .then(r => {
          self.loading = false
          self.companies = r.data.data
        })
        .catch(r => {});
    },

    //funcion para guardar registro
    create(){
      let self = this
      self.loading = true
      let data = self.form

      self.$store.state.services.personService
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
       
      self.$store.state.services.personService
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
        text: "Esta seguro de elminar "+data.user.email + '?',
        type: "warning",
        showCancelButton: true
      }).then((result) => { // <--
          if (result.value) { // <-- if confirmed
              self.loading = true
              self.$store.state.services.personService
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
      let self = this
      this.$validator.validateAll('form').then((result) => {
          if (result) {
              if(self.form.phones.length == 0){
                self.$toastr.error('debe agregar al menos un numero telefonico','error')
                return
              }
              self.form.municipalities_id = self.municipality.id
              self.form.rols_id = self.rol.id
              self.pasarMayusculas()
              self.form.id === null ? self.create() : self.update()
           }
      });
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
        self.form.cui = data.cui
        self.form.name_one = data.name_one
        self.form.name_two = data.name_two
        self.form.last_name_one = data.last_name_one
        self.form.last_name_two = data.last_name_two
        self.form.municipalities_id = data.municipalities_id
        self.form.email = data.email
        self.form.direction = data.direction
        self.form.rols_id = data.user.rols_id
        self.municipality = data.municipality
        self.rol = data.user.rol
        self.form.phones = data.phones

        this.$refs['nuevo'].show()
    },

    //limpiar data de formulario
    clearData(){
        let self = this

        Object.keys(self.form).forEach(function(key,index) {
          if(typeof self.form[key] === "string") 
            self.form[key] = ''
          else if (typeof self.form[key] === "boolean") 
            self.form[key] = false
          else if (typeof self.form[key] === "number") 
            self.form[key] = null
        });

        self.$validator.reset()
        self.$validator.reset()
    },

    open(numero,titulo){
        let self = this
        this.$refs['nuevo'].show()
        self.clearData()
    },

    //cerrar modal limpiar registros
    close(){
        let self= this
        self.$refs['nuevo'].hide()
    },

    //agregar telefono
    addPhone(){
      let self = this
      this.$validator.validateAll('form_telefono').then((result) => {
          if (result) {
            self.form.phones.push({
                number: self.phone,
                companies_id: self.company.id,
                company: self.company
              })
              
              self.phone = ''
              self.company = null
               self.$validator.reset()
              self.$validator.reset()
          }
      })

      
    },

    removePhone(phone){
      let self = this
      var i = self.form.phones.indexOf(phone)
      self.form.phones.splice(i,1)
    },

    getPhones(phones){
      let self = this
      return phones.map(e => e.number).join(", ");
    }
  },

  computed:{
      title(){
          let self = this
          return self.form.id == null ? 'Nuevo proveedor' : 'Editar '+self.form.name
      },

  }
};
</script>