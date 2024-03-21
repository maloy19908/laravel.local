<template>
  <div class="container marketing">
    <div class="row">
      <form action="#" @submit.prevent="heandleLogin">
        <input type="email" v-model="formData.email" name="email">
        <input type="password" v-model="formData.password" name="password">
        <input type="submit" value="отправить">
      </form>
      {{formData}}
  </div>
  </div>
</template>
<script>
import axios from 'axios';
export default {
    data(){
        return{
            formData:{
              email:'',
              password:'',
            },
        }
    },
    computed:{

    },
    methods:{
      heandleLogin(){
        axios.get('/sanctum/csrf-cookie').then(response => {
          axios.post('/api/login',this.formData).then(response=>{
            //console.log(response.data.token);
          })
        });
      }
    },
    created(){
      // let vm = this;
      // axios.get('/sanctum/csrf-cookie').then(response => {
      //   axios.get('api/login/')
      //   .then(function(response){
      //     return vm.formData = response.data;
      //   }).catch(err=>{
      //     console.log(err.response);
      //   })
      // });

    },
};
</script>