<template>
    <div>
        <!-- BEGIN BREADCRUMBS -->
        <v-breadcrumbs :items="items">
            <template v-slot:divider>
                <v-icon>mdi-forward</v-icon>
            </template>
        </v-breadcrumbs>
        <!-- END BREADCRUMBS -->
        <div id="printMe">
            <div class="ticket">
                <img
                src="/img/VOCHER.jpg"
                alt="Logotipo"/>
                <p class="centrado">
                    TICKET DE VENTA
                </p>
                <table>
                    <thead>
                        <tr>
                            <th class="cantidad">CANT</th>
                            <th class="producto">PROD</th>
                            <th class="precio">Q</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="product in products" :key="product.id">
                            <td class="cantidad">{{ product.quantity }}</td>
                            <td class="producto">{{ product.name }}</td>
                            <td class="precio">{{ product.subtotal }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>TOTAL</td>
                            <td colspan="2" class="text-right">
                                {{ mymoney(sale.amount) }}
                            </td>
                        </tr>
                        <tr>
                            <td>PAGADO</td>
                            <td colspan="2" class="text-right">
                                {{ mymoney(sale.payment) }}
                            </td>
                        </tr>
                        <tr>
                            <td>CAMBIO</td>
                            <td colspan="2" class="text-right">
                                {{ mymoney(sale.exchange) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <p class="centrado">
                    ¡GRACIAS POR SU COMPRA!<br />eight-software.tk
                </p>
            </div>
        </div>
        <v-btn class="mr-4" color="teal" @click="print">
            imprimir
        </v-btn>
    </div>
</template>
<script>
import Vue from "vue";
import VueHtmlToPaper from "vue-html-to-paper";
import axios from "axios";
import { eight } from "../../utils/eight";

const options = {
    name: "_blank",
    specs: ["fullscreen=yes", "titlebar=no", "scrollbars=no"],
    styles: ["/css/voucher.css"]
};

Vue.use(VueHtmlToPaper, options);
export default {
    data: () => ({
        idSale: 0,
        sale: {},
        products: [],
        items: [
            {
                text: "Inicio",
                disabled: false,
                href: "/"
            },
            {
                text: "Nueva venta",
                disabled: false,
                href: "/venta/nuevo"
            },
            {
                text: "Imprimir venta",
                disabled: true,
                href: "/venta/imprimir"
            }
        ]
    }),
    methods: {
        mymoney(value) {
            return eight.money(parseFloat(value));
        },
        print() {
            this.$htmlToPaper("printMe");
        },
        getData() {
            let vm = this;
            axios
                .get(`/api/getSale/${vm.$route.query.id}`)
                .then(response => {
                    // console.log(response.data[0);
                    vm.sale = response.data[0].sale;
                    vm.products = response.data[0].products;
                })
                .catch(error => {
                    console.log(error);
                });
        }
    },
    mounted() {
        this.getData();
    }
};
</script>
