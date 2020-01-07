<template>
<div v-loading="loading">

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">

        </div>
      </div>
    </div>

    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header border-0">
                <h3 class="card-title">Nueva escuela</h3>
              </div>
              <div class="card-body">
                <div class="row">
<form>                
  <hr>
  <div class="col-md-12 col-sm-12">
    <div class="row">
      <div class="col-md-12 col-sm-12">
        <h3>Datos de la escuela</h3>
      </div>
      <div class="col-md-12 col-sm-12">
        <div class="form-group">
          <label>Nombre</label>
          <input type="text" class="form-control" placeholder="nombre"
          name="add.name"
          v-model="form.name"
          data-vv-as="nombre"
          v-validate="'required|max:175'"
          data-vv-scope="add"
          :class="{'input':true,'has-errors': errors.has('add.name')}">
          <FormError :attribute_name="'add.name'" :errors_form="errors"> </FormError>
        </div>
      </div>

      <div class="col-md-4 col-sm-12">
        <div class="form-group">
          <label>NIT</label>
          <input type="text" class="form-control" placeholder="nit"
          name="add.nit"
          v-model="form.nit"
          data-vv-as="nit"
          v-validate="'required|max:13|min:7'"
          data-vv-scope="add"
          :class="{'input':true,'has-errors': errors.has('add.nit')}">
          <FormError :attribute_name="'nit'" :errors_form="errors"> </FormError>
        </div>
      </div> 

      <div class="col-md-4 col-sm-12">
        <div class="form-group">
          <label>Código Primaria</label>
          <input type="text" class="form-control" placeholder="código primaria"
          name="add.code_primary"
          v-model="form.code_primary"
          data-vv-as="código primaria"
          v-validate="'required|max:24'"
          data-vv-scope="add"
          :class="{'input':true,'has-errors': errors.has('add.code_primary')}">
          <FormError :attribute_name="'add.code_primary'" :errors_form="errors"> </FormError>
        </div>
      </div> 

      <div class="col-md-4 col-sm-12">
        <div class="form-group">
          <label>Código Preprimaria</label>
          <input type="text" class="form-control" placeholder="código preprimaria"
          name="add.code_high_school"
          v-model="form.code_high_school"
          data-vv-as="código preprimaria"
          v-validate="'required|max:24'"
          data-vv-scope="add"
          :class="{'input':true,'has-errors': errors.has('add.code_high_school')}">
          <FormError :attribute_name="'add.code_high_school'" :errors_form="errors"> </FormError>
        </div>
      </div> 

      <div class="col-md-12 col-sm-12">
        <div class="form-group">
          <label>Nombre Factura</label>
          <input type="text" class="form-control" placeholder="nombre a facturar"
          name="add.bill"
          v-model="form.bill"
          data-vv-as="nombre a facturar"
          v-validate="'required|max:175'"
          data-vv-scope="add"
          :class="{'input':true,'has-errors': errors.has('add.bill')}">
          <FormError :attribute_name="'bill'" :errors_form="errors"> </FormError>
        </div>
      </div>  

      <div class="col-md-5 col-sm-12">
        <div class="form-group">
          <label>Municipio</label>
          <multiselect v-model="form.municipalities_id"
              v-validate="'required'" 
              data-vv-name="add.municipalities_id"
              data-vv-as="municipio"
              :options="municipalities" placeholder="seleccione municipio"  
              :searchable="true"
              :allow-empty="false"
              :show-labels="false"
              data-vv-scope="add"
              label="name" track-by="id">
              <span slot="noResult">No se encontro ningún registro</span>
              </multiselect>
              <FormError :attribute_name="'add.municipalities_id'" :errors_form="errors"> </FormError>
        </div>
      </div>

      <div class="col-md-7 col-sm-12">
        <div class="form-group">
          <label>Dirección</label>
          <input type="text" class="form-control" placeholder="dirección"
          name="add.direction"
          v-model="form.direction"
          data-vv-as="dirección"
          v-validate="'required|max:175'"
          data-vv-scope="add"
          :class="{'input':true,'has-errors': errors.has('add.direction')}">
          <FormError :attribute_name="'add.direction'" :errors_form="errors"> </FormError>
        </div>
      </div> 
    </div>
  </div>
  <hr>
  <div class="col-md-12 col-sm-12">
    <div class="row">
      <div class="col-md-12 col-sm-12">
        <h3>Número de teléfono de la escuela</h3>
      </div>
      <div class="col-md-4 col-sm-12">
        <div class="row">
          <div class="col-md-12 col-sm-12">
            <div class="form-group">
              <label>Número de Teléfono</label>
              <input type="text" class="form-control" placeholder="número de teléfono"
              name="number_phone_school"
              v-model="number_phone_school"
              data-vv-as="número de teléfono"
              v-validate="'required|numeric|min:8|max:8'"
              :class="{'input':true,'has-errors': errors.has('number_phone_school')}">
              <FormError :attribute_name="'number_phone_school'" :errors_form="errors"> </FormError>
            </div>
          </div>           
          <div class="col-md-12 col-sm-12">            
            <div class="form-group">
              <label>Compania</label>
              <multiselect v-model="companies_id_phone_school"
                  v-validate="'required'" 
                  data-vv-name="companies_id_phone_school"
                  data-vv-as="compania"
                  :options="companies" placeholder="seleccione compania"  
                  :searchable="true"
                  :allow-empty="false"
                  :show-labels="false"
                  label="name" track-by="id">
                  <span slot="noResult">No se encontro ningún registro</span>
                  </multiselect>
                  <FormError :attribute_name="'companies_id_phone_school'" :errors_form="errors"> </FormError>
            </div>
          </div>
          <div class="col-md-12 col-sm-12 text-right">
            <button type="button" class="btn btn-success btn-sm" v-b-tooltip title="agregar teléfono" @click="addPhoneSchool">Agregar teléfono</button>
          </div>            
        </div>
      </div>
      <div class="col-md-8 col-sm-12">
        <div class="table-responsive">
          <table class="table table-bordered table-striped table-sm">
            <thead>
                <tr>
                    <th>Número de Teléfono</th>
                    <th>Compania</th>
                    <th></th>
                </tr>
            </thead>
            <tbody v-if="form.phone_school.length >= 1">
              <template v-for="(item, index) in form.phone_school">
                <tr v-bind:key="index">
                    <td v-text="item.number"></td>
                    <td v-text="item.name"></td>
                    <td>
                        <button class="btn btn-danger btn-sm" v-b-tooltip title="eliminar" @click="quitarPhoneSchool(index)">
                          Quitar
                        </button>
                    </td>                    
                </tr>
              </template>
            </tbody>            
          </table>
        </div>
      </div>
    </div>
  </div>  
  <hr>
  <div class="col-md-12 col-sm-12">
    <div class="row">
      <div class="col-md-12 col-sm-12">
        <h3>Datos del Director o Presidente</h3>
      </div>

      <div class="col-md-8 col-sm-12">
        <div class="row">

          <div class="col-md-6 col-sm-12">
            <div class="form-group">
              <label>CUI</label>
              <input type="text" class="form-control" placeholder="número de dpi"
              name="add.cui"
              v-model="form.cui"
              data-vv-as="número de dpi"
              v-validate="'required|numeric|min:13|max:13'"
              data-vv-scope="add"
              :class="{'input':true,'has-errors': errors.has('add.cui')}">
              <FormError :attribute_name="'add.cui'" :errors_form="errors"> </FormError>
            </div>
          </div>    

          <div class="col-md-6 col-sm-12">
            <div class="form-group">
              <label>Correo Electrónico</label>
              <input type="text" class="form-control" placeholder="correo electrónico"
              name="add.email"
              v-model="form.email"
              data-vv-as="correo electrónico"
              v-validate="'required|email|max:100'"
              data-vv-scope="add"
              :class="{'input':true,'has-errors': errors.has('add.email')}">
              <FormError :attribute_name="'add.email'" :errors_form="errors"> </FormError>
            </div>
          </div> 

          <div class="col-md-6 col-sm-12">
            <div class="form-group">
              <label>Primer Nombre</label>
              <input type="text" class="form-control" placeholder="primer nombre"
              name="add.name_one"
              v-model="form.name_one"
              data-vv-as="primer nombre"
              v-validate="'required|max:25'"
              data-vv-scope="add"
              :class="{'input':true,'has-errors': errors.has('add.name_one')}">
              <FormError :attribute_name="'add.name_one'" :errors_form="errors"> </FormError>
            </div>
          </div> 

          <div class="col-md-6 col-sm-12">
            <div class="form-group">
              <label>Segundo Nombre</label>
              <input type="text" class="form-control" placeholder="segundo nombre"
              name="add.name_two"
              v-model="form.name_two"
              data-vv-as="segundo nombre"
              v-validate="'max:25'"
              data-vv-scope="add"
              :class="{'input':true,'has-errors': errors.has('add.name_two')}">
              <FormError :attribute_name="'add.name_two'" :errors_form="errors"> </FormError>
            </div>
          </div> 

          <div class="col-md-6 col-sm-12">
            <div class="form-group">
              <label>Primer Apellido</label>
              <input type="text" class="form-control" placeholder="primer apellido"
              name="add.last_name_one"
              v-model="form.last_name_one"
              data-vv-as="primer apellido"
              v-validate="'required|max:25'"
              data-vv-scope="add"
              :class="{'input':true,'has-errors': errors.has('add.last_name_one')}">
              <FormError :attribute_name="'add.last_name_one'" :errors_form="errors"> </FormError>
            </div>
          </div> 

          <div class="col-md-6 col-sm-12">
            <div class="form-group">
              <label>Segundo Apellido</label>
              <input type="text" class="form-control" placeholder="segundo apellido"
              name="add.last_name_two"
              v-model="form.last_name_two"
              data-vv-as="segundo apellido"
              v-validate="'max:25'"
              data-vv-scope="add"
              :class="{'input':true,'has-errors': errors.has('add.last_name_two')}">
              <FormError :attribute_name="'add.last_name_two'" :errors_form="errors"> </FormError>
            </div>
          </div> 

          <div class="col-md-12 col-sm-12">
            <div class="form-group">
              <label>Municipio</label>
              <multiselect v-model="form.municipalities_id_people"
                  v-validate="'required'" 
                  data-vv-name="add.municipalities_id_people"
                  data-vv-as="municipio"
                  :options="municipalities" placeholder="seleccione municipio"  
                  :searchable="true"
                  :allow-empty="false"
                  :show-labels="false"
                  data-vv-scope="add"
                  label="name" track-by="id">
                  <span slot="noResult">No se encontro ningún registro</span>
                  </multiselect>
                  <FormError :attribute_name="'add.municipalities_id_people'" :errors_form="errors"> </FormError>
            </div>
          </div>

          <div class="col-md-12 col-sm-12">
            <div class="form-group">
              <label>Dirección</label>
              <input type="text" class="form-control" placeholder="dirección"
              name="add.direction_people"
              v-model="form.direction_people"
              data-vv-as="dirección"
              v-validate="'required|max:175'"
              data-vv-scope="add"
              :class="{'input':true,'has-errors': errors.has('add.direction_people')}">
              <FormError :attribute_name="'add.direction_people'" :errors_form="errors"> </FormError>
            </div>
          </div> 

          <div class="col-md-12 col-sm-12">
            <div class="form-group">
              <label>Rol</label>
              <multiselect v-model="form.type_person"
                  v-validate="'required'" 
                  data-vv-name="add.type_person"
                  data-vv-as="rol"
                  :options="type_persons" placeholder="seleccione el rol"  
                  :searchable="true"
                  :allow-empty="false"
                  :show-labels="false"
                  data-vv-scope="add"
                  label="name" track-by="id">
                  <span slot="noResult">No se encontro ningún registro</span>
                  </multiselect>
                  <FormError :attribute_name="'add.type_person'" :errors_form="errors"> </FormError>
            </div>
          </div>      

          <div class="col-md-12 col-sm-12">
            <div class="form-group">
                <label></label>
                <b-form-checkbox
                v-model="form.president"
                name="president"
                >
                ¿Los datos que está registrando, pertenecen a un presidente?
                </b-form-checkbox>
                <div class="text-right"><strong>{{ form.president ? 'Si es Presidente':'No es Presidente' }}</strong></div>
            </div>
          </div>

        </div>
      </div>
      <div class="col-md-4 col-sm-12">
        <div class="row">

          <div class="col-md-12 col-sm-12">
            <div class="form-group">
              <label>Número de Teléfono</label>
              <input type="text" class="form-control" placeholder="número de teléfono"
              name="number_phone_person"
              v-model="number_phone_person"
              data-vv-as="número de teléfono"
              v-validate="'required|numeric|min:8|max:8'"
              :class="{'input':true,'has-errors': errors.has('number_phone_person')}">
              <FormError :attribute_name="'number_phone_person'" :errors_form="errors"> </FormError>
            </div>
          </div>           
          <div class="col-md-12 col-sm-12">            
            <div class="form-group">
              <label>Compania</label>
              <multiselect v-model="companies_id_phone_person"
                  v-validate="'required'" 
                  data-vv-name="companies_id_phone_person"
                  data-vv-as="compania"
                  :options="companies" placeholder="seleccione compania"  
                  :searchable="true"
                  :allow-empty="false"
                  :show-labels="false"
                  label="name" track-by="id">
                  <span slot="noResult">No se encontro ningún registro</span>
                  </multiselect>
                  <FormError :attribute_name="'companies_id_phone_person'" :errors_form="errors"> </FormError>
            </div>
          </div>
          <div class="col-md-12 col-sm-12 text-right">
            <button type="button" class="btn btn-success btn-sm" v-b-tooltip title="agregar teléfono" @click="addPhonePerson">Agregar teléfono</button>
          </div>
          <hr>
          <div class="col-md-12 col-sm-12">
            <div class="table-responsive">
              <table class="table table-bordered table-striped table-sm">
                <thead>
                    <tr>
                        <th>Número de Teléfono</th>
                        <th>Compania</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody v-if="form.phone_people.length >= 1">
                  <template v-for="(item, index) in form.phone_people">
                    <tr v-bind:key="index">
                        <td v-text="item.number"></td>
                        <td v-text="item.name"></td>
                        <td>
                            <button class="btn btn-danger btn-sm" v-b-tooltip title="eliminar" @click="quitarPhonePerson(index)">
                              Quitar
                            </button>
                        </td>                    
                    </tr>
                  </template>
                </tbody>            
              </table>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
  <div class="col-md-12 col-sm-12 text-right">
    <button type="button" class="btn btn-primary btn-sm" v-b-tooltip title="guardar" @click="createOrEdit"><i class="fa fa-save"></i> Guardar</button>
  </div>  
</form>
                </div>
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
import FormError from '../shared/FormError'
export default {
  name: "newschool",
  components: {
      FormError
  },
  data() {
    return {
      loading: false,
      items: {},
      municipalities: [],
      type_persons: [],
      companies: [],
      number_phone_school: '',
      companies_id_phone_school: null,
      number_phone_person: '',
      companies_id_phone_person: null,
      form: {
        id: null,
        bill: '',
        code_high_school: '',
        code_primary: '',
        direction: '',
        municipalities_id: '',
        name: '',
        nit: '',  
        phone_school: [],
        type_person: null,
        president: false,

        cui: '',
        name_one: '',
        name_two: '',
        last_name_one: '',
        last_name_two: '',
        direction_people: '',
        email: '',
        municipalities_id_people: null,
        phone_people: [],

      }
    };
  },
  created() {
    let self = this;
    self.getMunicipalities()
    self.getTypePersona()
    self.getComany()
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

    //funcion, validar si se guarda o actualiza
    createOrEdit(){
      let self = this
      let existe = true

      if(self.form.phone_school.length == 0){
        self.$toastr.error('es necesario registrar al meno un número de teléfono para la escuela.', 'error')
        existe = false
      }

      if(self.form.phone_people.length == 0){
        self.$toastr.error('es necesario registrar al meno un número de teléfono para la persona.', 'error')
        existe = false
      }

      if(existe == true){
        self.$validator.validateAll('add').then((result) => {
            if (result) {
              self.pasarMayusculas()
              self.form.id === null ? self.create() : self.update()
            }
        });
      }
    },

    //Limpiar formulario
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

        self.form.phone_school = []
        self.form.phone_people = []
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

    //funcion para guardar registro
    create(){
      let self = this
      self.loading = true
      let data = self.form
      data.municipalities_id = self.form.municipalities_id.id
      data.municipalities_id_people = self.form.municipalities_id_people.id
      data.type_person = self.form.type_person.id
      console.log(data)
      self.$store.state.services.schoolService
        .create(data)
        .then(r => {
          self.loading = false
          if( self.interceptar_error(r) == 0) return
          self.$toastr.success('registro agregado con exito', 'exito')         
          self.clearData()
        })
        .catch(r => {});
    },

    //Obtner los municipios
    getMunicipalities(){
      let self = this;
      self.loading = true;

      self.$store.state.services.municipalityService
        .getAll()
        .then(r => {
          self.loading = false; 
          r.data.data.forEach( function ver(item) {
            self.municipalities.push({'id': item.id, 'name': item.departament.name+' / '+item.name})
          })
          self.getAll();
        })
        .catch(r => {});
    },

    //Obtener los roles
    getTypePersona(){
      let self = this;
      self.loading = true;
      self.type_persons = []

      self.type_persons.push({'id': 'DIRECTOR', 'name': 'DIRECTOR'})
      self.type_persons.push({'id': 'PROFESOR', 'name': 'PROFESOR'})
      self.type_persons.push({'id': 'OTRO', 'name': 'OTRO'})

      self.loading = false;
    }, 
    
    //Obtner las companias
    getComany() {
      let self = this;
      self.loading = true;

      self.$store.state.services.companyService
        .getAll()
        .then(r => {
          self.loading = false; 
          self.companies = r.data.data;
        })
        .catch(r => {});
    },
    
    //Agregar número de teléfono de la escuela
    addPhoneSchool(){
      let self = this
      let encontro = false

      if(self.number_phone_school == '' || self.number_phone_school == null 
        || self.companies_id_phone_school == '' || self.companies_id_phone_school == null)
      {
        self.$swal({
          title: "Advertencia",
          text: 'La información que intenta agregar no es correcta.',
          type: "warning",
        })

        return
      }
      else 
      {
        self.form.phone_school.forEach( function verificar(item) {
          if(item.number == self.number_phone_school) {
            self.$toastr.warning('el número '+item.number+' ya fue agregado anteriormente.', 'advertencia')
            encontro = true
          }
        })

        if(encontro == false){
          self.form.phone_school.push({
                                        'number': self.number_phone_school, 
                                        'name': self.companies_id_phone_school.name, 
                                        'companies_id': self.companies_id_phone_school.id})
          self.number_phone_school = ''
          self.companies_id_phone_school = null                                      
        }

        self.$validator.reset()
        self.$validator.reset()
      }
    },

    //Quitar número de teléfono de la escuela
    quitarPhoneSchool(index) {
        this.form.phone_school.splice(index, 1);
    },  
    
    //Agregar número de teléfono de la persona
    addPhonePerson(){
      let self = this
      let encontro = false

      if(self.number_phone_person == '' || self.number_phone_person == null 
        || self.companies_id_phone_person == '' || self.companies_id_phone_person == null)
      {
        self.$swal({
          title: "Advertencia",
          text: 'La información que intenta agregar no es correcta.',
          type: "warning",
        })

        return
      }
      else 
      {
        self.form.phone_people.forEach( function verificar(item) {
          if(item.number == self.number_phone_person) {
            self.$toastr.warning('el número '+item.number+' ya fue agregado anteriormente.', 'advertencia')
            encontro = true
          }
        })

        if(encontro == false){
          self.form.phone_people.push({
                                        'number': self.number_phone_person, 
                                        'name': self.companies_id_phone_person.name, 
                                        'companies_id': self.companies_id_phone_person.id})

          self.number_phone_person = ''
          self.companies_id_phone_person = null                                    
        }

        self.$validator.reset()
        self.$validator.reset()
      }
    },

    //Quitar número de teléfono de la escuela
    quitarPhonePerson(index) {
        this.form.phone_people.splice(index, 1);
    },    
  },

  mounted(){
    $("body").resize()
  },
};
</script>