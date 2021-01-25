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
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
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
                            <template v-slot:[`item.priceMinSale`]="{ item }">
                                {{ mymoney(item.priceMinSale) }}
                            </template>
                            <template v-slot:[`item.stock`]="{ item }" :class="item.stock<=0?'red':'green'">     
                                {{ mystock(item.stock) }}
                            </template>
                            <template v-slot:[`item.quantity`]="{ item }">
                                {{ mystock(item.quantity) }}
                            </template>
                            <template v-slot:[`item.typePrice`]="{ item }">
                                <template v-if="item.typePrice === 1">
                                    <v-chip
                                        class="ma-2"
                                        color="green"
                                        text-color="white"
                                        label
                                    >
                                        NORMAL
                                    </v-chip>
                                </template>
                                <template v-else>
                                    <v-chip
                                        class="ma-2"
                                        color="yellow darken-1"
                                        text-color="white"
                                        label
                                    >
                                        MAYORISTA
                                    </v-chip>
                                </template>
                            </template>
                            <template v-slot:[`item.subtotal`]="{ item }">
                                {{ mymoney(item.subtotal) }}
                            </template>
                            <template v-slot:[`item.actions`]="{ item }">
                                <v-tooltip top>
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-icon
                                            v-bind="attrs"
                                            v-on="on"
                                            small
                                            class="mr-2"
                                            @click="deleteProduct(item)"
                                        >
                                            mdi-delete
                                        </v-icon>
                                    </template>
                                    <span>Quitar</span>
                                </v-tooltip>
                                <v-tooltip top>
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-icon
                                            v-bind="attrs"
                                            v-on="on"
                                            small
                                            class="mr-2"
                                            @click="changePrice(item)"
                                        >
                                            mdi-arrow-left-right
                                        </v-icon>
                                    </template>
                                    <span>Cambiar precio</span>
                                </v-tooltip>
                                <v-tooltip top>
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-icon
                                            v-bind="attrs"
                                            v-on="on"
                                            small
                                            class="mr-2"
                                            @click="openDialogQuantity(item)"
                                        >
                                            mdi-cash
                                        </v-icon>
                                    </template>
                                    <span>Cantidad</span>
                                </v-tooltip>
                            </template>
                        </v-data-table>
                    </v-col>
                </v-row>
            </v-container>
            <v-card-text>
                <div>
                    <h1 class="text-end">Total: {{ mymoney(total) }}</h1>
                </div>
            </v-card-text>
            <v-card-actions>
                <v-btn @click="calculateExchange()">
                    Cobrar
                </v-btn>
            </v-card-actions>
        </v-card>
        <!-- END CARD -->
        <!-- BEGIN MODAL COBRAR -->
        <v-row justify="center">
            <v-dialog
                v-model="dialogExchange"
                persistent
                max-width="800px"
                :open-on-focus="true"
            >
                <v-card>
                    <v-card-title>
                        <span class="headline">Calcular cambio</span>
                    </v-card-title>
                    <v-card-text>
                        <v-container>
                            <v-row>
                                <v-col cols="12">
                                    <h2>Total: {{ mymoney(total) }}</h2>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="12">
                                    <v-text-field
                                        v-model="amount"
                                        label="Monto"
                                        required
                                        type="number"
                                        prefix="Q"
                                        autofocus
                                        @input="calculate()"
                                        @blur="calculate()"
                                    ></v-text-field>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="12">
                                    <h2>Cambio: {{ mymoney(exchange) }}</h2>
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="blue darken-1"
                            text
                            @click="dialogExchange = false"
                        >
                            Cancelar
                        </v-btn>
                        <v-btn color="blue darken-1" text @click="payment()">
                            Guardar
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-row>
        <!-- END MODAL COBRAR -->
        <!-- BEGIN MODAL COBRAR -->
        <v-row justify="center">
            <v-dialog
                v-model="dialogQuantity"
                persistent
                max-width="800px"
                :open-on-focus="true"
            >
                <v-card>
                    <v-card-title>
                        <span class="headline">Cambiar cantidad</span>
                    </v-card-title>
                    <v-card-text>
                        <v-container>
                            <v-row>
                                <v-col cols="12">
                                    <v-text-field
                                        v-model="quantity"
                                        label="Cantidad"
                                        required
                                        type="number"
                                        autofocus
                                    ></v-text-field>
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="blue darken-1"
                            text
                            @click="dialogQuantity = false"
                        >
                            Cancelar
                        </v-btn>
                        <v-btn
                            color="blue darken-1"
                            text
                            @click="saveQuantity()"
                        >
                            Guardar
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-row>
        <!-- END MODAL COBRAR -->
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
            { text: "Precio mayorista", value: "priceMinSale" },
            { text: "Tipo de precio", value: "typePrice" },
            { text: "Stock", value: "stock" },
            { text: "Cantidad", value: "quantity" },
            { text: "Subtotal", value: "subtotal" },
            { text: "Acciones", value: "actions", sortable: false }
        ],
        products: [],
        productSelect: null,
        productsSelected: [],
        loading: false,
        isLoading: false,
        total: 0,
        amount: null,
        exchange: 0,
        dialogExchange: false,
        dialogQuantity: false,
        quantity: 0,
        idProduct: 0
    }),
    methods: {
        openDialogQuantity(item) {
            this.quantity = item.quantity;
            this.idProduct = item.id;
            this.dialogQuantity = true;
        },
        saveQuantity() {
            let vm = this;
            if(vm.dialogExchange===true)
                return;
            if (
                vm.quantity === null ||
                vm.quantity === 0 ||
                vm.quantity <= 0 ||
                vm.quantity === ""
            ) {
                vm.$toastr.e("La cantidad debe ser mayor a 0");
                return;
            }
            let isExist = vm.productsSelected.findIndex(
                x => x.id === vm.idProduct
            );
            // RECALCULATE AMOUNT
            if (isExist > -1) {
                vm.productsSelected[isExist].quantity = vm.quantity;
                vm.productsSelected[isExist].subtotal = vm.parseDecimal(
                    vm.parseDecimal(vm.productsSelected[isExist].quantity) *
                        vm.parseDecimal(vm.productsSelected[isExist].price)
                );
                vm.calculateTotal();
                vm.dialogQuantity = false;
            }
        },
        changePrice(item) {
            let vm = this;
            let isExist = vm.productsSelected.findIndex(x => x.id === item.id);

            if (isExist > -1) {
                if (vm.productsSelected[isExist].typePrice === 1) {
                    vm.productsSelected[isExist].typePrice = 0;
                    vm.productsSelected[isExist].price =
                        vm.productsSelected[isExist].priceMinSale;
                } else {
                    vm.productsSelected[isExist].typePrice = 1;
                    vm.productsSelected[isExist].price =
                        vm.productsSelected[isExist].priceSale;
                }
                // RECALCULATE AMOUNT
                vm.productsSelected[isExist].subtotal = vm.parseDecimal(
                    vm.parseDecimal(vm.productsSelected[isExist].quantity) *
                        vm.parseDecimal(vm.productsSelected[isExist].price)
                );
                vm.calculateTotal();
            }
        },
        deleteProduct(item) {
            let vm = this;
            let idProduct = vm.productsSelected.findIndex(
                x => x.id === item.id
            );
            if (idProduct > -1) {
                vm.productsSelected.splice(idProduct, 1);
            }
            vm.calculateTotal();
        },
        payment() {
            let vm = this;
            if(vm.dialogQuantity===true)
                return;
            if (vm.exchange < 0) {
                vm.$toastr.e("El cambio no puede ser mayor a 0");
                return;
            }
            if (
                vm.amount === null ||
                vm.amount === 0 ||
                vm.amount <= 0 ||
                vm.amount === ""
            ) {
                vm.$toastr.e("El monto debe ser mayor a 0");
                return;
            }
            // SAVE PAYMENT
            axios.post('/api/saveSale',{
                amount:vm.total,
                exchange:vm.exchange,
                payment:vm.amount,
                products: JSON.stringify(vm.productsSelected)
            }).then((response)=>{
                // console.log(response);
                vm.$toastr.s(response.data.message);
                vm.$router.push({path: `/venta/imprimir?id=${response.data.id}`});
            }).catch((error)=>{
                // console.log(error);
                vm.$toastr.e(error.response.data.message);
            });
        },
        calculate() {
            let vm = this;
            if (
                vm.amount === null ||
                vm.amount === 0 ||
                vm.amount <= 0 ||
                vm.amount === ""
            ) {
                vm.$toastr.e(
                    "Para calcular el cambio se debe ingresar un monto"
                );
                vm.exchange = 0;
                return;
            }
            vm.exchange = vm.parseDecimal(
                vm.parseDecimal(vm.amount) - vm.parseDecimal(vm.total)
            );
        },
        calculateExchange() {
            let vm = this;
            if(vm.dialogExchange===true||vm.dialogQuantity===true)
                return;
            if (vm.productsSelected.length < 1) {
                vm.$toastr.e("Para cobrar es necesario tener productos");
                return;
            }
            vm.productsSelected.forEach(product => {
                if (product.quantity < 1) {
                    vm.$toastr.e(
                        `El producto ${product.name} debe ser mayor a 0`
                    );
                    return;
                }
            });
            vm.dialogExchange = true;
            // vm.$refs.amount.focus();
        },
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
                        vm.parseDecimal(product.price)
                );
                vm.productsSelected.unshift(product);
                vm.calculateTotal();
            } else {
                vm.productsSelected[isExist].quantity += 1;
                vm.productsSelected[isExist].subtotal = vm.parseDecimal(
                    vm.parseDecimal(vm.productsSelected[isExist].quantity) *
                        vm.parseDecimal(vm.productsSelected[isExist].price)
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
            if (e.ctrlKey && e.altKey && (e.key === "a" || e.key === "A")) {
                this.calculateExchange();
            }
            if (e.ctrlKey && e.altKey && (e.key === "s" || e.key === "S")) {
                this.payment();
            }
            if (e.ctrlKey && e.altKey && (e.key === "d" || e.key === "D")) {
                this.saveQuantity();
            }
        });
    },
    mounted() {
        this.onSearch("A");
        this.$refs.selectProduct.$el.focus();
    }
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
