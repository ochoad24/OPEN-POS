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
    <!-- BEGIN DELETE MODAL -->
    <v-row justify="center">
      <v-dialog v-model="dialogDelete" persistent max-width="500">
        <v-card>
          <v-card-title class="headline">
            Eliminar producto?
          </v-card-title>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="red" @click="deleteProduct()">
              Eliminar
            </v-btn>
            <v-btn color="teal" @click="dialogDelete = false">
              Cancelar
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row>
    <!-- END DELETE MODAL -->
    <!-- BEGIN CARD -->
    <v-card>
      <v-card-title>
        Lista de productos
        <v-spacer></v-spacer>
        <v-text-field
          v-model="search"
          append-icon="mdi-magnify"
          label="Buscar"
          single-line
          hide-details
        ></v-text-field>
        <v-spacer></v-spacer>
        <v-btn color="teal" link :to="'/producto/nuevo'">
            nuevo producto
          </v-btn>
      </v-card-title>
      <v-data-table
        :headers="headers"
        :items="products"
        :items-per-page="10"
        class="elevation-1"
        :search="search"
      >
        <template v-slot:[`item.price`]="{ item }">
          {{ mymoney(item.price) }}
        </template>
        <template v-slot:[`item.priceSale`]="{ item }">
          {{ mymoney(item.priceSale) }}
        </template>
        <template v-slot:[`item.priceMinSale`]="{ item }">
          {{ mymoney(item.priceMinSale) }}
        </template>
        <template v-slot:[`item.profit`]="{ item }">
          {{ mypercentage(item.profit) }}
        </template>
        <template v-slot:[`item.stock`]="{ item }">
          {{ mystock(item.stock) }}
        </template>
        <template v-slot:[`item.actions`]="{ item }">
          <v-tooltip top>
            <template v-slot:activator="{ on, attrs }">
              <v-icon
                v-bind="attrs"
                v-on="on"
                small
                class="mr-2"
                @click="editProduct(item)"
              >
                mdi-pencil
              </v-icon>
            </template>
            <span>Editar</span>
          </v-tooltip>
          <v-tooltip top>
            <template v-slot:activator="{ on, attrs }">
              <v-icon
                v-bind="attrs"
                v-on="on"
                small
                @click="showDialogProduct(item)"
              >
                mdi-delete
              </v-icon>
            </template>
            <span>Eliminar</span>
          </v-tooltip>
          <v-tooltip top>
            <template v-slot:activator="{ on, attrs }">
              <v-icon
                v-bind="attrs"
                v-on="on"
                small
                @click="printcodeProduct(item)"
              >
                mdi-barcode
              </v-icon>
            </template>
            <span>Imprimir codigo</span>
          </v-tooltip>
        </template>
      </v-data-table>
    </v-card>

    <!-- </v-card> -->
    <!-- END CARD -->
  </div>
</template>
<script>
import axios from "axios";
/* eslint-disable no-unused-vars */
import { eight } from "../../utils/eight";
/* eslint-enable no-unused-vars */
export default {
  data: () => ({
    items: [
      {
        text: "Inicio",
        disabled: false,
        href: "/",
      },
      {
        text: "Lista de productos",
        disabled: true,
        href: "/producto",
      },
    ],
    headers: [
      { text: "Nombre", value: "name" },
      { text: "Código", value: "code" },
      { text: "Precio de compra", value: "price" },
      { text: "Precio de Venta", value: "priceSale" },
      { text: "Precio mayorista", value: "priceMinSale" },
      { text: "Porcentaje de ganancia", value: "profit" },
      { text: "Cantidad", value: "stock" },
      { text: "Descripción", value: "description" },
      { text: "Acciones", value: "actions", sortable: false },
    ],
    products: [],
    loading: false,
    dialogDelete: false,
    idProduct: 0,
    search: "",
  }),
  methods: {
    editProduct(item) {
      let vm=this;
      vm.$router.push({ path: `/producto/editar?id=${item.id}` });
    },
    printcodeProduct(item){
      let vm=this;
      vm.$router.push({path: `/producto/imprimir?code=${item.code}`});
    },
    showDialogProduct(item) {
      this.idProduct = item.id;
      this.dialogDelete = true;
    },
    deleteProduct() {
      let vm = this;
      vm.loading = true;
      axios
        .delete(`/api/products/${vm.idProduct}`)
        .then(() => {
          vm.loading = false;
          vm.$toastr.s("Producto eliminado exitosamente");
          vm.idProduct = 0;
          vm.dialogDelete = false;
          vm.getProducts();
        })
        .catch((error) => {
          console.log(error);
          vm.loading = false;
        });
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
    getProducts() {
      let vm = this;
      vm.loading = true;
      axios
        .get("/api/products")
        .then((response) => {
          vm.products = response.data;
          vm.loading = false;
        })
        .catch((error) => {
          console.log(error);
          vm.loading = false;
        });
    },
  },
  mounted() {
    this.getProducts();
  },
};
</script>
