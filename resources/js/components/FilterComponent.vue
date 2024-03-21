<template>
  {{ total }}
   <input type="text" v-model="filters.title" placeholder="Фильтр по названию">
   <input type="text" v-model="filters.phonePersonal" placeholder="Фильтр по персональному номеру телефона">
   <input type="text" v-model="filters.address" placeholder="Фильтр по адресу">
  <thead class="thead-dark">
    <tr>
      <th scope="col">#</th>
      <th scope="col">AvitoId</th>
      <th scope="col">AvitoMyId</th>
      <th scope="col">Price</th>
      <th scope="col">Title</th>
      <th scope="col">Category</th>
      <th scope="col">address</th>
      <th scope="col">phone_personal</th>
      <th scope="col">действие</th>
      <th scope="col">статус</th>
      <th scope="col">дата старта</th>
      <th scope="col">listingFee</th>
    </tr>
  </thead>
  <tr v-for="product in filteredProducts" :key="product.id">
    
    <td class="col" scope="row">{{ product.id }}</td>
    <td class="col" scope="row">{{ product.avito_id }}</td>
    <td class="col" scope="row">{{ product.my_id }}</td>
    <td class="col" scope="row">{{ product.title }}</td>
    <td class="col" scope="row">{{ product.category_name }}</td>
    <td class="col" scope="row">{{ product.address_street }}</td>
    <td class="col" scope="row">{{ product.phone_personal }}</td>
  </tr>
</template>

<script>
export default {
  data() {
    return {
      filters: {
        title: '',
        phonePersonal: '',
        address: '',
      },
      products: [],
    };
  },
  created() {
    this.getProducts();
  },
  computed: {
    filteredProducts() {
      return this.products.filter(product => {
        const titleMatch = product.title.toLowerCase().includes(this.filters.title.toLowerCase());
        const phoneMatch = product.phone_personal.includes(this.filters.phonePersonal);
        const addressMatch = product.address_street.toLowerCase().includes(this.filters.address.toLowerCase());
        return titleMatch && phoneMatch && addressMatch;
      });
    }
  },
  methods: {
    getProducts(page = 1) {
      axios.get('/api/products',{
        params: {
          page,
          perPage: this.perPage
        }
      })
        .then(response => {
          this.products = response.data.data;
          this.total = this.products.length;
        })
        .catch(error => {
          console.log(error);
        });
    }
  }
};
</script>