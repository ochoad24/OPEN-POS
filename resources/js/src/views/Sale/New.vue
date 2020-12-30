<template>
    <div>
        <!-- BEGIN LOADING -->
        <v-dialog v-model="loading" hide-overlay persistent width="300">
            <v-card color="teal" dark>
                <v-card-text>
                    Cargando
                    <v-progress-linear
                        indeterminate
                        color="white"
                        class="mb-0"
                    ></v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>
        <!-- END LOADING -->
        <!-- BEGIN BREADCRUMBS -->
        <v-breadcrumbs :items="items">
            <template v-slot:divider>
                <v-icon>mdi-forward</v-icon>
            </template>
        </v-breadcrumbs>
        <!-- END BREADCRUMBS -->
        <!-- BEGIN CARD -->
        <v-card class="mx-auto my-12">
            <v-card-title>Nuevo Venta</v-card-title>
            <v-container fluid>
                <v-row>
                    <v-col cols="4">
                        <v-subheader>Seleccione los productos</v-subheader>
                    </v-col>
                    <v-col cols="6">
                        <vue-multiselect
                            v-model="productSelect"
                            label="name"
                            track-by="id"
                            :custom-label="customLabel"
                            :options="products"
                            :searchable="true"
                            :loading="isLoading"
                            placeholder="Seleccione un producto"
                            @search-change="onSearch"
                            @select="selectedProduct"
                            @close="closeSelectProduct"
                            ref="selectProduct"
                        >
                            ><span slot="noResult"
                                >Producto no encontrado.</span
                            >
                        </vue-multiselect>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col cols="12">
                        <v-data-table
                            :headers="headers"
                            :items="productsSelected"
                            :items-per-page="20"
                            class="elevation-1"
                        >
                            <template v-slot:[`item.priceSale`]="{ item }">
                                {{ mymoney(item.priceSale) }}
                            </template>
                            <!-- <template v-slot:[`item.priceMinSale`]="{ item }">
                                {{ mymoney(item.priceMinSale) }}
                            </template> -->
                            <!-- <template v-slot:[`item.stock`]="{ item }">
                                {{ mystock(item.stock) }}
                            </template> -->
                            <template v-slot:[`item.subtotal`]="{ item }">
                                {{ mymoney(item.subtotal) }}
                            </template>
                        </v-data-table>
                    </v-col>
                </v-row>
            </v-container>
            <v-card-text>
                <div>
                    <h1 class="text-end">{{ mymoney(total) }}</h1>
                </div>
            </v-card-text>
            <v-card-actions>
                <v-btn>
                    Cobrar
                </v-btn>
            </v-card-actions>
        </v-card>
        <!-- END CARD -->
    </div>
</template>
<script>
import Multiselect from "vue-multiselect";
import _ from "lodash";
import { eight } from "../../utils/eight";
export default {
    components: {
        "vue-multiselect": Multiselect
    },
    data: () => ({
        items: [
            {
                text: "Inicio",
                disabled: false,
                href: "/"
            },
            {
                text: "Nueva venta",
                disabled: true,
                href: "/venta/nuevo"
            }
        ],
        headers: [
            { text: "Nombre", value: "name" },
            { text: "Código", value: "code" },
            { text: "Precio de Venta", value: "priceSale" },
            // { text: "Precio mayorista", value: "priceMinSale" },
            // { text: "Stock", value: "stock" },
            { text: "Cantidad", value: "quantity" },
            { text: "Subtotal", value: "subtotal" }
        ],
        products: [],
        productSelect: null,
        productsSelected: [],
        loading: false,
        isLoading: false,
        total: 0
    }),
    methods: {
        customLabel({ name, code }) {
            return `${name} – ${code}`;
        },
        mymoney(value) {
            return eight.money(parseFloat(value));
        },
        mypercentage(value) {
            return eight.percentage(parseFloat(value));
        },
        mystock(value) {
            return eight.stock(parseFloat(value));
        },
        closeSelectProduct(product) {
            if (this.productSelect !== null) {
                this.productSelect = null;
                this.$refs.selectProduct.$el.focus();
            }
        },
        selectedProduct(product) {
            let vm = this;
            let isExist = vm.productsSelected.findIndex(
                x => x.id === product.id
            );
            if (isExist < 0) {
                product.subtotal = vm.parseDecimal(
                    vm.parseDecimal(product.quantity) *
                        vm.parseDecimal(product.priceSale)
                );
                vm.productsSelected.unshift(product);
                vm.calculateTotal();
            } else {
                vm.productsSelected[isExist].quantity += 1;
                vm.productsSelected[isExist].subtotal = vm.parseDecimal(
                    vm.parseDecimal(vm.productsSelected[isExist].quantity) *
                        vm.parseDecimal(vm.productsSelected[isExist].priceSale)
                );
                vm.calculateTotal();
            }
        },
        calculateTotal() {
            let vm = this;
            vm.total = 0;
            vm.productsSelected.forEach(product => {
                vm.total += vm.parseDecimal(product.subtotal);
            });
            vm.total = vm.parseDecimal(vm.total);
        },
        onSearch(search) {
            // console.log(search);
            this.isLoading = true;
            this.search(search, this);
        },
        search: _.debounce((search, vm) => {
            axios
                .get(`/api/getProducts?search=${search}`)
                .then(response => {
                    vm.isLoading = false;
                    vm.products = response.data;
                })
                .catch(error => {
                    vm.isLoading = false;
                    console.log(error);
                });
        }, 350),
        parseDecimal(value) {
            return parseFloat(this.parseDecimalFixed(value));
        },
        parseDecimalFixed(value) {
            return parseFloat(value).toFixed(2);
        }
    },
    created() {
        window.addEventListener("keydown", e => {
            // if (e.key == "C"||e.key == "c") {
            //     console.log(e.key);
            // }
            if (e.ctrlKey && e.key === 'z') {
                alert('Undo!');
            }
            // console.log(e.key);
        });
    },
    mounted() {
        this.onSearch();
        this.$refs.selectProduct.$el.focus();
    }
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
