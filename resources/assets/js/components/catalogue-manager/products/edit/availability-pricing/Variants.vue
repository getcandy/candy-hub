<script>
    export default {
        data() {
            return {
              editing: false,
              options: [],
              variants: [],
              sortListOptions: {
                filter: '.disabled',
                handle: '.handle',
                animation: 150
              },
              sortTableOptions: {
                filter: '.disabled',
                onEnd: this.reorder,
                handle: '.handle',
                animation: 150
              }
            }
        },
        mounted() {
          this.options = this.product.option_data;
          this.variants = this.product.variants.data;
        },
        props: {
            product: {
                type: Object
            }
        },
        methods: {
          addOption(option, handle) {
            let value = this.$refs[handle + '_option'][0].value;

            this.$set(this.options[handle].options, value, {
              position: 3,
              values: {
                en: value
              }
            });

            this.$refs[handle + '_option'][0].value = null;
          },
          getOptionRef(handle) {
            return handle + '_option';
          },
          getHandleRef(handle) {
            return handle + '_handle';
          },
          deleteTag(handle, itemHandle) {
            this.$delete(this.options[handle].options, itemHandle);
          },
          addOptionRow(event) {
            let value = event.target.value;
            this.$set(this.options, value.slugify(), {
                options: {},
                label: {
                  en: value
                },
                position: 3
            });
            event.target.value = null;
          },
          reorder ({oldIndex, newIndex}) {

            let keys = Object.keys(this.options)[newIndex];
            console.log(keys);
            // const movedItem = this.options.splice(oldIndex, 1)[0];
            // this.options.splice(newIndex, 0, movedItem);
            // let pos = 1;
            // this.options.forEach(asset => {
            //   asset.position = pos;
            //   pos++;
            // });
          }
        },
        computed: {
        }
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
              <div class="col-md-4 text-right">
                <!-- <candy-create-variant :product="product"></candy-create-variant> -->
              </div>
            </div>
          </div>
        </div>
        <hr>
        <h4>Options</h4>
        <table class="table">
          <thead>
            <tr>
              <th ></th>
              <th>
                Label
              </th>
              <th>
                Values
              </th>
              <th></th>
            </tr>
          </thead>
          <tbody v-sortable="sortTableOptions">
            <tr v-for="(option, handle) in options">
              <td class="handle">
                <svg width="13px" viewBox="0 0 13 19" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <g id="Artboard" fill="#D8D8D8">
                            <rect id="Rectangle" x="2" y="2" width="3" height="3"></rect>
                            <rect id="Rectangle-Copy-2" x="2" y="8" width="3" height="3"></rect>
                            <rect id="Rectangle-Copy-4" x="2" y="14" width="3" height="3"></rect>
                            <rect id="Rectangle-Copy-5" x="8" y="14" width="3" height="3"></rect>
                            <rect id="Rectangle-Copy" x="8" y="2" width="3" height="3"></rect>
                            <rect id="Rectangle-Copy-3" x="8" y="8" width="3" height="3"></rect>
                        </g>
                    </g>
                </svg>
              </td>
              <td width="20%">{{ option.label.en }}</td>
              <td>
                <ul v-sortable="sortListOptions" class="sortable-tags-list">
                  <li v-for="(item, itemHandle) in option.options">
                    <span class="handle">
                      <svg width="13px" height="19px" viewBox="0 0 13 19" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                          <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                              <g id="Artboard" fill="#D8D8D8">
                                  <rect id="Rectangle" x="2" y="2" width="3" height="3"></rect>
                                  <rect id="Rectangle-Copy-2" x="2" y="8" width="3" height="3"></rect>
                                  <rect id="Rectangle-Copy-4" x="2" y="14" width="3" height="3"></rect>
                                  <rect id="Rectangle-Copy-5" x="8" y="14" width="3" height="3"></rect>
                                  <rect id="Rectangle-Copy" x="8" y="2" width="3" height="3"></rect>
                                  <rect id="Rectangle-Copy-3" x="8" y="8" width="3" height="3"></rect>
                              </g>
                          </g>
                      </svg>
                    </span>
                    <span class="tag-text">
                      {{ item.values.en }}
                    </span>
                    <button @click="deleteTag(handle, itemHandle)" class="tag-delete"><i class="fa fa-times"></i></button>
                  </li>
                </ul>
                <div class="sortable-tags-list-input">
                  <form class="option-form">
                    <input type="text" placeholder="Add value" :ref="getOptionRef(handle)">
                    <button @click.prevent="addOption(option, handle)"><i class="fa fa-plus"></i></button>
                  </form>
                </div>
              </td>
            </tr>
            <tr>
              <td colspan="25">
                <input type="text" class="form-control"  placeholder="Type new option and press enter" @keyup.enter="addOptionRow">
              </td>
            </tr>
          </tbody>
        </table>
        <h4>Variants</h4>
        <table class="table">
          <thead>
            <tr>
              <th>SKU</th>
              <th v-for="option in options">
                {{ option.label.en }}
              </th>
              <th>Price</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="variant in variants">
              <td>{{ variant.sku }}</td>
              <td v-for="(option, handle) in options">
                <input type="text" class="form-control" v-model="variant.options[handle].en" v-if="variant.options[handle]">
                <input type="text" class="form-control" v-else>
              </td>
              <td>&pound;{{ variant.price }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>
