<template>
    <div>
        <v-row>
            <v-col cols="4">
              <v-subheader>Cantidad de codigo a imprimir</v-subheader>
            </v-col>
            <v-col cols="8">
              <v-text-field
                type="number"
                v-model="quantity"
              ></v-text-field>
            </v-col>
          </v-row>
        <div id="printMe">
            <barcode v-bind:value="barcodeValue" v-for="(n,index) in parseInt(quantity)" :key="index">
                Show this if the rendering fails.
            </barcode>
        </div>
        <v-btn class="mr-4" color="teal" @click="print">
            imprimir
          </v-btn>
    </div>
</template>
<script>
import VueBarcode from "vue-barcode";
import Vue from "vue";
import VueHtmlToPaper from "vue-html-to-paper";
import axios from 'axios';

const options = {
    name: "_blank",
    specs: ["fullscreen=yes", "titlebar=no", "scrollbars=no"],
    styles: [
        "/css/bootstrap.min.css",
        "/js/bootstrap.min.js",
        "/js/jquery.min.js",
        "/css/voucher.css"
    ]
};

Vue.use(VueHtmlToPaper, options);
export default {
    data: () => ({
        barcodeValue: "",
        quantity:1,
    }),
    components: {
        barcode: VueBarcode
    },
    methods: {
        print() {
            this.$htmlToPaper("printMe");
        },
    },
    mounted(){
        this.barcodeValue=this.$route.query.code;
    }
};
</script>
