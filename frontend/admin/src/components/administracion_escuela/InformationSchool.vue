<template>
<div>

  <div class="content-wrapper" v-loading="loading">
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
            <b-modal ref="modal_person_phone" :title="title_person_phone" hide-footer class="modal-backdrop" no-close-on-backdrop size="sm">
<form>
  <div class="row">
    <div class="col-md-12 col-sm-12">
      <div class="col-md-12 col-sm-12">
        <div class="form-group">
          <label>Número de Teléfono</label>
          <input type="text" class="form-control" placeholder="número de teléfono"
          name="add_phone_person.number"
          v-model="form_phone_person.number"
          data-vv-as="número de teléfono"
          v-validate="'required|numeric|min:8|max:8'"
          data-vv-scope="add_phone_person"
          :class="{'input':true,'has-errors': errors.has('add_phone_person.number')}">
          <FormError :attribute_name="'add_phone_person.number'" :errors_form="errors"> </FormError>
        </div>
      </div>           
      <div class="col-md-12 col-sm-12">            
        <div class="form-group">
          <label>Compania</label>
          <multiselect v-model="form_phone_person.companies_id"
              v-validate="'required'" 
              data-vv-name="add_phone_person.companies_id"
              data-vv-as="compania"
              :options="companies" placeholder="seleccione compania"  
              :searchable="true"
              :allow-empty="false"
              :show-labels="false"
              data-vv-scope="add_phone_person"
              label="name" track-by="id">
              <span slot="noResult">No se encontro ningún registro</span>
              </multiselect>
              <FormError :attribute_name="'add_phone_person.companies_id'" :errors_form="errors"> </FormError>
        </div>
      </div>
    </div>
    <hr>
    <div class="col-md-12 col-sm-12 text-right">
      <button type="button" class="btn btn-primary btn-sm" @click="addPersonPhone"><i class="fa fa-save"></i> Guardar</button>
    </div>      
  </div>
</form>
            </b-modal>            
          </div>

          <div class="col-lg-12">
            <b-modal ref="modal_person" :title="title" hide-footer class="modal-backdrop" no-close-on-backdrop size="xl">
<form>
  <div class="row">

    <div class="col-md-6 col-sm-12">
      <div class="form-group">
        <label>CUI</label>
        <input type="text" class="form-control" placeholder="número de dpi"
        name="add_person_school.cui"
        v-model="form_person_school.cui"
        data-vv-as="número de dpi"
        v-validate="'required|numeric|min:13|max:13'"
        data-vv-scope="add_person_school"
        :class="{'input':true,'has-errors': errors.has('add_person_school.cui')}">
        <FormError :attribute_name="'add_person_school.cui'" :errors_form="errors"> </FormError>
      </div>
    </div>    

    <div class="col-md-6 col-sm-12">
      <div class="form-group">
        <label>Correo Electrónico</label>
        <input type="text" class="form-control" placeholder="correo electrónico"
        name="add_person_school.email"
        v-model="form_person_school.email"
        data-vv-as="correo electrónico"
        v-validate="'required|email|max:100'"
        data-vv-scope="add_person_school"
        :class="{'input':true,'has-errors': errors.has('add_person_school.email')}">
        <FormError :attribute_name="'add_person_school.email'" :errors_form="errors"> </FormError>
      </div>
    </div> 

    <div class="col-md-6 col-sm-12">
      <div class="form-group">
        <label>Primer Nombre</label>
        <input type="text" class="form-control" placeholder="primer nombre"
        name="add_person_school.name_one"
        v-model="form_person_school.name_one"
        data-vv-as="primer nombre"
        v-validate="'required|max:25'"
        data-vv-scope="add_person_school"
        :class="{'input':true,'has-errors': errors.has('add_person_school.name_one')}">
        <FormError :attribute_name="'add_person_school.name_one'" :errors_form="errors"> </FormError>
      </div>
    </div> 

    <div class="col-md-6 col-sm-12">
      <div class="form-group">
        <label>Segundo Nombre</label>
        <input type="text" class="form-control" placeholder="segundo nombre"
        name="add_person_school.name_two"
        v-model="form_person_school.name_two"
        data-vv-as="segundo nombre"
        v-validate="'max:25'"
        data-vv-scope="add_person_school"
        :class="{'input':true,'has-errors': errors.has('add_person_school.name_two')}">
        <FormError :attribute_name="'add_person_school.name_two'" :errors_form="errors"> </FormError>
      </div>
    </div> 

    <div class="col-md-6 col-sm-12">
      <div class="form-group">
        <label>Primer Apellido</label>
        <input type="text" class="form-control" placeholder="primer apellido"
        name="add_person_school.last_name_one"
        v-model="form_person_school.last_name_one"
        data-vv-as="primer apellido"
        v-validate="'required|max:25'"
        data-vv-scope="add_person_school"
        :class="{'input':true,'has-errors': errors.has('add_person_school.last_name_one')}">
        <FormError :attribute_name="'add_person_school.last_name_one'" :errors_form="errors"> </FormError>
      </div>
    </div> 

    <div class="col-md-6 col-sm-12">
      <div class="form-group">
        <label>Segundo Apellido</label>
        <input type="text" class="form-control" placeholder="segundo apellido"
        name="add_person_school.last_name_two"
        v-model="form_person_school.last_name_two"
        data-vv-as="segundo apellido"
        v-validate="'max:25'"
        data-vv-scope="add_person_school"
        :class="{'input':true,'has-errors': errors.has('add_person_school.last_name_two')}">
        <FormError :attribute_name="'add_person_school.last_name_two'" :errors_form="errors"> </FormError>
      </div>
    </div> 

    <div class="col-md-12 col-sm-12">
      <div class="form-group">
        <label>Municipio</label>
        <multiselect v-model="form_person_school.municipalities_id_people"
            v-validate="'required'" 
            data-vv-name="add_person_school.municipalities_id_people"
            data-vv-as="municipio"
            :options="municipalities" placeholder="seleccione municipio"  
            :searchable="true"
            :allow-empty="false"
            :show-labels="false"
            data-vv-scope="add_person_school"
            label="name" track-by="id">
            <span slot="noResult">No se encontro ningún registro</span>
            </multiselect>
            <FormError :attribute_name="'add_person_school.municipalities_id_people'" :errors_form="errors"> </FormError>
      </div>
    </div>

    <div class="col-md-12 col-sm-12">
      <div class="form-group">
        <label>Dirección</label>
        <input type="text" class="form-control" placeholder="dirección"
        name="add_person_school.direction_people"
        v-model="form_person_school.direction_people"
        data-vv-as="dirección"
        v-validate="'required|max:175'"
        data-vv-scope="add_person_school"
        :class="{'input':true,'has-errors': errors.has('add_person_school.direction_people')}">
        <FormError :attribute_name="'add_person_school.direction_people'" :errors_form="errors"> </FormError>
      </div>
    </div> 

    <div class="col-md-12 col-sm-12">
      <div class="form-group">
        <label>Rol</label>
        <multiselect v-model="form_person_school.type_person"
            v-validate="'required'" 
            data-vv-name="add_person_school.type_person"
            data-vv-as="rol"
            :options="type_persons" placeholder="seleccione el rol"  
            :searchable="true"
            :allow-empty="false"
            :show-labels="false"
            data-vv-scope="add_person_school"
            label="name" track-by="id">
            <span slot="noResult">No se encontro ningún registro</span>
            </multiselect>
            <FormError :attribute_name="'add_person_school.type_person'" :errors_form="errors"> </FormError>
      </div>
      
    </div>        

    <hr>
    <div class="col-md-12 col-sm-12 text-right">
      <button type="button" class="btn btn-primary btn-sm" @click="addOredit"><i class="fa fa-save"></i> Guardar</button>
    </div>      
  </div>
</form>
            </b-modal>
          </div>

          <div class="col-lg-12">
            <div class="card">
              <div class="card-header border-0">
                <h3 class="card-title">{{ form.name }}</h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12 col-sm-12">

<form>
  <div class="col-md-12 col-sm-12">
                        <div class="row">
                          <div class="col-md-12 col-sm-12">
                            <h3>Datos de la escuela</h3>
                          </div>
                          <div class="col-md-4 col-lg-4 col-xs-12 card">
                            <div class="form-group">
                              <label>Logotipo</label>
                              <div class="input-group">
                                  <input accept="image/*" type="file" @change="selectedFile">
                              </div>
                            </div>
                            <div class="card">
                              <div class="text-center">
                                <img class="product-image" style="max-height: 300px;" :src="logo !== ''?logo : 'src/assets/logo_empty.png'">
                              </div>
                            </div>
                          </div>
                          <div class="col-md-8 col-lg-8 col-xs-12">
                            <div class="row">
                              <div class="col-md-12 col-sm-12">
                                <div class="form-group">
                                  <label>Nombre</label>
                                  <input
                                    type="text"
                                    class="form-control"
                                    placeholder="nombre"
                                    name="name"
                                    v-model="form.name"
                                    data-vv-as="nombre"
                                    v-validate="'required|max:175'"
                                    :class="{'input':true,'has-errors': errors.has('name')}"
                                  />
                                  <FormError :attribute_name="'name'" :errors_form="errors"></FormError>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-4 col-sm-12">
                                <div class="form-group">
                                  <label>NIT</label>
                                  <input
                                    type="text"
                                    class="form-control"
                                    placeholder="nit"
                                    name="nit"
                                    v-model="form.nit"
                                    data-vv-as="nit"
                                    v-validate="'required|max:13|min:7'"
                                    :class="{'input':true,'has-errors': errors.has('nit')}"
                                  />
                                  <FormError :attribute_name="'nit'" :errors_form="errors"></FormError>
                                </div>
                              </div>
                              <div class="col-md-4 col-sm-12">
                                <div class="form-group">
                                  <label>Código Primaria</label>
                                  <input
                                    type="text"
                                    class="form-control"
                                    placeholder="código primaria"
                                    name="code_primary"
                                    v-model="form.code_primary"
                                    data-vv-as="código primaria"
                                    v-validate="'required|max:24'"
                                    :class="{'input':true,'has-errors': errors.has('code_primary')}"
                                  />
                                  <FormError :attribute_name="'code_primary'" :errors_form="errors"></FormError>
                                </div>
                              </div>

                              <div class="col-md-4 col-sm-12">
                                <div class="form-group">
                                  <label>Código Preprimaria</label>
                                  <input
                                    type="text"
                                    class="form-control"
                                    placeholder="código preprimaria"
                                    name="code_high_school"
                                    v-model="form.code_high_school"
                                    data-vv-as="código preprimaria"
                                    v-validate="'required|max:24'"
                                    :class="{'input':true,'has-errors': errors.has('code_high_school')}"
                                  />
                                  <FormError
                                    :attribute_name="'code_high_school'"
                                    :errors_form="errors"
                                  ></FormError>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-12 col-sm-12">
                                <div class="form-group">
                                  <label>Nombre Factura</label>
                                  <input
                                    type="text"
                                    class="form-control"
                                    placeholder="nombre a facturar"
                                    name="bill"
                                    v-model="form.bill"
                                    data-vv-as="nombre a facturar"
                                    v-validate="'required|max:175'"
                                    :class="{'input':true,'has-errors': errors.has('bill')}"
                                  />
                                  <FormError :attribute_name="'bill'" :errors_form="errors"></FormError>
                                </div>
                              </div>
                            </div>
                            
                        <div class="row">
                          <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                              <label>Municipio</label>
                              <multiselect
                                v-model="form.municipalities_id"
                                v-validate="'required'"
                                data-vv-name="municipalities_id"
                                data-vv-as="municipio"
                                :options="municipalities"
                                placeholder="seleccione municipio"
                                :searchable="true"
                                :allow-empty="false"
                                :show-labels="false"
                                label="name"
                                track-by="id"
                              >
                                <span slot="noResult">No se encontro ningún registro</span>
                              </multiselect>
                              <FormError
                                :attribute_name="'municipalities_id'"
                                :errors_form="errors"
                              ></FormError>
                            </div>
                          </div>
                          <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                              <label>Dirección</label>
                              <input
                                type="text"
                                class="form-control"
                                placeholder="dirección"
                                name="direction"
                                v-model="form.direction"
                                data-vv-as="dirección"
                                v-validate="'required|max:175'"
                                :class="{'input':true,'has-errors': errors.has('direction')}"
                              />
                              <FormError :attribute_name="'direction'" :errors_form="errors"></FormError>
                            </div>
                          </div>
                        </div>
                          </div>
                        </div>
                      </div>
  <hr>
  <div class="row">
    <div class="col-12 text-right">
      <button type="button" class="btn btn-primary btn-sm" @click="createOrEdit"><i class="fa fa-save"></i> Guardar</button>
    </div>
  </div>
</form>


                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-9 col-sm-12">
            <div class="card border-primary">
              <div class="card-header bg-primary">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">Lista de personas asignadas</h3>
                  <button type="button" class="btn btn-success btn-sm" @click="viewModal"><i class="fa fa-plus"></i></button>                  
                </div>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12 col-sm-12">
                    <div class="table-responsive-sm table-responsive-md">
                      <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="text-center">
                                <th>CUI</th>
                                <th>Nombre</th>
                                <th>Usuario</th>
                                <th>Dirección</th>
                                <th>Rol</th>
                                <th>Teléfono</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody v-if="Object.keys(items).length !== 0">
                          <template v-for="(item, index) in items.people">
                            <tr v-bind:key="index">
                                <td style="vertical-align:middle;" class="text-center" v-text="item.person.cui"></td>
                                <td style="vertical-align:middle;" v-text="concat_name(item.person.name_one,item.person.name_two,item.person.last_name_one,item.person.last_name_two)"></td>
                                <td style="vertical-align:middle;" class="text-center"><span class="badge bg-primary">{{ item.person.user.verified == 'VERIFICADO' ? 'ACTIVO' : 'INACTIVO' }}</span></td>
                                <td style="vertical-align:middle;">{{ item.person.municipality.departament.name+', '+item.person.municipality.name+', '+item.person.direction }}</td>
                                <td style="vertical-align:middle;" v-text="item.person.user.rol.name"></td>
                                <td style="vertical-align:middle;" class="text-center">
                                  <button type="button" class="btn btn-success btn-sm" @click="viewModalPhone(item.person.id)"><i class="fa fa-phone-square"></i></button> 
                                  <hr>
                                  <template v-for="(item_phone, index_phone) in item.person.phons">
                                    <b-button v-bind:key="index_phone" class="btn btn-sm" style="font-size: 10px;"  :variant="color(item_phone.company.name)">
                                      {{ item_phone.company.name }}
                                      <b-badge variant="light">{{ item_phone.number }}<span class="sr-only"></span></b-badge>
                                    </b-button>  
                                    <button v-bind:key="index_phone+'Quitar'" type="button" class="btn btn-danger btn-sm" @click="quitarPersonPhone(item)"><i class="fa fa-tty"></i></button> 
                                    <hr v-bind:key="index_phone+'Espacio'">                                    
                                  </template>                               
                                </td>
                                <td style="vertical-align:middle;" class="text-center">
                                    <button class="btn btn-primary btn-sm" @click="mapData(item)"><i class="fa fa-pencil"></i></button>
                                    <br><br>
                                    <button class="btn btn-danger btn-sm" @click="destroyPersonSchool(item)"><i class="fa fa-trash"></i></button>
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

          <div class="col-md-3 col-sm-12">
            <div class="card border-warning">
              <div class="card-header bg-warning">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">Lista de teléfonos</h3>                 
                </div>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12 col-sm-12">
<div class="row">

  <div class="col-md-12 col-sm-12">
    <div class="form-group">
      <label>Número de Teléfono</label>
      <input type="text" class="form-control" placeholder="número de teléfono"
      name="add_phone_school.number"
      v-model="form_phone_school.number"
      data-vv-as="número de teléfono"
      v-validate="'required|numeric|min:8|max:8'"
      data-vv-scope="add_phone_school"
      :class="{'input':true,'has-errors': errors.has('add_phone_school.number')}">
      <FormError :attribute_name="'add_phone_school.number'" :errors_form="errors"> </FormError>
    </div>
  </div>           
  <div class="col-md-12 col-sm-12">            
    <div class="form-group">
      <label>Compania</label>
      <multiselect v-model="form_phone_school.companies_id"
          v-validate="'required'" 
          data-vv-name="add_phone_school.companies_id"
          data-vv-as="compania"
          :options="companies" placeholder="seleccione compania"  
          :searchable="true"
          :allow-empty="false"
          :show-labels="false"
          data-vv-scope="add_phone_school"
          label="name" track-by="id">
          <span slot="noResult">No se encontro ningún registro</span>
          </multiselect>
          <FormError :attribute_name="'add_phone_school.companies_id'" :errors_form="errors"> </FormError>
    </div>
  </div>
  <div class="col-md-12 col-sm-12 text-right">
    <button type="button" class="btn btn-success btn-sm" @click="addPhoneSchool">Agregar teléfono</button>
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
        <tbody v-if="Object.keys(items).length !== 0">
          <template v-for="(item, index) in items.phons">
            <tr v-bind:key="index">
                <td class="text-center" v-text="item.number"></td>
                <td v-text="item.company.name"></td>
                <td class="text-center">
                    <button class="btn btn-danger btn-sm" @click="quitarPhoneSchool(item)"><i class="fa fa-trash"></i></button>
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
  name: "informationschool",
  components: {
      FormError
  },
  data() {
    return {
      loading: false,
      items: {},
      logo: '',
      municipalities: [],
      companies: [],
      type_persons: [],
      schools_id: 0,
      form_phone_person: {
        id: null,
        number: '',
        companies_id: null,
        people_id: null
      },
      form_person_school: {
        id: null,
        cui: '',
        name_one: '',
        name_two: '',
        last_name_one: '',
        last_name_two: '',
        direction_people: '',
        email: '',
        municipalities_id_people: null,
        type_person: null
      },
      form_phone_school: {
        id: null,
        number: '',
        companies_id: null
      },
      form: {
        id: null,
        bill: '',
        code_high_school: '',
        code_primary: '',
        direction: '',
        municipalities_id: '',
        name: '',
        nit: '',
        logo: null        
      }
    };
  },
  created() {
    let self = this;
    self.schools_id = self.$route.params.id
    self.getMunicipalities()
  },

  methods: {
    //Funcion para concatenar el nombre
    concat_name(nombre_uno, nombre_dos, apellido_uno, apellido_dos){
        let concat = ''
        concat = nombre_uno + ' '
        
        if(nombre_dos != null)
            concat = concat + nombre_dos + ' '

        concat = concat + apellido_uno + ' '
        
        if(apellido_dos != null)
            concat = concat + apellido_dos + ' '

        return concat
    },

    color(item){
      let color = '';
      switch (item) {
        case 'CLARO':
          color='danger';
          break;

        case 'TIGO':
          color='primary';
          break;

        case 'MOVISTAR':
          color='success';
          break;
      
        default:
          color='default';
          break;
      }

      return color
    },

    getAll(id) {
      let self = this;
      self.loading = true;

      self.$store.state.services.schoolService
        .get(id)
        .then(r => {
          r.data.data.forEach( function ver(item) {
            self.items = item
          })
          self.form.id = self.items.id
          self.form.name = self.items.name
          self.form.bill = self.items.bill
          self.form.code_high_school = self.items.code_high_school
          self.form.code_primary = self.items.code_primary
          self.form.direction = self.items.direction
          self.form.municipalities_id = {'id': self.items.municipality.id, 'name': self.items.municipality.departament.name+' / '+self.items.municipality.name}
          self.form.nit = self.items.nit
          self.logo = self.items.logo !== null ? self.$store.state.base_url+self.items.logo : self.$store.state.base_url+'img/logo_empty.png'
          self.loading = false; 
        })
        .catch(r => {});
    },

    //funcion, validar si se guarda o actualiza
    createOrEdit(){
      let self = this
      this.$validator.validateAll().then((result) => {
          if (result) {
              self.pasarMayusculas()
              self.update()
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

    //funcion para actualizar registro
    update(){
      let self = this
      self.loading = true
      let data = self.form
      data.logo = self.logo
      data.municipalities_id = self.form.municipalities_id.id
       
      self.$store.state.services.schoolService
        .update(data)
        .then(r => {
          self.loading = false
          if(r.response){
            self.$toastr.error(r.response.data.error, 'error')
            return
          }
          self.$toastr.success('registro actualizado con exito', 'exito')
          self.getMunicipalities()
        })
        .catch(r => {});
    },

    //Llamar los municipios
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
          self.getAll(self.schools_id);
          self.getComany()
          self.getTypePersona()
        })
        .catch(r => {});
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

    //Agregar número de teléfono de la persona
    addPhoneSchool(){
      let self = this
      self.$validator.validateAll('add_phone_school').then((result) => {
          if (result) {
            self.loading = true
            let data = self.form_phone_school
            data.companies_id = self.form_phone_school.companies_id.id
            data.schools_id = self.schools_id
            
            self.$store.state.services.phoneschoolService
              .create(data)
              .then(r => {
                self.loading = false
                if(r.response){
                  self.$toastr.error(r.response.data.error, 'error')
                  return
                }

                //Limpiar datos
                self.form_phone_school.number = ''
                self.form_phone_school.companies_id = null
                self.$validator.reset()
                self.$validator.reset()

                self.$toastr.success('registro actualizado con exito', 'exito')
                self.getMunicipalities()
              })
              .catch(r => {});
          }
      });
    }, 
    
    quitarPhoneSchool(data){
      let self = this

      self.$swal({
        title: "¿Eliminar registro?",
        text: "¿Está seguro de elminar el número de teléfono "+ data.number + '?',
        type: "warning",
        showCancelButton: true
      }).then((result) => { 
          if (result.value) { 
              self.loading = true
              self.$store.state.services.phoneschoolService
                .destroy(data)
                .then(r => {
                  self.loading = false
                  if(r.response){
                        self.$toastr.error(r.response.data.error, 'error')
                        return
                    }
                  self.$toastr.success('registro eliminado con exito', 'exito')
                  self.getMunicipalities()
                })
                .catch(r => {});
          }
      });      
    },

    clearData(){
      let self = this
      self.form_person_school.id = null
      self.form_person_school.cui = ''
      self.form_person_school.name_one = ''
      self.form_person_school.name_two = ''
      self.form_person_school.last_name_one = ''
      self.form_person_school.last_name_two = ''
      self.form_person_school.direction_people = ''
      self.form_person_school.email = ''
      self.form_person_school.municipalities_id_people = null
      self.form_person_school.type_person = null  
      self.$refs['modal_person'].hide()        
    },

    //Obtener los roles
    getTypePersona(){
      let self = this;
      self.loading = true;

      self.type_persons.push({'id': 'DIRECTOR', 'name': 'DIRECTOR'})
      self.type_persons.push({'id': 'PROFESOR', 'name': 'PROFESOR'})
      self.type_persons.push({'id': 'PRESIDENTE', 'name': 'PRESIDENTE'})
      self.type_persons.push({'id': 'OTRO', 'name': 'OTRO'})

      self.loading = false;
    }, 

    viewModal(){
        let self = this
        self.$refs['modal_person'].show()
        self.clearData()
        self.getTypePersona()
    },

    addOredit(){
      let self = this
      self.$validator.validateAll('add_person_school').then((result) => {
          if (result) {
              self.pasarMayusculas()
              self.form_person_school.id === null ? self.addPersonSchool() : self.editPersonSchool()
          }
      });
    },

    mapData(item){
      let self = this
      self.loading = true
        self.form_person_school.id = item.id
        self.form_person_school.cui = item.person.cui
        self.form_person_school.name_one = item.person.name_one
        self.form_person_school.name_two = item.person.name_two
        self.form_person_school.last_name_one = item.person.last_name_one
        self.form_person_school.last_name_two = item.person.last_name_two
        self.form_person_school.direction_people = item.person.direction
        self.form_person_school.email = item.person.email
        self.form_person_school.municipalities_id_people = {id: item.person.municipality.id, name: item.person.municipality.departament.name+' / '+item.person.municipality.name}
        self.form_person_school.type_person = {id: item.type_person, name: item.type_person}  
      self.loading = false
      self.$refs['modal_person'].show()
    },

    addPersonSchool(){
      let self = this
      self.loading = true
      let data = self.form_person_school
      data.type_person = self.form_person_school.type_person.id
      data.municipalities_id_people = self.form_person_school.municipalities_id_people.id
       
      self.$store.state.services.personschoolService
        .create(data)
        .then(r => {
          self.loading = false
          if(r.response){
            self.$toastr.error(r.response.data.error, 'error')
            return
          }
          self.$toastr.success('registro agregado con exito', 'exito')
          self.getMunicipalities()
          self.clearData()
        })
        .catch(r => {});
    },

    editPersonSchool(){
      let self = this
      self.loading = true
      let data = self.form_person_school
      data.type_person = self.form_person_school.type_person.id
      data.municipalities_id_people = self.form_person_school.municipalities_id_people.id

      self.$store.state.services.personschoolService
        .update(data)
        .then(r => {
          self.loading = false
          if(r.status != 201){
            for (let value of Object.values(r.response.data)) {
              self.$toastr.error(value, 'Mensaje')
            }
            return
          }
          self.$toastr.success('registro agregado con exito', 'exito')
          self.getMunicipalities()
          self.clearData()
        })
        .catch(r => {});
    },    

    destroyPersonSchool(data){
      let self = this

      self.$swal({
        title: "¿Eliminar registro?",
        text: "¿Está seguro de elminar el número de CUI "+ data.person.cui + '?',
        type: "warning",
        showCancelButton: true
      }).then((result) => { 
          if (result.value) { 
              self.loading = true
              self.$store.state.services.personschoolService
                .destroy(data)
                .then(r => {
                  self.loading = false
                  if(r.response){
                    self.$toastr.error(r.response.data.error, 'error')
                    return
                  }
                  self.$toastr.success('registro eliminado con exito', 'exito')
                  self.getMunicipalities()
                })
                .catch(r => {});
          }
      }); 
    },

    viewModalPhone(id){
        let self = this
        self.$refs['modal_person_phone'].show()
        self.form_phone_person.people_id = id
        self.form_phone_person.number = ''
        self.form_phone_person.companies_id = null
        self.getTypePersona()
    },

    addPersonPhone(){
      let self = this
      self.$validator.validateAll('add_phone_person').then((result) => {
          if (result) {
            self.loading = true
            let data = self.form_phone_person
            data.companies_id = self.form_phone_person.companies_id.id
            
            self.$store.state.services.phonepersonService
              .create(data)
              .then(r => {
                self.loading = false
                if(r.response){
                  self.$toastr.error(r.response.data.error, 'error')
                  return
                }

                self.$refs['modal_person_phone'].hide()
                //Limpiar datos
                self.form_phone_person.number = ''
                self.form_phone_person.companies_id = null
                self.form_phone_person.people_id = null
                self.$validator.reset()
                self.$validator.reset()

                self.$toastr.success('registro actualizado con exito', 'exito')
                self.getMunicipalities()
              })
              .catch(r => {});
          }
      });
    },

    quitarPersonPhone(data){
      let self = this

      self.$swal({
        title: "¿Eliminar registro?",
        text: "¿Está seguro de elminar el número de teléfono "+ data.number + '?',
        type: "warning",
        showCancelButton: true
      }).then((result) => { 
          if (result.value) { 
              self.loading = true
              self.$store.state.services.phonepersonService
                .destroy(data)
                .then(r => {
                  self.loading = false
                  if(r.response){
                        self.$toastr.error(r.response.data.error, 'error')
                        return
                    }
                  self.$toastr.success('registro eliminado con exito', 'exito')
                  self.getMunicipalities()
                })
                .catch(r => {});
          }
      }); 
    },

    selectedFile(e) {
      let self = this
      self.$store.state.services.schoolService.getBase64(e.target.files[0])
      .then(data =>{
          self.logo = data
        } 
      ); 
    },
  },
  computed:{
      title(){
          let self = this
          return self.form_person_school.id == null ? 'Agregar persona' : 'Editar persona con cui'+self.form_person_school.cui
      },
      title_person_phone(){
          let self = this
          return 'Agregar número de teléfono'
      }
  },
  mounted(){
    $("body").resize()
  },
};
</script>