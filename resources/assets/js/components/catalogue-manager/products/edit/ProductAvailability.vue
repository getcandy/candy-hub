<script>
    export default {
        data() {
            return {
                request: apiRequest,
                current: {},
                channels: []
            }
        },
        props: ['variants', 'product'],
        mounted() {
            this.loadChannels();
        },
        methods: {//product.channels.data[0].visible
            loadChannels() {
                this.request.send('get', '/channels').then(response => {
                    this.channels = response.data
                });
            },
            channelAvailability(name) {
              if (this.product.channels) {
                this.product.channels.data.forEach(channel => {
                  if (channel.name == name) {
                    console.log(channel.visible);
                    return channel.visible;
                  }
                });
              }
              // this.product.channels.data.forEach(channel => {
              //   if (channel.name == name) {
              //     console.log(channel);
              //     return channel.visible;
              //   }
              // });
              return true;
            },
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
        <candy-tabs nested="true"  v-if="product">
            <candy-tab name="Pricing & Variants" handle="pricing-variants" :selected="true">
                <candy-variants :variants="variants" :product="product" v-if="this.variants.length"></candy-variants>
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
                      {{ channels }}
                      <tbody>
                        <tr v-for="channel in channels">
                          <td>{{ channel.name }}</td>
                          <td>
                            <div class="checkbox">
                              {{ product.channels }}
                              <input id="storefrontVisible" type="checkbox">
                              <label for="storefrontVisible"><span class="check"></span></label>
                            </div>
                          </td>
                          <td class="publish-date"><a href="#" class="btn-pop-over"><i class="fa fa-calendar-o" aria-hidden="true"></i></a></td>
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