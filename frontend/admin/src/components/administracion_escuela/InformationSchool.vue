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
                <h3 class="card-title">{{ form.name }}</h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12 col-sm-12">


  <form>
    <div class="row">
      <div class="col-md-12 col-sm-12">
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
      </div>

      <div class="col-md-4 col-sm-12">
        <div class="form-group">
          <label>NIT</label>
          <input type="text" class="form-control" placeholder="nit"
          name="nit"
          v-model="form.nit"
          data-vv-as="nit"
          v-validate="'required'"
          :class="{'input':true,'has-errors': errors.has('nit')}">
          <FormError :attribute_name="'nit'" :errors_form="errors"> </FormError>
        </div>
      </div> 

      <div class="col-md-4 col-sm-12">
        <div class="form-group">
          <label>Código Primaria</label>
          <input type="text" class="form-control" placeholder="código primaria"
          name="code_primary"
          v-model="form.code_primary"
          data-vv-as="código primaria"
          v-validate="'required'"
          :class="{'input':true,'has-errors': errors.has('code_primary')}">
          <FormError :attribute_name="'code_primary'" :errors_form="errors"> </FormError>
        </div>
      </div> 

      <div class="col-md-4 col-sm-12">
        <div class="form-group">
          <label>Código Preprimaria</label>
          <input type="text" class="form-control" placeholder="código preprimaria"
          name="code_high_school"
          v-model="form.code_high_school"
          data-vv-as="código preprimaria"
          v-validate="'required'"
          :class="{'input':true,'has-errors': errors.has('code_high_school')}">
          <FormError :attribute_name="'code_high_school'" :errors_form="errors"> </FormError>
        </div>
      </div> 

      <div class="col-md-12 col-sm-12">
        <div class="form-group">
          <label>Nombre Factura</label>
          <input type="text" class="form-control" placeholder="nombre a facturar"
          name="bill"
          v-model="form.bill"
          data-vv-as="nombre a facturar"
          v-validate="'required'"
          :class="{'input':true,'has-errors': errors.has('bill')}">
          <FormError :attribute_name="'bill'" :errors_form="errors"> </FormError>
        </div>
      </div>  

      <div class="col-md-5 col-sm-12">
        <div class="form-group">
          <label>Municipio</label>
          <multiselect v-model="form.municipalities_id"
              v-validate="'required'" 
              data-vv-name="municipalities_id"
              data-vv-as="municipio"
              :options="municipalities" placeholder="seleccione municipio"  
              :searchable="true"
              :allow-empty="false"
              :show-labels="false"
              label="name" track-by="id">
              <span slot="noResult">No se encontro ningún registro</span>
              </multiselect>
              <FormError :attribute_name="'municipalities_id'" :errors_form="errors"> </FormError>
        </div>
      </div>

      <div class="col-md-7 col-sm-12">
        <div class="form-group">
          <label>Dirección</label>
          <input type="text" class="form-control" placeholder="dirección"
          name="direction"
          v-model="form.direction"
          data-vv-as="dirección"
          v-validate="'required'"
          :class="{'input':true,'has-errors': errors.has('direction')}">
          <FormError :attribute_name="'direction'" :errors_form="errors"> </FormError>
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
      municipalities: [],
      form: {
        id: null,
        bill: '',
        code_high_school: '',
        code_primary: '',
        direction: '',
        municipalities_id: '',
        name: '',
        nit: '',        
      }
    };
  },
  created() {
    let self = this;
    self.getMunicipalities()
    self.getAll();
  },

  methods: {
    getAll() {
      let self = this;
      self.loading = true;

      self.$store.state.services.schoolService
        .get(self.$route.params.id)
        .then(r => {
                    console.log(r.data.data)
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

    //funcion para actualizar registro
    update(){
      let self = this
      self.loading = true
      let data = self.form
      data.municipalities_id = self.form.municipalities_id.id
       
      self.$store.state.services.schoolService
        .update(data)
        .then(r => {
          console.log(r)
          self.loading = false
          if(r.response){
            this.$toastr.error(r.response.data.error, 'error')
            return
          }
          this.$toastr.success('registro actualizado con exito', 'exito')
          self.getAll()
        })
        .catch(r => {console.log(r)});
    },

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
  },

  mounted(){
    $("body").resize()
  },
};
</script>