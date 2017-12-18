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
        },
        methods: {
            remove(id) {
            }
        }
    }
</script>


<template>
    <div>
        <div class="form-group">
            <label for="">When the coupon equals</label>
            <input type="text" class="form-control">
        </div>
        
    </div>
</template>
