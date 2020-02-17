<template>
<!--Contenido-->
      <!-- Content Wrapper. Contains page content -->
        <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" v-if="school !== null">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-8">
            <h1 class="m-0 text-dark">NUEVO REGISTRO BALANCE</h1>
          </div><!-- /.col -->
           <div class="col-sm-4">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#/balance">ESCUELAS</a></li>
              <li class="breadcrumb-item"><a :href="'#/school_balance/'+school.id">BALANCE</a></li>
              <li class="breadcrumb-item active">NUEVA BALANCE</li>
            </ol>
          </div>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12 col-lg-12">
            
          <div class="card">
              <div class="card-header no-border">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">{{ school.name }} 
                    <button @click="mapForms(school)" type="button" class="btn btn-primary btn-sm"><i class="fa fa-refresh"></i></button>
                  </h3>
                </div>
              </div>
              <div class="card-body">
                  <div class="row">
                    <div class="col-md-12 col-lg-12" v-for="(item,i) in items" :key="item.code+i">
                    <div class="card">
                      <div class="card-header">
                        <h6 class="text-primary text-bold">CODIGO: {{item.code}}</h6>
                      </div>
                      <div class="card-body">
                        <div class="table-responsive">
                          <table class="table table-bordered">
                            <thead>
                              <tr>
                                <th>Tipo balance</th>
                                <th>Fecha inicio</th>
                                <th>Fecha fin</th>
                                <th>Balance</th>
                                <th>Opcion</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr v-for="(item2, i2) in item.items" :key="i2">
                                
                                <td>{{item2.type_balance}}</td>
                                <td>
                                  <div class="">
                                      <input type="date" class="form-control form-control-sm" placeholder="ingrese fecha compra"
                                      :name="item2.start_date_validate"
                                      v-model="item2.start_date"
                                      :data-vv-as="'fecha inicio '+item2.type_balance"
                                      v-validate="'required'"
                                      :class="{'input':true,'has-errors': errors.has(item2.start_date_validate)}">
                                    <FormError :attribute_name="item2.start_date_validate" :errors_form="errors"> </FormError>
                                  </div>
                                </td>
                                <td>
                                  <div class="">
                                      <input type="date" class="form-control form-control-sm" placeholder="ingrese fecha compra"
                                      :name="item2.end_date_validate"
                                      v-model="item2.end_date"
                                      :data-vv-as="'fecha fin '+item2.type_balance"
                                      v-validate="dateRules(item2)"
                                      :class="{'input':true,'has-errors': errors.has(item2.end_date_validate)}">
                                    <FormError :attribute_name="item2.end_date_validate" :errors_form="errors"> </FormError>
                                  </div>
                                </td>
                                <td>
                                  <div class="">
                                      <div class="form-group">
                                        <input type="text" class="form-control" :placeholder="'BALANCE '+item2.type_balance"
                                        :name="item2.balance_validate"
                                        v-model="item2.balance"
                                        :data-vv-as="'balance ' +item2.type_balance"
                                        v-validate="'required|decimal'"
                                        :class="{'input':true,'has-errors': errors.has(item2.balance_validate)}">
                                        <FormError :attribute_name="item2.balance_validate" :errors_form="errors"> </FormError>
                                    </div>
                                  </div>
                                </td>
                                <td>
                                  <button @click="removeItem(item,item2)" type="button" class="btn btn-danger btn-sm"><i class="fa fa-minus"></i></button>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                    </div>
                  </div>
                <div>
                    <!-- /.col -->
                    <div class="col-12 text-right" v-if="validationButton">
                    <button type="button" class="btn btn-primary btn-sm" @click="beforeCreate"><i class="fa fa-save"></i> Guardar</button>
                    </div>
                    <!-- /.col -->
                </div>
              </div>
            </div>
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
  name: 'Default',
  components: {
      FormError
  },
  data() {
    return {
        loading: false,
        school_id: null,
        school: null,
        items: [],
        form: {
          items: []
        }
    }
  },

  created() {
    let self = this
    self.school_id = self.$route.params.id
    self.get(self.school_id)
  },

  methods: {
    get(id) {
      let self = this;
      self.loading = true;

      self.$store.state.services.schoolService
        .getOne(id)
        .then(r => {
          self.loading = false
          self.school = r.data.data
          self.mapForms(self.school)
        })
        .catch(r => {});
    },

    create(){
        let self = this
        self.form.items = self.items
        var data = self.form
        self.$store.state.services.balanceService
        .create(data)
        .then(r => {
          self.loading = false
          if(self.$store.state.global.captureError(r)){
            return
          }
          this.$toastr.success('registro agregado con exito', 'exito')
          self.$router.push('/school_balance/'+self.school_id)
        })
        .catch(r => {});
    },

     //funcion, validar si se guarda o actualiza
    beforeCreate(){
      let self = this
      self.$validator.validateAll().then((result) => {
          if (result) {
              self.create()
           }
      });
    },

    mapForms(school){
      let self = this
      self.$validator.reset()
      self.$validator.reset()

      self.items = []
      var items = []
      if(school.code_primary !== ''){
        items = forms(school.code_primary)
        self.items.push({
          code: school.code_primary,
          school_id: self.school_id,
          items: items
        })
      }

      if(school.code_high_school !== ''){
        items = forms(school.code_high_school)
        self.items.push({
          code: school.code_high_school,
          school_id: self.school_id,
          items: items
        })
      }

      function forms(code){
        var items2 = []
        for(var i=0; i<4; i++){
          var type_balance = i == 0 ? 'ALIMENTACION' : i == 1 ? 'GRATUIDAD' : i==2 ? 'UTILES' : 'VALIJA DIDACTICA'
          items2.push({
            type_balance: type_balance,
            balance : null,
            start_date : '',
            end_date: '',
            year: null,
            balance_validate: code+'balance'+i,
            start_date_validate: code+'start_date'+i,
            end_date_validate: code+'end_date'+i,
          })
        }
        return items2
      }
    },

    removeItem(first_item, second_item){
      let self = this
      var i = first_item.items.indexOf(second_item)
      first_item.items.splice(i,1)
    },

    getValidationName(key,type_balance,index){
      if(key == null){
        return 'balance '+type_balance+index
      }
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

  computed: {
    validationButton(){
      let self = this
      var valid = false
      self.items.forEach(item => {
        if(item.items.length > 0){
          valid = true
        }
      })
      return valid
    }
  },
}
</script>