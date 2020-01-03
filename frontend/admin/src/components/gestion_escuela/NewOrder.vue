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
                <h3 class="card-title">Aregar nuevo pedido de alimentación</h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12 col-sm-12">
                    <div class="card card-primary card-tabs">
                      <div class="card-header p-0 pt-1">
                        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                          <li class="nav-item">
                            <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">Datos de la escula</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-one-settings-tab" data-toggle="pill" href="#custom-tabs-one-settings" role="tab" aria-controls="custom-tabs-one-settings" aria-selected="false">Pedido</a>
                          </li>
                        </ul>
                      </div>
                      <div class="card-body">
<form> 
  <div class="tab-content" id="custom-tabs-one-tabContent">
    <div class="tab-pane fade active show" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
      Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin malesuada lacus ullamcorper dui molestie, sit amet congue quam finibus. Etiam ultricies nunc non magna feugiat commodo. Etiam odio magna, mollis auctor felis vitae, ullamcorper ornare ligula. Proin pellentesque tincidunt nisi, vitae ullamcorper felis aliquam id. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Proin id orci eu lectus blandit suscipit. Phasellus porta, ante et varius ornare, sem enim sollicitudin eros, at commodo leo est vitae lacus. Etiam ut porta sem. Proin porttitor porta nisl, id tempor risus rhoncus quis. In in quam a nibh cursus pulvinar non consequat neque. Mauris lacus elit, condimentum ac condimentum at, semper vitae lectus. Cras lacinia erat eget sapien porta consectetur. 
    </div>

    <div class="tab-pane fade" id="custom-tabs-one-settings" role="tabpanel" aria-labelledby="custom-tabs-one-settings-tab">
      <div class="row">
        <div class="col-md-12 col-sm-12">
          <h3>Pedido # </h3><h1><b>1</b></h1>
        </div>
        <div class="col-md-8 col-sm-12">
          <div class="row">
            <div class="col-md-4 col-sm-12">
              <div class="form-group">
                <label>Cantidad</label>

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
              <button type="button" class="btn btn-success btn-sm" @click="addPhoneSchool">Agregar teléfono</button>
            </div>     
            &nbsp;
            &nbsp;     
          </div>
        </div>
        <div class="col-md-4 col-sm-12">
          <b-jumbotron :header="'100.00'" lead="Total">
          </b-jumbotron>         
        </div>
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
              <tbody v-if="form.phone_school.length >= 1">
                <template v-for="(item, index) in form.phone_school">
                  <tr v-bind:key="index">
                      <td v-text="item.number"></td>
                      <td v-text="item.name"></td>
                      <td>
                          <button class="btn btn-danger btn-sm" @click="quitarPhoneSchool(index)">
                            Quitar
                          </button>
                      </td>                    
                  </tr>
                </template>
              </tbody>            
            </table>
          </div>
        </div>
        <hr>
        <div class="col-md-12 col-sm-12 text-right">
            <button type="button" class="btn btn-primary btn-sm" @click="createOrEdit"><i class="fa fa-save"></i> Guardar</button>
        </div>
      </div>
    </div>
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
    //funcion, validar si se guarda o actualiza
    createOrEdit(){
      let self = this
      let existe = true

      self.number_phone_school = '00000000'
      self.companies_id_phone_school = {'id': 1, 'name': 'TIGO'}
      self.number_phone_person = '00000000'
      self.companies_id_phone_person = {'id': 1, 'name': 'TIGO'}

      if(self.form.phone_school.length == 0){
        self.$toastr.error('es necesario registrar al meno un número de teléfono para la escuela.', 'error')
        existe = false
      }

      if(self.form.phone_people.length == 0){
        self.$toastr.error('es necesario registrar al meno un número de teléfono para la persona.', 'error')
        existe = false
      }

      if(existe == true){
        self.$validator.validateAll().then((result) => {
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
          if(r.response){
            self.$toastr.error(r.response.data.error, 'error')
            return
          }
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
