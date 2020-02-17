<template>
<!--Contenido-->
      <!-- Content Wrapper. Contains page content -->
        <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-8">
            <h1 class="m-0 text-dark" v-if="school !== null">BALANCE {{ school.name }}</h1>
          </div><!-- /.col -->
          <div class="col-sm-4">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#/school">ESCUELAS</a></li>
              <li class="breadcrumb-item active">BALANCE ESCUELA</li>
            </ol>
          </div>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Modal para nuevo registro -->
        <b-modal ref="nuevo" size="lg" :title="'editar balance '+form.type_balance+' codigo '+form.code" hide-footer class="modal-backdrop" no-close-on-backdrop>
          <form>
            <div class="row">
              <div class="form-group col-md-4 col-lg-4 col-xs-12">
                <label>Fecha inicio</label>
                <input type="date" class="form-control" placeholder="fecha inicio"
                name="fecha_inicio"
                v-model="form.start_date"
                data-vv-as="fecha inicio"
                v-validate="'required'"
              :class="{'input':true,'has-errors': errors.has('fecha_innicio')}">
              <FormError :attribute_name="'fecha_inicio'" :errors_form="errors"> </FormError>
              </div>
              <div class="form-group col-md-4 col-lg-4 col-xs-12">
                <label>Fecha fin</label>
                <input type="date" class="form-control" placeholder="fecha fin"
                name="fecha_fin"
                v-model="form.end_date"
                data-vv-as="fecha fin"
                v-validate="dateRules(form)"
              :class="{'input':true,'has-errors': errors.has('fecha_fin')}">
              <FormError :attribute_name="'fecha_fin'" :errors_form="errors"> </FormError>
              </div>
              <div class="form-group col-md-4 col-lg-4 col-xs-12">
                <label>Balance</label>
                <input type="text" class="form-control" placeholder="fecha inicio"
                name="balance"
                v-model="form.balance"
                data-vv-as="Balance"
                v-validate="'required|decimal'"
              :class="{'input':true,'has-errors': errors.has('balance')}">
              <FormError :attribute_name="'balance'" :errors_form="errors"> </FormError>
              </div>
            </div>
              
              <div class="row">
                <!-- /.col -->
                <div class="col-12 text-right">
                  <button type="button" class="btn btn-danger btn-sm" @click="close"><i class="fa fa-undo"></i> Cancelar</button>
                  <button type="button" class="btn btn-primary btn-sm" @click="beforeEdit"><i class="fa fa-save"></i> Guardar</button>
                </div>
                <!-- /.col -->
              </div>
            </form>
        </b-modal>

    <!-- Main content -->
    <div class="content" v-loading="loading">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header no-border">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title" v-if="school !== null">Registro balance 
                    <b-button variant="success" size="sm" @click="$router.push('/school_create_balance/'+school.id)"><i class="fa fa-plus"></i> nuevo</b-button></h3>
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
                      <template v-slot:cell(year)="data">
                        {{ data.item.year }}
                      </template>
                      <template v-slot:cell(code)="data">
                        {{ data.item.code }}
                      </template>
                      <template v-slot:cell(dates)="data">
                        {{data.item.start_date | moment('DD/MM/YYYY')}} - {{data.item.end_date|moment('DD/MM/YYYY')}}
                      </template>
                      <template v-slot:cell(property)="data">
                        {{ data.item.type_balance }}
                      </template>
                      <template v-slot:cell(disbursement)="data">
                        {{ data.item.disbursement.name }}
                      </template>
                      <template v-slot:cell(balance)="data">
                        {{data.item.balance | currency('Q ')}}
                      </template>
                      <template v-slot:cell(subtraction)="data">
                        {{data.item.subtraction | currency('Q ')}}
                      </template>
                      <template v-slot:cell(option)="data">
                          <button v-if="data.item.subtraction == 0" type="button" class="btn btn-info btn-sm" @click="mapData(data.item)" v-b-tooltip title="editar">
                              <i class="fa fa-pencil">
                              </i>
                          </button>
                          <button v-if="data.item.subtraction == 0" type="button" class="btn btn-danger btn-sm" @click="destroy(data.item)" v-b-tooltip title="eliminar">
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
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!--Fin-Contenido-->
</template>

<script>
import FormError from '../../shared/FormError'
import moment from 'moment'
export default {
  name: 'index_balance',
  components: {
    FormError
  },
  data() {
    return {
      loading: false,
      school: null,
      items: [],
      fields: [
        { key: 'year', label: 'Año', sortable: true },
        { key: 'code', label: 'Codigo', sortable: true },
        { key: 'dates', label: 'Fechas', sortable: true },
        { key: 'property', label: 'Pertenece', sortable: true },
        { key: 'disbursement', label: 'Desembolso', sortable: true },
        { key: 'balance', label: 'Balance', sortable: true },
        { key: 'subtraction', label: 'Balance actual', sortable: true },
        { key: 'option', label: 'Opciones', sortable: true },
      ],
      filter: null,
      currentPage: 1,
      perPage: 5,
      totalRows: 0,
      pageOptions: [ 5, 10, 25 ],
      showStringEmpty: 'no hay balances que mostrar',
      form: {
        id: null,
        start_date: '',
        end_date: '',
        balance: null,
        type_balance: '',
        code: ''
      }
    }
  },

  created() {
    let self = this
    self.get(self.$route.params.id)
  },

  methods: {
    onFiltered (filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length
      this.currentPage = 1
    },

    get(id) {
      let self = this;
      self.loading = true;

      self.$store.state.services.schoolService
        .getOne(id)
        .then(r => {
          self.loading = false
          self.school = r.data.data
          self.items = self.school.balances
          self.totalRows = self.items.length
        })
        .catch(r => {});
    },

    getPhones(phones){
      let self = this
      return phones.map(e => e.number).join(", ")
    },

        //funcion para actualizar registro
    update(){
      let self = this
      self.loading = true
      let data = self.form
      self.$store.state.services.balanceService
        .update(data)
        .then(r => {
          self.loading = false
          self.loading = false
          if(self.$store.state.global.captureError(r)){
            return
          }
          this.$toastr.success('registro eliminado con exito', 'exito')
          self.get(self.school.id)
          self.close()
        })
        .catch(r => {});
    },

    //funcion para eliminar registro
    destroy(data){
      let self = this

      self.$swal({
        title: "¿Eliminar registro?",
        text: "Esta seguro de elminar "+data.code + '?',
        type: "warning",
        showCancelButton: true
      }).then((result) => { // <--
          if (result.value) { // <-- if confirmed
              self.loading = true
              self.$store.state.services.balanceService
                .destroy(data)
                .then(r => {
                  self.loading = false
                  self.loading = false
                  if(self.$store.state.global.captureError(r)){
                    return
                  }
                  this.$toastr.success('registro eliminado con exito', 'exito')
                  self.get(self.school.id)
                  self.close()
                })
                .catch(r => {});
          }
      });
    },

    //funcion, validar si se guarda o actualiza
    beforeEdit(){
      this.$validator.validateAll().then((result) => {
          if (result) {
              self.update()
           }
      });

      let self = this
    },

     //mapear datos a formulario
    mapData(data){
        let self = this
        self.form.id = data.id
        self.form.code = data.code
        self.form.start_date = data.start_date
        self.form.end_date = data.end_date
        self.form.type_balance = data.type_balance
        self.form.balance = data.balance
        this.$refs['nuevo'].show()
    },

    //cerrar modal limpiar registros
    close(){
        let self= this
        self.$refs['nuevo'].hide()
    },

     dateRules (item) {
      if(item.start_date !== ''){
        var year = moment(item.start_date).year()
        var finish_date = year+'-12-31'
        var theDate = moment(item.start_date)
        var newDate = theDate.add(1, "days")
        var init_date = moment(theDate.toString()).format('YYYY-MM-DD')
        return {
          required: true,
          date_format: 'yyyy-MM-dd',
          date_between: [init_date, finish_date, true]
        };
      }
      return {
        required: true
      }
        
    }
  },

  computed:{
  }
  
}
</script>