<template>
  <div class="container marketing">
    <div class="row">
      <app-loading v-if="loading"></app-loading>
        <form action="" v-for="item in prices" :key="item.id" :id="item.id" @input="changed($event,item)">
          <div class="row">
            <div class="col mb-3">
              <input class="form-control" type="text" name="name" :value="item.name">
            </div>
            <div class="col mb-3">
              <input class="form-control" type="text" name="price" :value="item.price">
            </div>
            <div class="col mb-3">
              <button class="btn btn-info" @click.prevent="editPrice(item)">изменить</button>
            </div>
            <div class="col mb-3">
              <button class="btn btn-danger" @click.prevent="delPrice(item.id)">удалить</button>
            </div>
          </div>
        </form>
        <div class="btn bg-primary text-white" @click.prevent="addPrice">Добавить Цену</div>
        <div class="container marketing">
          <div class="row">
            <div class="col-lg-4" v-for="price in prices" :key="price.id">
              <h2 class="text-info">{{price.name}}</h2>
              <div class="p-3 mb-2 bg-secondary text-white">
                <small>{{price.id}}</small>
                Цена:<strong class="text-warning">{{price.price}}</strong> Р.
              </div>
            </div>
          </div>
        </div>
    </div>
  </div>

</template>
<script>
import axios from 'axios';
export default {
    data(){
        return{
            price:null,
            input: null,
            prices:[]
        }
    },
    methods:{
      editPrice(el){
        let vm = this;
        console.log(el);
        axios.request({
          url: "api/prices/"+el.id,
          method: "PUT",
          data:el,
        }).then(function (response){
          vm.prices = response.data;
        }).catch(function(e){})
      },
      changed(e,el){
        let vm = this;
        el[e.target.name] = e.target.value;
        if(e.target.name=='price'){
          el[e.target.name] = Number(e.target.value);
        }
      },
      delPrice(id){
        let vm = this;
        axios.request({
          url: "api/prices/"+id,
          method: "DELETE",
        }).then(function (response){
          vm.prices = response.data;
        }).catch(function(e){
          
        })
        
      },
      addPrice(){
        let vm = this;
        axios.request({
          url: "api/prices",
          method: "POST",
          headers:{
            "Content-Type": "application/x-www-form-urlencoded",
          },
          data:"name=Название товара&price=0&town_id=1"
        }).then(function (response){
          //vm.prices.push(response.data);
          console.log(response.data);
        }).catch(function(e){});
      }
    },
    computed:{
      loading(){
        return this.prices === null;
      },
    },
    created(){
      let vm = this;
      axios.get('api/prices/')
      .then(function(response){
        return vm.prices = response.data;
      }).catch(err=>{
        console.log(err.response);
      })
    },
};
</script>