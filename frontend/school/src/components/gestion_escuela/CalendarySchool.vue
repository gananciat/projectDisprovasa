<template>
<div>

  <div class="content-wrapper" v-loading="loading">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Calendarización</h1> 
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
                <div class="card">
                    <div class="card-header no-border">
                        <div class="d-flex justify-content-between">
                        <h3 class="card-title">Agregar fecha para calendarizar</h3>               
                        </div>
                    </div>
                    <div class="card-body">
    <div class="row">
        <div class="col-md-9 col-sm-12">
            <div class="form-group">
                <label>Título</label>
                <input type="text" class="form-control" placeholder="titulo de la calendarización"
                name="title"
                v-model="form.title"
                data-vv-as="titulo de la calendarización"
                v-validate="'required|min:5|max:50'"
                data-vv-scope="calendar"
                :class="{'input':true,'has-errors': errors.has('calendar.title')}">
                <FormError :attribute_name="'calendar.title'" :errors_form="errors"> </FormError>
            </div>
        </div>  
        <div class="col-md-3 col-sm-12">
            <div class="form-group">
                <label>Fecha a calendarizar</label>
                <div class="block">
                <el-date-picker
                    v-model="form.date"
                    type="date"
                    data-vv-name="date"
                    data-vv-as="fecha"
                    v-validate="'required|date_format:yyyy-MM-dd'"
                    :class="{'input':true,'has-errors': errors.has('calendar.date')}"                
                    placeholder="Seleccionar una fecha"
                    format="dd/MM/yyyy"
                    data-vv-scope="calendar"
                    value-format="yyyy-MM-dd">
                </el-date-picker>
                </div>
                <FormError :attribute_name="'calendar.date'" :errors_form="errors"> </FormError>
            </div>
        </div>                    
    </div>
    <div class="row">
        <div class="col-12 text-right">
        <button type="button" class="btn btn-primary btn-sm" v-b-tooltip.hover title="guardar" @click="createOrEdit"><i class="fa fa-save"></i> Guardar</button>
        </div>
    </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <b-jumbotron :header="'Historial de la Calendarización del año '+anio">
                    <div class="timeline">
                        <template v-for="(item, index) in items">
                            <div class="time-label" v-bind:key="index">
                                <span :class="color(item.date)">{{ item.date | moment('dddd DD MMMM YYYY') }}</span>
                            </div>
                            <div  v-bind:key="'.1'+index">
                                <i class="fa fa-calendar"></i>
                                <div class="timeline-item">
                                    <span class="time">
                                        <button type="button" class="btn btn-danger btn-sm" v-if="puede_eliminar(item.date)" v-b-tooltip.hover title="eliminar" @click="destroy(item)">
                                            <i class="fa fa-trash">
                                            </i>
                                        </button>                                        
                                    </span>
                                    <h3 class="timeline-header">#{{ index+1 }} <b-badge variant="primary">{{ item.title }}</b-badge></h3>
                                    <div class="timeline-body">
                                        ESTÁ ACTIVIDAD LA REGISTRO LA PERSONA {{ concat_name(item.person.name_one,item.person.name_two,item.person.last_name_one,item.person.last_name_two) }}
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </b-jumbotron>
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
  name: "calendaryschool",
  components: {
      FormError,
      moment 
  },
  data() {
    return {
      loading: false,
      items: [],
      fecha_actual: '',
      schools_id: null,
      anio: moment(new Date()).format("YYYY"),
      form: {
          title: '',
          date: new Date(),
      }      
    };
  },
  created() {
    let self = this;
    var hoy = new Date()
    self.fecha_actual = hoy.getDate()+"/"+(hoy.getMonth()+1)+"/"+hoy.getFullYear()
    
    self.schools_id = self.$store.state.school.schools_id
    self.getAll(self.schools_id)
  },
  methods: {
    //Clasificar error
    interceptar_error(r){
      let self = this

        if(r.response){
            if(r.response.status === 422){
                this.$toastr.info(r.response.data.error, 'Mensaje')
                return 0
            }

            if(r.response.status != 201 && r.response.status != 422){
                for (let value of Object.values(r.response.data)) {
                    self.$toastr.error(value, 'Mensaje')
                }
                return 0
            }
        }
    },

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

    getAll(id) {
      let self = this;
      self.loading = true;
      self.items = []
        
      self.$store.state.services.calendaryschoolService
        .get(id)
        .then(r => {
            self.loading = false; 
            self.items = r.data.data;
            self.form.title = ''
            self.form.date = new Date()

            self.$validator.reset()
            self.$validator.reset()         
        })
        .catch(r => {});
    },

    //funcion, validar si se guarda o actualiza
    createOrEdit(){
      let self = this
      
      self.$validator.validateAll('calendar').then((result) => {
          if (result) {
            self.pasarMayusculas()
            self.create()
          } 
      });
    },

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
      let data = self.form
      data.schools_id = self.schools_id

      self.$swal({
        title: "Verificar",
        text: "¿ESTA SEGURO QUE DESEA CALENDARIZAR LA FECHA "+ moment(data.date, 'YYYY-MM-DD').format('DD/MM/YYYY') + "?",
        type: "info",
        showCancelButton: true
      }).then((result) => {
          if (result.value) {
            self.loading = true  
            self.$store.state.services.calendaryschoolService
              .create(data)
              .then(r => {
                self.loading = false
                if( self.interceptar_error(r) == 0) return
                self.$toastr.success('registro agregado con exito', 'exito') 
                self.getAll(self.schools_id)
              })
              .catch(r => {});                     
          }
      });
    },

    //funcion para eliminar registro
    destroy(data){
      let self = this

      self.$swal({
        title: "¿Eliminar registro?",
        text: "¿ESTÁ SEGURO QUE DESEA ELIMINAR LA FECHA "+ moment(data.date, 'YYYY-MM-DD').format('DD/MM/YYYY') + '?',
        type: "warning",
        showCancelButton: true
      }).then((result) => { 
          if (result.value) { 
              self.loading = true
              self.$store.state.services.calendaryschoolService
                .destroy(data)
                .then(r => {
                  self.loading = false
                  if( self.interceptar_error(r) == 0) return
                  self.$toastr.success('registro eliminado con exito', 'exito') 
                  self.getAll(self.schools_id)
                })
                .catch(r => {});
          }
      });
    },

    puede_eliminar(fecha){
        if(moment(fecha).format('YYYY-MM-DD') <= moment(new Date()).format('YYYY-MM-DD'))
            return false
        else
            return true
    },

    color(fecha){
        if(moment(fecha).format('YYYY-MM-DD') == moment(new Date()).format('YYYY-MM-DD'))
            return 'bg-yellow';
        else if(moment(fecha).format('YYYY-MM-DD') > moment(new Date()).format('YYYY-MM-DD'))
            return 'bg-green'
        else if(moment(fecha).format('YYYY-MM-DD') < moment(new Date()).format('YYYY-MM-DD'))
            return 'bg-red'
    }

  },
  mounted(){
    $("body").resize()
  },
};
</script>