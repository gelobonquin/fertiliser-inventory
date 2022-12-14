<template>
    <div class="flex justify-center mt-32 items-center">
        <div class="w-full sm:w-1/3 mx-5">
            <h1 class="text-lg font-bold text-gray-700 my-3 text-center">Inventory Valuation</h1>
            <div class="shadow rounded-md bg-white p-4">
                <form @submit.prevent="onSubmit">
                    <div class="mt-5">
                        <label class="block text-sm font-medium text-gray-700">Quantity <span class="text-red-500"> * </span></label>
                        <input v-model="form.quantity" type="text" class="mt-1 p-2 focus:ring-primary focus:ring-0 focus:border-primary block w-full shadow-sm sm:text-sm border border-gray-300 rounded-md">
                    </div>
                    <div v-if="errors.has('quantity')">
                        <div
                        v-for="(error, key) in errors.get('quantity')"
                        :key="key"
                        class="text-red-600 text-sm"
                        >
                        {{ error }}
                        </div>
                    </div>                      
                    <button type="submit" class="mt-5 w-full bg-blue-900 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:shadow-outline">Submit</button>
                </form>
            </div>  

            <div v-if="invalidResponse" class="mt-5 border-t-4 rounded-b px-4 py-3 shadow-md bg-red-100 border-red-500 text-red-500" role="alert">
                <div class="flex">
                    <div class="py-1">
                        <svg
                            class="text-red-500 fill-current h-6 w-6 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
                    <div>
                    <p class="font-bold">{{ invalidResponse.message }}</p>
                    <p class="text-sm">Stock on hand: {{ invalidResponse.remaining_quantity }}</p>
                    </div>
                </div>
            </div>
            <div v-if="successResponse" class="justify-center mt-5 rounded-md flex items-center border border-blue-500 bg-blue-100 text-white text-sm font-bold px-4 py-3" role="alert">
                <p class="text-sm text-blue-500">{{ successResponse.message }}</p>
            </div>
            
        </div>        
    </div>
</template>

<script>
import axios from 'axios';
import Error from '../Error';
export default {
    name: 'App',
    data() {
        return {
            form: {
                quantity: ''
            },
            errors: new Error(),
            successResponse: '' 
        }
    },
    methods: {
        onSubmit() {
            this.clearError();            
            this.clearSuccess();
                        
            axios.post('/api/inventory', this.form)
                .then(response => {
                    this.successResponse = response.data; 
                })
                .catch(error => {     
                    this.catchValidationError(error);                        
                });
        },
        catchValidationError(error) {
            if (error.response.status == 422) {
                this.errors.record(error.response.data.errors);
            } 
        },
        catchInvalidResponse(error) {
            if (error.response.status != 422) {
                this.invalidResponse = error.response.data;            
            }
        },
        clearError() {
            this.errors.clear();
        },
        clearSuccess() {
            this.successResponse = '';
        }
        
    }
}
</script>