<template>
<div>

  <div class="content-wrapper" v-loading="loading">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Pedidos</h1> 
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12" v-if="edit_form">
            <div class="card text-white">
              <div class="card-header bg-primary text-center" style="font-size: 18px;"><div>Editar orden #{{ form.order }}</div></div>
              <div class="card-body">
<form>
  <div class="row">
    <div class="col-md-4 col-sm-12">
      <div class="form-group">
        <label>Fecha para la entrega del pedido</label>
        <div class="block">
          <el-date-picker
            v-model="form.date"
            type="date"
            data-vv-name="date"
            data-vv-as="fecha"
            v-validate="'required|date_format:yyyy-MM-dd'"
            :class="{'input':true,'has-errors': errors.has('menu.date')}"                
            placeholder="Seleccionar una fecha"
            format="dd/MM/yyyy"
            :align="'center'"
            @change="gh"
            :picker-options="pickerOptions"
            data-vv-scope="menu"
            value-format="yyyy-MM-dd">
          </el-date-picker>
        </div>
        <FormError :attribute_name="'menu.date'" :errors_form="errors"> </FormError>
      </div>
    </div>
    <div class="col-md-12 col-sm-12">
      <div class="form-group">
        <label>Menú</label>
        <input type="text" class="form-control" placeholder="titulo del menú"
        name="title"
        v-model="form.title"
        data-vv-as="titulo del menú"
        v-validate="'required|min:5|max:125'"
        data-vv-scope="menu"
        :class="{'input':true,'has-errors': errors.has('menu.title')}">
        <FormError :attribute_name="'menu.title'" :errors_form="errors"> </FormError>
      </div>
    </div>  
    <div class="col-md-12 col-sm-12">
      <div class="form-group">
        <label>Descripción</label>
        <textarea class="form-control" 
        cols="10" rows="3" 
        placeholder="descripción del menú"
        name="description"
        v-model="form.description"
        data-vv-as="descripción del menú"
        v-validate="'required|min:5|max:1000'"
        data-vv-scope="menu"
        :class="{'input':true,'has-errors': errors.has('menu.description')}">
        </textarea>
        <FormError :attribute_name="'menu.description'" :errors_form="errors"> </FormError>
      </div>
    </div>                
  </div>
  <div class="row">
    <div class="col-12 text-right">
      <button type="button" class="btn btn-danger btn-sm" v-b-tooltip.hover title="cancelar" @click="close"><i class="fa fa-undo"></i> Cancelar</button>
      <button type="button" class="btn btn-primary btn-sm" v-b-tooltip.hover title="guardar" @click="update"><i class="fa fa-save"></i> Guardar</button>
    </div>
  </div>
</form>
              </div>
            </div>
          </div>
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header no-border">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">Lista de pedidos</h3>
                  <b-button-group>
                    <router-link to="/school/0/management/order/new/alimentacion/A" class="btn btn-success">ALIMENTACION</router-link>
                    <router-link to="/school/0/management/order/new/gratuidad/G" class="btn btn-info">GRATUIDAD</router-link>
                    <router-link to="/school/0/management/order/new/utiles/U" class="btn btn-primary">UTILES</router-link>
                    <router-link to="/school/0/management/order/new/valijas/V" class="btn btn-warning">VALIJAS DIDACTICA</router-link>
                  </b-button-group>                  
                </div>
              </div>
              <div class="card-body">
                <el-tabs tab-position="left" @tab-click="handleClick">
                  <el-tab-pane label="ALIMENTACION">
                    <h1>Pedidos de alimentación</h1>
                    &nbsp;
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

                          <template v-slot:row-details="data">
                            <div class="row">
                              <div class="col-sm-12 text-center align-items-center">
                                <h3><b>Código </b> {{ data.item.code }}</h3>  
                              </div>
                              <div class="col-sm-12 text-center align-items-center">
                                <h3><b>Total del Pedido </b> {{ data.item.total | currency('Q',',',2,'.','front',true) }}</h3>  
                              </div>
                              <div class="col-sm-12 text-center align-items-center">
                                <h3><b>Total del Reembolso </b> {{ data.item.refund | currency('Q',',',2,'.','front',true) }}</h3>  
                              </div>
                              <div class="col-sm-12 text-center align-items-center">
                                <h3><b>Total Facturado </b> {{ data.item.total - data.item.refund | currency('Q',',',2,'.','front',true) }}</h3>  
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-sm-12 text-center d-flex justify-content-between align-items-center">
                                <b>Progreso del pedido</b>
                                <b-progress :max="data.item.detail_total" style="font-weight: bold; font-size: 14px;" show-progress height="20px" class="w-100 mb-2">
                                  <b-progress-bar :value="data.item.detail_complete" :label="`${((data.item.detail_complete / data.item.detail_total) * 100).toFixed(2)}%`"></b-progress-bar>
                                </b-progress>
                              </div>
                            </div>
                          </template>

                          <template v-slot:cell(order)="data">
                            <div class="col-sm-12 text-center">
                              <b-button size="md" @click="data.toggleDetails" :class="data.item.complete == true ?  'mr-2 btn-success' : 'mr-2 btn-danger'">
                                {{ data.item.order }}
                              </b-button>
                            </div>
                          </template> 

                          <template v-slot:cell(date)="data">
                            <div class="text-center">{{ data.item.date | moment('dddd DD MMMM YYYY') }}</div>
                          </template>                  
                          <template v-slot:cell(created_at)="data">
                            <div class="text-center">{{ data.item.created_at | moment('dddd DD MMMM YYYY') }}</div> 
                          </template>                 
                          <template v-slot:cell(option)="data">    
                              <router-link class="btn btn-success btn-sm" v-b-tooltip.left v-b-tooltip.hover title="mostrar información" :to="'/school/management/order/detail/'+data.item.id"><i class="fa fa-eye"></i></router-link>                  
                              <button type="button" class="btn btn-warning btn-sm" v-if="!data.item.complete" v-b-tooltip.left v-b-tooltip.hover title="editar" @click="mapData(data.item)">
                                  <i class="fa fa-pencil">
                                  </i>
                              </button>                                                          
                              <button type="button" class="btn btn-danger btn-sm" v-if="!data.item.complete" v-b-tooltip.left v-b-tooltip.hover title="eliminar" @click="destroy(data.item)">
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
                  </el-tab-pane>
                  <el-tab-pane label="GRATUIDAD">
                    <h1>Pedidos de gratuidad</h1>
                    &nbsp;
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

                          <template v-slot:row-details="data">
                            <div class="row">
                              <div class="col-sm-12 text-center align-items-center">
                                <h3><b>Código </b> {{ data.item.code }}</h3>  
                              </div>
                              <div class="col-sm-12 text-center align-items-center">
                                <h3><b>Total del Pedido </b> {{ data.item.total | currency('Q',',',2,'.','front',true) }}</h3>  
                              </div>
                              <div class="col-sm-12 text-center align-items-center">
                                <h3><b>Total del Reembolso </b> {{ data.item.refund | currency('Q',',',2,'.','front',true) }}</h3>  
                              </div>
                              <div class="col-sm-12 text-center align-items-center">
                                <h3><b>Total Facturado </b> {{ data.item.total - data.item.refund | currency('Q',',',2,'.','front',true) }}</h3>  
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-sm-12 text-center d-flex justify-content-between align-items-center">
                                <b>Progreso del pedido</b>
                                <b-progress :max="data.item.detail_total" style="font-weight: bold; font-size: 14px;" show-progress height="20px" class="w-100 mb-2">
                                  <b-progress-bar :value="data.item.detail_complete" :label="`${((data.item.detail_complete / data.item.detail_total) * 100).toFixed(2)}%`"></b-progress-bar>
                                </b-progress>
                              </div>
                            </div>
                          </template>

                          <template v-slot:cell(order)="data">
                            <div class="col-sm-12 text-center">
                              <b-button size="md" @click="data.toggleDetails" :class="data.item.complete == true ?  'mr-2 btn-success' : 'mr-2 btn-danger'">
                                {{ data.item.order }}
                              </b-button>
                            </div>
                          </template> 

                          <template v-slot:cell(date)="data">
                            <div class="text-center">{{ data.item.date | moment('dddd DD MMMM YYYY') }}</div>
                          </template>                  
                          <template v-slot:cell(created_at)="data">
                            <div class="text-center">{{ data.item.created_at | moment('dddd DD MMMM YYYY') }}</div> 
                          </template>                 
                          <template v-slot:cell(option)="data">    
                              <router-link class="btn btn-success btn-sm" v-b-tooltip.left v-b-tooltip.hover title="mostrar información" :to="'/school/management/order/detail/'+data.item.id"><i class="fa fa-eye"></i></router-link>                  
                              <button type="button" class="btn btn-warning btn-sm" v-if="!data.item.complete" v-b-tooltip.left v-b-tooltip.hover title="editar" @click="mapData(data.item)">
                                  <i class="fa fa-pencil">
                                  </i>
                              </button>                                                          
                              <button type="button" class="btn btn-danger btn-sm" v-if="!data.item.complete" v-b-tooltip.left v-b-tooltip.hover title="eliminar" @click="destroy(data.item)">
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
                  </el-tab-pane>
                  <el-tab-pane label="UTILES">
                    <h1>Pedidos de utiles</h1>
                    &nbsp;
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

                          <template v-slot:row-details="data">
                            <div class="row">
                              <div class="col-sm-12 text-center align-items-center">
                                <h3><b>Código </b> {{ data.item.code }}</h3>  
                              </div>
                              <div class="col-sm-12 text-center align-items-center">
                                <h3><b>Total del Pedido </b> {{ data.item.total | currency('Q',',',2,'.','front',true) }}</h3>  
                              </div>
                              <div class="col-sm-12 text-center align-items-center">
                                <h3><b>Total del Reembolso </b> {{ data.item.refund | currency('Q',',',2,'.','front',true) }}</h3>  
                              </div>
                              <div class="col-sm-12 text-center align-items-center">
                                <h3><b>Total Facturado </b> {{ data.item.total - data.item.refund | currency('Q',',',2,'.','front',true) }}</h3>  
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-sm-12 text-center d-flex justify-content-between align-items-center">
                                <b>Progreso del pedido</b>
                                <b-progress :max="data.item.detail_total" style="font-weight: bold; font-size: 14px;" show-progress height="20px" class="w-100 mb-2">
                                  <b-progress-bar :value="data.item.detail_complete" :label="`${((data.item.detail_complete / data.item.detail_total) * 100).toFixed(2)}%`"></b-progress-bar>
                                </b-progress>
                              </div>
                            </div>
                          </template>

                          <template v-slot:cell(order)="data">
                            <div class="col-sm-12 text-center">
                              <b-button size="md" @click="data.toggleDetails" :class="data.item.complete == true ?  'mr-2 btn-success' : 'mr-2 btn-danger'">
                                {{ data.item.order }}
                              </b-button>
                            </div>
                          </template> 

                          <template v-slot:cell(date)="data">
                            <div class="text-center">{{ data.item.date | moment('dddd DD MMMM YYYY') }}</div>
                          </template>                  
                          <template v-slot:cell(created_at)="data">
                            <div class="text-center">{{ data.item.created_at | moment('dddd DD MMMM YYYY') }}</div> 
                          </template>                 
                          <template v-slot:cell(option)="data">    
                              <router-link class="btn btn-success btn-sm" v-b-tooltip.left v-b-tooltip.hover title="mostrar información" :to="'/school/management/order/detail/'+data.item.id"><i class="fa fa-eye"></i></router-link>                  
                              <button type="button" class="btn btn-warning btn-sm" v-if="!data.item.complete" v-b-tooltip.left v-b-tooltip.hover title="editar" @click="mapData(data.item)">
                                  <i class="fa fa-pencil">
                                  </i>
                              </button>                                                          
                              <button type="button" class="btn btn-danger btn-sm" v-if="!data.item.complete" v-b-tooltip.left v-b-tooltip.hover title="eliminar" @click="destroy(data.item)">
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
                  </el-tab-pane>
                  <el-tab-pane label="VALIJAS DIDACTICA">
                    <h1>Pedidos de valijas didactica</h1>
                    &nbsp;
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

                          <template v-slot:row-details="data">
                            <div class="row">
                              <div class="col-sm-12 text-center align-items-center">
                                <h3><b>Código </b> {{ data.item.code }}</h3>  
                              </div>
                              <div class="col-sm-12 text-center align-items-center">
                                <h3><b>Total del Pedido </b> {{ data.item.total | currency('Q',',',2,'.','front',true) }}</h3>  
                              </div>
                              <div class="col-sm-12 text-center align-items-center">
                                <h3><b>Total del Reembolso </b> {{ data.item.refund | currency('Q',',',2,'.','front',true) }}</h3>  
                              </div>
                              <div class="col-sm-12 text-center align-items-center">
                                <h3><b>Total Facturado </b> {{ data.item.total - data.item.refund | currency('Q',',',2,'.','front',true) }}</h3>  
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-sm-12 text-center d-flex justify-content-between align-items-center">
                                <b>Progreso del pedido</b>
                                <b-progress :max="data.item.detail_total" style="font-weight: bold; font-size: 14px;" show-progress height="20px" class="w-100 mb-2">
                                  <b-progress-bar :value="data.item.detail_complete" :label="`${((data.item.detail_complete / data.item.detail_total) * 100).toFixed(2)}%`"></b-progress-bar>
                                </b-progress>
                              </div>
                            </div>
                          </template>

                          <template v-slot:cell(order)="data">
                            <div class="col-sm-12 text-center">
                              <b-button size="md" @click="data.toggleDetails" :class="data.item.complete == true ?  'mr-2 btn-success' : 'mr-2 btn-danger'">
                                {{ data.item.order }}
                              </b-button>
                            </div>
                          </template> 

                          <template v-slot:cell(date)="data">
                            <div class="text-center">{{ data.item.date | moment('dddd DD MMMM YYYY') }}</div>
                          </template>                  
                          <template v-slot:cell(created_at)="data">
                            <div class="text-center">{{ data.item.created_at | moment('dddd DD MMMM YYYY') }}</div> 
                          </template>                 
                          <template v-slot:cell(option)="data">    
                              <router-link class="btn btn-success btn-sm" v-b-tooltip.left v-b-tooltip.hover title="mostrar información" :to="'/school/management/order/detail/'+data.item.id"><i class="fa fa-eye"></i></router-link>                  
                              <button type="button" class="btn btn-warning btn-sm" v-if="!data.item.complete" v-b-tooltip.left v-b-tooltip.hover title="editar" @click="mapData(data.item)">
                                  <i class="fa fa-pencil">
                                  </i>
                              </button>                                                          
                              <button type="button" class="btn btn-danger btn-sm" v-if="!data.item.complete" v-b-tooltip.left v-b-tooltip.hover title="eliminar" @click="destroy(data.item)">
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
                  </el-tab-pane>
                </el-tabs>
              </div>
            </div>
            <!-- /.card -->
            <!-- /.card -->
          </div>
          <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
</div>
</template>

<script>
import moment from 'moment'
import FormError from '../shared/FormError'
export default {
  name: "order",
  components: {
      FormError,
      moment
  },
  data() {
    return {
      loading: false,
      edit_form: false,
      modalidad: '',
      items: [],
      calendario: [],
      fields: [
        { key: 'order', label: '#', sortable: true },
        { key: 'title', label: 'Título', sortable: true },
        { key: 'description', label: 'Descripción', sortable: true },
        { key: 'date', label: 'Fecha de entrega', sortable: true },
        { key: 'type_order', label: 'Tipo de Pedido', sortable: true },
        { key: 'created_at', label: 'Fecha de creación', sortable: true },
        { key: 'option', label: 'Opciones', sortable: true },
      ],
      filter: null,
      currentPage: 1,
      perPage: 5,
      totalRows: 0,
      pageOptions: [ 5, 10, 25 ],
      showStringEmpty: 'no hay registros que mostrar',

      form: {
          id: null,
          title: '',
          description: '',
          date: '',
          order: ''
      } ,

      pickerOptions: {
        disabledDate(time) {
          var d = new Date();
          return d.setDate(d.getDate() - 1) > time.getTime();
        },
      },     
    };
  },
  created() {
    let self = this;
    self.getAll('ALIMENTACION');
    self.getCalendario()
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

    onFiltered (filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length
      this.currentPage = 1
    },

    handleClick(tab, event) {
      let self = this
      switch (tab.index) {
        case '0':
          self.getAll('ALIMENTACION')
          self.modalidad = 'ALIMENTACION'
          break;
      
        case '1':
          self.getAll('GRATUIDAD')
          self.modalidad = 'GRATUIDAD'
          break;

        case '2':
          self.getAll('UTILES')
          self.modalidad = 'UTILES'
          break;  

        case '3':
          self.getAll('VALIJA DIDACTICA')
          self.modalidad = 'VALIJA DIDACTICA'
          break;         
      }      
    },

    getCalendario(){
      let self = this
      self.loading = true
      self.$store.state.services.calendaryschoolService
        .get(self.$store.state.school.schools_id)
        .then(r => {
          self.calendario = r.data.data
          self.loading = false
        })
        .catch(r => {});   
    },

    getAll(type_order) {
      let self = this;
      self.loading = true;
      self.items = []

      self.$store.state.services.orderService
        .get(type_order)
        .then(r => {
          self.loading = false; 
          self.items = r.data.data;
          self.totalRows = self.items.length
        })
        .catch(r => {});
    },

    //mapear datos a formulario
    mapData(data){
        let self = this
        self.form.id = data.id
        self.form.title = data.title
        self.form.description = data.description
        self.form.date = data.date
        self.form.order = data.order
        self.edit_form = true
    },    

    gh(){
      let self = this
      self.calendario.forEach(function (item) {
        if(moment(item.date, 'YYYY-MM-DD').format('DD/MM/YYYY') == moment(self.form.date, 'YYYY-MM-DD').format('DD/MM/YYYY'))
        {
          self.$swal({
            title: "ERROR",
            text: "LA FECHA QUE SELECCIONO TIENE UNA ACTIVIDAD PROGRAMADA, "+item.title,
            type: "error",
            showCancelButton: false
          }).then((result) => {
              self.form.date = ''
          }); 
          return 
        }
      });    
    },

    //funcion, validar si se guarda o actualiza
    update(){
      let self = this      
      let data = self.form
      self.$validator.validateAll('menu').then((result) => {
          if (result) {
              self.loading = true
              self.pasarMayusculas()
              self.$store.state.services.orderService
                .update(data)
                .then(r => {
                  self.loading = false
                  if( self.interceptar_error(r) == 0) return 
                  self.$toastr.success('registro actualizado con exito', 'exito') 
                  self.getAll(r.data.data.type_order)
                  self.close()
                })
                .catch(r => {});
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

    //funcion para eliminar registro
    destroy(data){
      let self = this

      self.$swal({
        title: "¿Eliminar registro?",
        text: "¿Está seguro que desea eliminar la orden # "+ data.order + '?',
        type: "warning",
        showCancelButton: true
      }).then((result) => { // <--
          if (result.value) { // <-- if confirmed
              self.loading = true
              self.$store.state.services.orderService
                .destroy(data)
                .then(r => {
                  self.loading = false
                  if( self.interceptar_error(r) == 0) return
                  self.$toastr.success('registro eliminado con exito', 'exito') 
                  self.getAll(r.data.data.type_order)
                  self.close()
                })
                .catch(r => {});
          }
      });
    },

    //limpiar data de formulario
    clearData(){
        let self = this

        Object.keys(self.form).forEach(function(key,index) {
          if(typeof self.form[key] === "string") 
            self.form[key] = ''
          else if (typeof self.form[key] === "boolean") 
            self.form[key] = true
          else if (typeof self.form[key] === "number") 
            self.form[key] = null
        });

        self.$validator.reset()
        self.$validator.reset()
    },

    //cerrar modal limpiar registros
    close(){
        let self= this
        self.edit_form = false
    },
    
    //repetir pedido, comprar si tiene dinero suficiente para realizar el pedido de nuevo
    repeatOrder(id){
      let self = this;
      self.loading = true;

      self.$store.state.services.repeatorderService
        .get(id)
        .then(r => {
          self.loading = false; 
          if( self.interceptar_error(r) == 0) return
        })
        .catch(r => {});
    }
  },
  mounted(){
    $("body").resize()
  },
};
</script>