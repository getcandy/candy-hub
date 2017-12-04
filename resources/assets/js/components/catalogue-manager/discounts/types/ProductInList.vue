<script>
    export default {
        data() {
            return {
                products: [],
                loaded: false
            }
        },
        props: {
            criteria: {
                type: Object
            }
        },
        computed: {
            selected() {
                return _.filter(this.products, product => {
                    return _.includes(this.criteria.products, product.id);
                });
            }
        },
        mounted() { 
            console.log(this.criteria);
            apiRequest.send('GET', 'products').then(response => {
                this.products = response.data;
                this.loaded = true;
            });
        },
        methods: {
            remove(id) {
                this.criteria.products.splice(this.criteria.products.indexOf(id), 1);
            }
        }
    }
</script>


<template>
    <div>
        <h5>Product in list</h5>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="product in selected" :key="product.id">
                    <td>{{ product|attribute('name') }}</td>
                    <td><button class="btn btn-sm btn-default btn-action" @click="remove(product.id)"><i class="fa fa-trash-o"></i></button></td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
