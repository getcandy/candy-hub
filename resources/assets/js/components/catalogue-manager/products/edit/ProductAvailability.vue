<script>
    import flatPickr from 'vue-flatpickr-component';
    import 'flatpickr/dist/flatpickr.css';
    export default {
        data() {
            return {
                request: apiRequest,
                current: {},
                channels: [],
                productChannels: [],
                customerGroups: [],
                flatPickrConfig: {
                  enableTime: true,
                  minDate: new Date()
                }
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
          },
          customerGroups: {
              handler(groups, oldVal) {
                  let purchasableCount = 0;
                  groups.forEach(group => {
                      if (group.purchasable) {
                        purchasableCount++;
                      }
                  });
                  console.log(groups.length);
                  console.log(purchasableCount);
                  Event.$emit('product_purchasable', !((groups.length - purchasableCount) == groups.length));
              },
              deep: true
          }
        },
        mounted() {
          this.channels = this.product.channels.data;
          this.customerGroups = this.product.customer_groups.data;
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
          }
        },
        components: {
          flatPickr
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
                            <flat-pickr v-model="channel.published_at.date" :config="flatPickrConfig"></flat-pickr>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
            </candy-tab>
            <candy-tab name="Customer Groups" handle="customer-groups">
                <div class="row">
                    <div class="col-xs-12 col-md-11">
                        <h4>Customer Groups</h4>
                        <hr>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Group</th>
                                    <th>Visible</th>
                                    <th>Purchasable</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="group in customerGroups">
                                    <td>{{ group.name }}</td>
                                    <td>
                                        <div class="checkbox">
                                            <input :id="'CGV' + group.id" type="checkbox" v-model="group.visible">
                                            <label :for="'CGV' + group.id"><span class="check"></span></label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="checkbox">
                                            <input :id="'CGP' + group.id" type="checkbox" v-model="group.purchasable">
                                            <label :for="'CGP' + group.id"><span class="check"></span></label>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </candy-tab>
            <candy-tab name="Discounts" handle="discounts">
              <candy-discounts></candy-discounts>
            </candy-tab>
        </candy-tabs>
    </div>
</template>
