<template>
		<h1>Города</h1>
		<select v-model="selected">
				<option disabled value="">Выберите один из вариантов</option>
				<option v-for="town in towns" :key=town.id :value="town.id">{{town.name}}</option>
		</select>
		<span>Выбрано: {{selected}}</span><br>
		<span>Цены с этого города: {{getPrices}}</span>
			<div v-for="price in prices">
				<div>Товар:{{price.name}}</div>
				<div>Цена:{{price.price}}</div>
			</div>
</template>
<script>
import axios from 'axios';
export default {
		data(){
				return{
						selected:null,
						towns:[],
						prices:[]
				}
		},
		computed:{
				getPrices(){
					let vm = this;
					if(this.selected!=null){
						axios.get('api/prices/search/t/'+this.selected)
						.then(function(response){
							console.log(vm.prices);
							return vm.prices = response.data;
						}); 
					}
				}
		},
		created(){
			let vm = this;
			axios.get('api/towns/')
			.then(function(response){
				return vm.towns = response.data;
			});
		},
};
</script>