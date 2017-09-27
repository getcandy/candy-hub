<script>
    export default {
        data() {
            return {
                request: apiRequest,
                current: {}
            }
        },
        props: {
            product: {
                type: Object
            },
            variants: {
                type: Array,
                default() {
                    return [];
                }
            }
        },
        methods: {
            save() {
                console.log(1);
                this.variants.forEach(variant => {
                    this.request.send('put', '/products/variants/' + variant.id, variant)
                        .then(response => {
                            CandyEvent.$emit('notification', {
                                level: 'error'
                            });
                        }).catch(response => {
                        CandyEvent.$emit('notification', {
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
            <candy-tab name="Pricing & Variants" handle="pricing-variants_variants-added" :selected="true" dispatch="product-variants">
                <candy-edit-variants :variants="variants"></candy-edit-variants>
            </candy-tab>
            <candy-tab name="Channels" handle="channels_variants-added">
                <candy-channels></candy-channels>
            </candy-tab>
            <candy-tab name="Customer Groups" handle="customer-groups_variants-added">
                <candy-customer-groups></candy-customer-groups>
            </candy-tab>
            <candy-tab name="Discounts" handle="discounts_variants-added">
                <candy-discounts></candy-discounts>
            </candy-tab>
        </candy-tabs>
        <candy-avalability-pricing-modals></candy-avalability-pricing-modals>
    </div>
</template>