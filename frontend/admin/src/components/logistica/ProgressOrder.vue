<template>
<div>

  <div class="content-wrapper" v-loading="loading">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          </div>
        </div>
      </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <b-jumbotron header="Pedidos existentes">
            <hr>
            <div class="row">
                <div class="col-md-3 col-sm-12 col-3 text-center">
                    <button type="button" @click="getAlimentacion" class="btn btn-block btn-outline-success btn-lg">ALIMENTACION</button>
                </div>
                <div class="col-md-3 col-sm-12 col-3 text-center">
                    <button type="button" @click="getGratuidad" class="btn btn-block btn-outline-primary btn-lg">GRATUIDAD</button>
                </div>
                <div class="col-md-3 col-sm-12 col-3 text-center">
                    <button type="button" @click="getUtiles" class="btn btn-block btn-outline-info btn-lg">UTILES</button>
                </div>
                <div class="col-md-3 col-sm-12 col-3 text-center">
                    <button type="button" @click="getValijas" class="btn btn-block btn-outline-warning btn-lg">VALIJAS DIDACTICAS</button>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-3 col-sm-12 col-3">
                    <template v-for="(item,index) in items_alimentacion">
                        <router-link v-b-tooltip :title="'gestionar orden #'+item.order" v-bind:key="index" :to="'/assign_product/'+item.id" >
                            <div v-bind:key="'1.'+index" :class="color_a(item.date)">
                                <span class="info-box-icon"><i class="fa fa-apple"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-number">{{ item.school.name }}</span>
                                    <span class="info-box-number">Código, {{ item.code }}</span>
                                    <span class="info-box-text">Pedido #{{ item.order }}</span>
                                    <span class="info-box-text">Menú, {{ item.title }}</span>
                                    <span class="info-box-text">Fecha de entrega, {{ item.date | moment('dddd DD MMMM YYYY') }}</span>
                                    <span class="info-box-text">Monto, {{ item.total | currency('Q ',',',2,'.','front',true) }}</span>
                                    <div class="progress">
                                        <div class="progress-bar" :style="'width:'+Number((item.detail_complete*100)/item.detail_total).toFixed(2)+'%'"></div>
                                    </div>
                                    <span class="progress-description text-center"> {{ Number((item.detail_complete*100)/item.detail_total).toFixed(2) }}%</span>
                                </div>
                            </div>
                        </router-link>
                    </template>
                    <div v-if="items_alimentacion.length === 0 && view_a" class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="fa fa-apple"></i> ¡Mensaje!</h5>
                        No hay pedidos de alimentación.
                    </div>
                </div>
                <div class="col-md-3 col-sm-12 col-3">
                    <template v-for="(item,index) in items_gratuidad">
                        <router-link v-b-tooltip title="gestionar orden" v-bind:key="index" :to="'/assign_product/'+item.id" >
                            <div v-bind:key="'1.'+index" :class="color_g(item.date)">
                                <span class="info-box-icon"><i class="fa fa-file"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-number">{{ item.school.name }}</span>
                                    <span class="info-box-number">Código, {{ item.code }}</span>
                                    <span class="info-box-text">Pedido #{{ item.order }}</span>
                                    <span class="info-box-text">Menú, {{ item.title }}</span>
                                    <span class="info-box-text">Fecha de entrega, {{ item.date | moment('dddd DD MMMM YYYY') }}</span>
                                    <span class="info-box-text">Monto, {{ item.total | currency('Q ',',',2,'.','front',true) }}</span>
                                    <div class="progress" style="width:100%;">
                                        <div class="progress-bar" :style="'width:'+Number((item.detail_complete*100)/item.detail_total).toFixed(2)+'%'"></div>
                                    </div>
                                    <span class="progress-description text-center"> {{ Number((item.detail_complete*100)/item.detail_total).toFixed(2) }}%</span>
                                </div>
                            </div>
                        </router-link>
                    </template>
                    <div v-if="items_gratuidad.length === 0 && view_g" class="alert alert-primary alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="fa fa-file"></i> ¡Mensaje!</h5>
                        No hay pedidos de gratuidad.
                    </div>
                </div>
                <div class="col-md-3 col-sm-12 col-3">
                    <template v-for="(item,index) in items_utiles">
                        <router-link v-b-tooltip title="gestionar orden" v-bind:key="index" :to="'/assign_product/'+item.id" >
                            <div v-bind:key="'1.'+index" :class="color_u(item.date)">
                                <span class="info-box-icon"><i class="fa fa-book"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-number">{{ item.school.name }}</span>
                                    <span class="info-box-number">Código, {{ item.code }}</span>
                                    <span class="info-box-text">Pedido #{{ item.order }}</span>
                                    <span class="info-box-text">Menú, {{ item.title }}</span>
                                    <span class="info-box-text">Fecha de entrega, {{ item.date | moment('dddd DD MMMM YYYY') }}</span>
                                    <span class="info-box-text">Monto, {{ item.total | currency('Q ',',',2,'.','front',true) }}</span>
                                    <div class="progress" style="width:100%;">
                                        <div class="progress-bar" :style="'width:'+Number((item.detail_complete*100)/item.detail_total).toFixed(2)+'%'"></div>
                                    </div>
                                    <span class="progress-description text-center"> {{ Number((item.detail_complete*100)/item.detail_total).toFixed(2) }}%</span>
                                </div>
                            </div>
                        </router-link>
                    </template>
                    <div v-if="items_utiles.length === 0 && view_u" class="alert alert-info alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="fa fa-book"></i> ¡Mensaje!</h5>
                        No hay pedidos de utiles.
                    </div>
                </div>
                <div class="col-md-3 col-sm-12 col-3">
                    <template v-for="(item,index) in items_valijas">
                        <router-link v-b-tooltip title="gestionar orden" v-bind:key="index" :to="'/assign_product/'+item.id" >
                            <div v-bind:key="'1.'+index" :class="color_v(item.date)">
                                <span class="info-box-icon"><i class="fa fa-book"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-number">{{ item.school.name }}</span>
                                    <span class="info-box-number">Código, {{ item.code }}</span>
                                    <span class="info-box-text">Pedido #{{ item.order }}</span>
                                    <span class="info-box-text">Menú, {{ item.title }}</span>
                                    <span class="info-box-text">Fecha de entrega, {{ item.date | moment('dddd DD MMMM YYYY') }}</span>
                                    <span class="info-box-text">Monto, {{ item.total | currency('Q ',',',2,'.','front',true) }}</span>
                                    <div class="progress" style="width:100%;">
                                        <div class="progress-bar" :style="'width:'+Number((item.detail_complete*100)/item.detail_total).toFixed(2)+'%'"></div>
                                    </div>
                                    <span class="progress-description text-center"> {{ Number((item.detail_complete*100)/item.detail_total).toFixed(2) }}%</span>
                                </div>
                            </div>
                        </router-link>
                    </template>
                    <div v-if="items_valijas.length === 0 && view_v" class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="fa fa-book"></i> ¡Mensaje!</h5>
                        No hay pedidos de valijas didactica.
                    </div>
                </div>
            </div>            
        </b-jumbotron>
      </div>
    </div>
    <!-- /.content -->
  </div>
</div>
</template>

<script>
import moment from 'moment'
import FormError from '../shared/FormError'
export default {
  name: "progressorder",
  components: {
      FormError
  },
  data() {
    return {
      loading: false,
      view_a: false,
      view_g: false,
      view_u: false,
      view_v: false,
      items_alimentacion: [],
      items_gratuidad: [],
      items_utiles: [],
      items_valijas: [],
    };
  },
  created() {

  },

  methods: {
    getAlimentacion() {
      let self = this;
      self.loading = true;

      self.$store.state.services.progressorderService
        .getOrder('alimentacion')
        .then(r => {
          self.loading = false; 
          self.items_alimentacion = r.data.data;
          self.view_a = true
        })
        .catch(r => {});
    },

    getGratuidad() {
      let self = this;
      self.loading = true;

      self.$store.state.services.progressorderService
        .getOrder('gratuidad')
        .then(r => {
          self.loading = false; 
          self.items_gratuidad = r.data.data;
          self.view_g = true
        })
        .catch(r => {});
    },

    getUtiles() {
      let self = this;
      self.loading = true;

      self.$store.state.services.progressorderService
        .getOrder('utiles')
        .then(r => {
          self.loading = false; 
          self.items_utiles = r.data.data;
          self.view_u = true
        })
        .catch(r => {});
    },

    getValijas() {
      let self = this;
      self.loading = true;

      self.$store.state.services.progressorderService
        .getOrder('valija didactica')
        .then(r => {
          self.loading = false; 
          self.items_valijas = r.data.data;
          self.view_v = true
        })
        .catch(r => {});
    },

    color_a(fecha){
        if(fecha == moment(new Date()).format('YYYY-MM-DD'))
            return 'info-box bg-warning';
        else if(fecha > moment(new Date()).format('YYYY-MM-DD'))
            return 'info-box bg-success'
        else if(fecha < moment(new Date()).format('YYYY-MM-DD'))
            return 'info-box bg-danger'
    },

    color_g(fecha){
        if(fecha == moment(new Date()).format('YYYY-MM-DD'))
            return 'info-box bg-warning';
        else if(fecha > moment(new Date()).format('YYYY-MM-DD'))
            return 'info-box bg-primary'
        else if(fecha < moment(new Date()).format('YYYY-MM-DD'))
            return 'info-box bg-danger'
    },

    color_u(fecha){
        if(fecha == moment(new Date()).format('YYYY-MM-DD'))
            return 'info-box bg-warning';
        else if(fecha > moment(new Date()).format('YYYY-MM-DD'))
            return 'info-box bg-info'
        else if(fecha < moment(new Date()).format('YYYY-MM-DD'))
            return 'info-box bg-danger'
    },

    color_v(fecha){
        if(fecha == moment(new Date()).format('YYYY-MM-DD'))
            return 'info-box bg-warning';
        else if(fecha > moment(new Date()).format('YYYY-MM-DD'))
            return 'info-box bg-default'
        else if(fecha < moment(new Date()).format('YYYY-MM-DD'))
            return 'info-box bg-danger'
    }
  },

  mounted(){
        $("body").resize()
  },  
};
</script>