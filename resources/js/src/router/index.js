import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'
import ListProduct from '../views/Product/List.vue'
import NewProduct from '../views/Product/New.vue'
import EditProduct from '../views/Product/Edit.vue'
import PrintProductCode from '../views/Product/Print.vue'
import NewSale from '../views/Sale/New.vue'
Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/producto',
    name: 'Producto',
    component: ListProduct
  },
  {
    path: '/producto/nuevo',
    name: 'NuevoProducto',
    component: NewProduct
  },
  {
    path: '/producto/editar',
    name: 'EditarProducto',
    component: EditProduct
  },
  {
    path: '/venta/nuevo',
    name: 'NuevaVenta',
    component: NewSale
  },
  {
    path: '/producto/imprimir',
    name: 'ImprimirCodigo',
    component: PrintProductCode
  },
  {
    path: '/about',
    name: 'About',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "about" */ '../views/About.vue')
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
