<script>
    export default {
        data() {
            return {
                
            }
        },
        mounted() {
        },
        props: {
            product: {
                type: Object
            }
        },
        methods: {

        },
        computed: {}
    }
</script>

<template>
    <div>
        <div class="row">
            <div class="col-xs-12 col-md-11">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-md-8">
                                <h4>Product Availability</h4>
                            </div>
                            <div class="col-md-4">
                                <candy-create-variant :product="product"></candy-create-variant>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
