<script>
    export default {
        data() {
            return {
                request: apiRequest,
                current: {},
                channels: [],
                foo: '',
                productChannels: []
            }
        },
        props: ['variants', 'product'],
        watch: {
          channels: {
            handler(channels, oldVal) {
              let visibleCount = 0;
              channels.forEach(channel => {
                if (channel.visible) {
                  visibleCount++;
                }
              });
              // If there are no visible channels checked
              Event.$emit('product_visibility', !((channels.length - visibleCount) == channels.length));
            },
            deep: true
          }
        },
        mounted() {
          this.channels = this.product.channels.data;
        },
        methods: {//product.channels.data[0].visible
          save() {
            this.request.send('put', '/products/' + this.product.id, this.product).then(response => {
              Event.$emit('notification', {
                level: 'success'
              });
            }).catch(response => {
              Event.$emit('notification', {
                level: 'error',
                message: 'Missing / Invalid fields'
              });
            });

                // this.variants.forEach(variant => {
                //     this.request.send('put', '/products/variants/' + variant.id, variant)
                //     .then(response => {
                //         Event.$emit('notification', {
                //             level: 'success'
                //         });
                //     }).catch(response => {
                //         Event.$emit('notification', {
                //             level: 'error',
                //             message: 'Missing / Invalid fields'
                //         });
                //     });
                // });
          }
        }
    }
</script>
<template>
    <div>
        <candy-tabs nested="true"  v-if="product">
            <candy-tab name="Pricing & Variants" handle="pricing-variants" :selected="true">
                <candy-variants :variants="variants" :product="product"></candy-variants>
                <candy-avalability-pricing-modals></candy-avalability-pricing-modals>
            </candy-tab>
            <candy-tab name="Channels" handle="channels">
                <div class="row">
                    <div class="col-xs-12 col-md-11">
                      <h4>Channels</h4>
                      <hr>
                      <table class="table">
                      <thead>
                        <tr>
                          <th>Channel</th>
                          <th>Visible</th>
                          <th>Publish Date</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="channel in channels">
                          <td>{{ channel.name }}</td>
                          <td>
                            <div class="checkbox">
                              <input :id="'CH' + channel.id" type="checkbox" v-model="channel.visible">
                              <label :for="'CH' + channel.id"><span class="check"></span></label>
                            </div>
                          </td>
                          <td class="publish-date">
                            {{ channel.published_at.date }}
                            <candy-date :value="channel.published_at.date"></candy-date>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
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