<script>
    export default {
        data() {
            return {
                request: apiRequest
            }
        },
        props: {
            product: {
                type: Object
            },
            groups: {
                type: Array,
                default() {
                    return [];
                }
            }
        },
        methods: {
            save() {
                this.request.send('put', '/products/' + this.product.id, { 'attributes' : this.product.attributes })
                    .then(response => {
                        CandyEvent.$emit('notification', {
                            level: 'success'
                        });
                    }).catch(response => {
                    CandyEvent.$emit('notification', {
                        level: 'error',
                        message: 'Missing / Invalid fields'
                    });
                });
            }
        },
        mounted() {
            CandyEvent.$emit('current-tab', this);
        }
    }
</script>
<template>
    <div>
        <candy-tabs nested="true">

            <template v-for="(group, index) in groups">
                <candy-tab :name="group.name" :selected="index == 0 ? true : false">
                    <candy-product-attributes :group="group" :product="product" :request="request"></candy-product-attributes>
                </candy-tab>
            </template>

        </candy-tabs>
    </div>
</template>