<template>

<body class="login-page">
<div class="login-box" style="margin-top: -150px;">
  <div class="login-logo">
    <a><b>Sistema Proalsa - </b> Empresa</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">ingreso al sistema</p>

      <form action="#">
        <div class="form-group has-feedback">
          <input 
            type="email" 
            class="form-control"
            v-model="credentials.email"
            name="correo"
            data-vv-as="correo electronico"
            placeholder="Email"
            v-validate="'required|email'"
            :class="{'input':true,'has-errors': errors.has('correo')}">
            <FormError :attribute_name="'correo'" :errors_form="errors"> </FormError>
        </div>
        <div class="form-group has-feedback">
          <input 
            @keypress.enter="beforeLogin"
            type="password" 
            name="contraseña"
            class="form-control" 
            v-model="credentials.password"
            placeholder="Password"
            v-validate="'required|min:6'"
            :class="{'input':true,'has-errors': errors.has('contraseña')}">
             <FormError :attribute_name="'contraseña'" :errors_form="errors"> </FormError>
        </div>
        <div class="row">
          <!-- /.col -->
          <div class="col-5">
            <button type="button" @click="beforeLogin" class="btn btn-primary btn-block btn-flat"><i class="fa fa-sign-in"></i> ingresar</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>

    <br><br><br><br><br><br><br>
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
            if(self.$store.state.global.captureError(r)){
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

  computed: {
    getLogo(){
      let self = this
      return self.$store.state.base_url+'img/logo.jpeg'
    }
  }
}
</script>