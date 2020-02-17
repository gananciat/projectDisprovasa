<template>

<body class="login-page">
<div class="login-box">
  <div class="login-logo">
    <img src="../../assets/logo.jpeg" width="60%" alt="Logo" class="brand-image elevation-3">
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg"><a><b>Sistema Disprovasa </b> <br> Escuela</a></p>

      <form action="#">
        <div class="form-group has-feedback">
          <input 
            type="email" 
            class="form-control"
            v-model="credentials.email"
            name="correo"
            data-vv-as="correo electronico"
            placeholder="Email"
            @keypress.enter="beforeLogin"
            v-validate="'required|email'"
            :class="{'input':true,'has-errors': errors.has('correo')}">
            <FormError :attribute_name="'correo'" :errors_form="errors"> </FormError>
        </div>
        <div class="form-group has-feedback">
          <input 
            type="password" 
            name="contraseña"
            class="form-control" 
            v-model="credentials.password"
            @keypress.enter="beforeLogin"
            placeholder="Password"
            v-validate="'required|min:6'"
            :class="{'input':true,'has-errors': errors.has('contraseña')}">
             <FormError :attribute_name="'contraseña'" :errors_form="errors"> </FormError>
        </div>
        <div class="row">
          <!-- /.col -->
          <div class="col-4">
            <button type="button" @click="beforeLogin" v-b-tooltip.left v-b-tooltip.hover title="ingresar" class="btn btn-primary btn-block btn-flat">Ingresar</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>

<!-- /.login-box -->
</body>
</template>

<script>
import FormError from '../shared/FormError'
import auth from '../../auth'
export default {
  name: 'Login',
  components: {
      FormError
  },
  data(){
    return {
      credentials: {
        email: '',
        password: ''
      }
    }
  },
  created(){
    let self = this
  },
  methods: {
      login(){
        let self = this
        self.loading = true
        self.$store.state.services.loginService
        .login(self.credentials)
        .then(r => {
            self.loading = false
            if(r.response !== undefined){
                self.$toastr.error(r.response.data.error, 'error')
                return
            }
            self.$store.dispatch('guardarToken',r.data)
            self.$router.push('/')
            auth.getUser()
        }).catch(e => {
        })
        },
        beforeLogin(){
          let self = this
          self.$validator.validateAll().then((result) => {
            if (result) {
                self.login()
              }
          });
        }
  },
  mounted(){
        $("body").resize()
  }
}
</script>
<style scoped>
  .brand-image {
      border-radius: 10%
  }
</style>
