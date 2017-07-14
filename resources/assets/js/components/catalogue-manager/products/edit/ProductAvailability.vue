<script>
    export default {
        data() {
            return {
                request: apiRequest,
                current: {}
            }
        },
        props: ['variants', 'product'],
        methods: {
            save() {
                this.variants.forEach(variant => {
                    this.request.send('put', '/products/variants/' + variant.id, variant)
                    .then(response => {
                        Event.$emit('notification', {
                            level: 'success'
                        });
                    }).catch(response => {
                        Event.$emit('notification', {
                            level: 'error',
                            message: 'Missing / Invalid fields'
                        });
                    });
                });
            }
        }
    }
</script>
<template>
    <div>
        <candy-tabs nested="true">
            <candy-tab name="Pricing & Variants" handle="pricing-variants" :selected="true">
                <candy-variants :variants="variants" :product="product" v-if="this.variants.length"></candy-variants>
                <candy-avalability-pricing-modals></candy-avalability-pricing-modals>
            </candy-tab>
            <candy-tab name="Inventory" handle="inventory">
              <candy-inventory></candy-inventory>
            </candy-tab>
            <candy-tab name="Shipping" handle="shipping">
              <candy-shipping></candy-shipping>
            </candy-tab>
            <candy-tab name="Channels" handle="channels">
              <candy-channels></candy-channels>
            </candy-tab>
            <candy-tab name="Customer Groups" handle="customer-groups">
              <candy-customer-groups></candy-customer-groups>
            </candy-tab>
            <candy-tab name="Discounts" handle="discounts">
              <candy-discounts></candy-discounts>
            </candy-tab>
        </candy-tabs>
    </div>
</template>