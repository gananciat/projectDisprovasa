<template>
  <div>
    <div id="print_div" class="row">
        imprimir
    </div>
  </div>
</template>

<script>
import moment from 'moment'
export default {
  name: 'invoice_download',
  data() {
      return {
          llading: false,
          invoice_name: ''
      }
  },

  created() {
    let self = this
  },

  mounted() {
    this.printDiv('print_div')
  },

  methods: {
    printDiv(nombreDiv) {
      var printContents = document.getElementById(nombreDiv).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
    }
  },
   computed: {

    },
}
</script>
