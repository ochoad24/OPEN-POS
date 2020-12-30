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
      <v-card-title>Nuevo producto</v-card-title>
      <v-container fluid>
        <form>
          <v-row>
            <v-col cols="4">
              <v-subheader>Nombre del producto</v-subheader>
            </v-col>
            <v-col cols="8">
              <v-text-field
                v-model="name"
                :error-messages="nameErrors"
                :counter="512"
                required
                @input="$v.name.$touch()"
                @blur="$v.name.$touch()"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="4">
              <v-subheader>Precio de compra</v-subheader>
            </v-col>
            <v-col cols="8">
              <v-text-field
                v-model="price"
                :error-messages="priceErrors"
                type="number"
                prefix="Q"
                required
                @input="$v.price.$touch()"
                @blur="$v.price.$touch()"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="4">
              <v-subheader>Ganacia</v-subheader>
            </v-col>
            <v-col cols="2">
              <v-select
                v-model="select"
                :items="itemsSelect"
                label="Select"
                persistent-hint
                single-line
                @change="calculateProfit()"
              ></v-select>
            </v-col>
            <v-col cols="6">
              <v-text-field
                v-model="profitShow"
                :error-messages="profitShowErrors"
                type="number"
                required
                @input="calculateProfit()"
                @blur="calculateProfit()"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="4">
              <v-subheader>Precio de venta</v-subheader>
            </v-col>
            <v-col cols="8">
              <v-text-field
                v-model="priceSale"
                :error-messages="priceSaleErrors"
                type="number"
                prefix="Q"
                required
                readonly
                @input="$v.priceSale.$touch()"
                @blur="$v.priceSale.$touch()"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="4">
              <v-subheader>Precio mayorista</v-subheader>
            </v-col>
            <v-col cols="8">
              <v-text-field
                v-model="priceMinSale"
                :error-messages="priceMinSaleErrors"
                type="number"
                prefix="Q"
                required
                @input="$v.priceMinSale.$touch()"
                @blur="$v.priceMinSale.$touch()"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="4">
              <v-subheader>Cantidad</v-subheader>
            </v-col>
            <v-col cols="8">
              <v-text-field
                v-model="stock"
                :error-messages="stockErrors"
                type="number"
                required
                @input="$v.stock.$touch()"
                @blur="$v.stock.$touch()"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="4">
              <v-subheader>Descripción</v-subheader>
            </v-col>
            <v-col cols="8">
              <v-textarea v-model="description" :counter="512"></v-textarea>
            </v-col>
          </v-row>
          <v-btn class="mr-4" color="teal" @click="submit">
            guardar
          </v-btn>
          <v-btn class="mr-4" color="deep-orange" @click="cancel">
            cancelar
          </v-btn>
          <v-btn class="mr-4" color="orange" @click="clear">
            limpiar
          </v-btn>
        </form>
      </v-container>
    </v-card>
    <!-- END CARD -->
  </div>
</template>
<script>
import axios from "axios";
import { validationMixin } from "vuelidate";
import { required } from "vuelidate/lib/validators";
export default {
  mixins: [validationMixin],

  validations: {
    name: { required },
    price: { required },
    priceSale: { required },
    priceMinSale: { required },
    profitShow: { required },
    stock: { required },
  },
  computed: {
    nameErrors() {
      const errors = [];
      if (!this.$v.name.$dirty) return errors;
      !this.$v.name.required &&
        errors.push("El nombre del producto es requerido.");
      return errors;
    },
    priceErrors() {
      const errors = [];
      if (!this.$v.price.$dirty) return errors;
      !this.$v.price.required &&
        errors.push("El precio de compra del producto es requerido.");
      return errors;
    },
    priceSaleErrors() {
      const errors = [];
      if (!this.$v.priceSale.$dirty) return errors;
      !this.$v.priceSale.required &&
        errors.push("El precio de venta del producto es requerido.");
      return errors;
    },
    priceMinSaleErrors() {
      const errors = [];
      if (!this.$v.priceMinSale.$dirty) return errors;
      !this.$v.priceMinSale.required &&
        errors.push("El precio mayorista del producto es requerido.");
      return errors;
    },
    profitShowErrors() {
      const errors = [];
      if (!this.$v.profitShow.$dirty) return errors;
      !this.$v.profitShow.required &&
        errors.push("La ganancia del producto es requerida.");
      return errors;
    },
    stockErrors() {
      const errors = [];
      if (!this.$v.stock.$dirty) return errors;
      !this.$v.stock.required &&
        errors.push("La cantidad del producto es requerida.");
      return errors;
    },
  },
  data: () => ({
    items: [
      {
        text: "Inicio",
        disabled: false,
        href: "/",
      },
      {
        text: "Lista de productos",
        disabled: false,
        href: "/producto",
      },
      {
        text: "Nuevo producto",
        disabled: true,
        href: "/producto/nuevo",
      },
    ],
    select: "Q",
    itemsSelect: ["Q", "%"],
    name: "",
    code: '820200'+Math.random().toString().slice(2,9),
    price: "",
    priceSale: "",
    priceMinSale: "",
    profit: "",
    profitShow: "",
    stock: "",
    description: "",
    loading: false,
  }),
  methods: {
    parseDecimal(value) {
      return parseFloat(this.parseDecimalFixed(value));
    },
    parseDecimalFixed(value) {
      return parseFloat(value).toFixed(2);
    },
    calculateProfit() {
      let vm = this;
      vm.$v.profitShow.$touch();
      if (vm.select === "Q") {
        if (vm.profitShow === "" || vm.price === ""){ 
          vm.priceSale="";
          return;
        }else {
          vm.priceSale = vm.parseDecimal(vm.parseDecimal(vm.price) + vm.parseDecimal(vm.profitShow));
          vm.profit=vm.parseDecimal(vm.parseDecimal(vm.profitShow)/vm.parseDecimal(vm.price))*100;
        }
      } else {
        if (vm.profitShow === "" || vm.price === ""){
          vm.priceSale="";
          return;
        }else {
          vm.priceSale = vm.parseDecimal(vm.parseDecimal(vm.price) + vm.parseDecimal(vm.parseDecimal(vm.parseDecimal(vm.profitShow)/100)*vm.parseDecimal(vm.price)));
          vm.profit=vm.profitShow;
        }
      }
    },
    cancel() {
      this.$router.push({ path: `/producto` });
    },
    submit() {
      let vm = this;
      vm.$v.$touch();
      if (vm.$v.$invalid) {
        vm.$toastr.e("Revise el formulario");
      } else {
        vm.loading = true;
        axios
          .post("/api/products", {
            name: vm.name,
            price:vm.parseDecimal(vm.price) ,
            priceSale: vm.parseDecimal(vm.priceSale),
            priceMinSale: vm.parseDecimal(vm.priceMinSale),
            profit: vm.parseDecimal(vm.profit),
            code: vm.code,
            description: vm.description,
            stock: vm.parseDecimal(vm.stock)
          })
          .then(() => {
            vm.loading = false;
            vm.$toastr.s("Producto creado exitosamente");
            vm.$router.push({ path: `/producto` });
          })
          .catch((error) => {
            console.log(error);
            vm.loading = false;
          });
      }
    },
    clear() {
      this.$v.$reset();
      this.name = "";
      this.code = "";
      this.price = "";
      this.priceSale = "";
      this.priceMinSale = "";
      this.profit = "";
      this.stock = "";
      this.description = "";
    },
  },
  mounted() {},
};
</script>
