
import './bootstrap';
import { createApp } from 'vue';
import { Bootstrap5Pagination }  from 'laravel-vue-pagination';
import DatatableComponent from './components/DatatableComponent.vue';

const app = createApp({});
app.component('datatable-component', DatatableComponent);
app.component('pagination', Bootstrap5Pagination)

import ExampleComponent from './components/ExampleComponent.vue';
app.component('example-component', ExampleComponent);

app.mount('#app');
