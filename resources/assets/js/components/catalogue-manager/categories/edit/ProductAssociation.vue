<script>
    export default {
        data() {
            return {
              request: apiRequest,
              selected: [],
              chosen: [],
              results: [],
              processing: false,
              addAssociationModal : false,
              loading : false,
              products: [],
              requestParams: {
                per_page: 6,
                current_page: 1,
                keywords: '',
                type: 'product'
              },
              meta: []
            }
        },
        props: {
            categoryId: {
                type: String
            },
            current: {
                type: Array
            }
        },
        mounted() {

          _.each(this.current, item => {
            this.selected.push(item.id);
          });

          Dispatcher.add('product-associations', this);
        },
        methods: {
          save() {
            this.processing = true;
            this.request.send('POST', 'categories/' + this.categoryId + '/products/attach', {'product' : this.chosen}).then(response => {
              CandyEvent.$emit('notification', {
                level: 'success'
              });
              CandyEvent.$emit('category-updated');
              this.addAssociationModal = false;
              this.processing = false;
              this.results = [];
              this.keywords = '';
            });
          },
          /**
           *
           * Modal Methods
           *
          */
          showProductAssociationModal() {
            this.addAssociationModal = true;
          },
          changePage(page) {
            this.results = [];
            this.loading = true;
            this.requestParams.current_page = page;
            this.getResults(this.keywords);
          },
          getResults(keywords) {
            this.requestParams.keywords = this.keywords;
            let results = this.request.send('GET', 'search', {}, this.requestParams).then(response => {
                  this.results = _.filter(response.data, item => {
                      return !this.selected.contains(item.id);
                  });
                  this.requestParams.total_pages = response.meta.pagination.total_pages;
                  this.meta = response.meta;
                  this.loading = false;
            });
          },
          productThumbnail(product) {
              if (product.thumbnail) {
                  return product.thumbnail.data.thumbnail;
              }
              return '/candy-hub/images/placeholder/no-image.svg';
          },
          alreadyLinked(product) {
            return this.chosen.contains(product.id);
          },
          assign(product) {
            this.chosen.push(product.id);
          },
          detatch(product) {
            this.chosen.splice(this.chosen.indexOf(product.id), 1);
          },
          updateKeywords: _.debounce(function (e) {
            this.results = null;
            this.loading = true;
            this.keywords = e.target.value;
            this.requestParams.current_page = 1;
            this.getResults(e.target.value);
          }, 500)
        }
    }
</script>
<template>
  <div class="row">
    <div class="col-xs-12">
      <!--
        Page Title and button
      -->
      <div class="row">
        <div class="col-xs-12 col-sm-12 text-right">
          <button type="button" class="btn btn-primary" @click="showProductAssociationModal">Add a Product</button>
        </div>
      </div>
      <!--
        Modal
      -->
      <candy-modal title="Add product associations" v-show="addAssociationModal" @closed="addAssociationModal = false">
        <div slot="body">
          <div class="form-group">
            <label class="sr-only">Search</label>
            <input type="text" class="form-control search" placeholder="Search Products" v-on:input="updateKeywords">
          </div>
          <hr>
          <table class="table association-table">
            <thead>
              <tr>
                <th> </th>
                <th>Name</th>
                <th></th>
              </tr>
            </thead>
            <tfoot v-if="loading" class="text-center">
                <tr>
                    <td colspan="25" style="padding:40px;">
                        <div class="loading">
                            <span><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i></span> <strong>Loading</strong>
                        </div>
                    </td>
                </tr>
            </tfoot>
            <tbody class="list">
              <tr v-for="product in results">
                <td width="10%">
                  <img :src="productThumbnail(product)" :alt="product|attribute('name')" class="img-sm">
                </td>
                <td class="name" width="40%">{{ product|attribute('name') }}</td>
                <td align="right">
                    <button @click="assign(product)" class="btn btn-sm btn-action btn-success" v-if="!alreadyLinked(product)">
                        <fa icon="plus"></fa>
                    </button>
                    <button @click="detatch(product)" class="btn btn-sm btn-default btn-action" v-else>
                        <fa icon="trash"></fa>
                    </button>
                </td>
              </tr>
              <!-- <tr v-for="product in products">
                <td width="20%"><img :src="productThumbnail(product)" :alt="product|attribute('name')" class="img-sm"></td>
                <td class="name" width="60%">{{ product|attribute('name') }}</td>
                <td>
                  <select class="form-control selectize" v-model="loaded[product.id].type">
                    <option v-for="group in groups" :value="group.handle">
                      {{ group.name }}
                    </option>
                  </select>
                </td>

                <td align="right">
                  <div class="checkbox">
                    <input :id="product.id" type="checkbox" :value="product.id" v-model="loaded[product.id].selected">
                    <label :for="product.id"><span class="check"></span></label>
                  </div>
                </td>
              </tr> -->
              <tr v-if="!loading && !products">
                <td colspan="25">
                  <div class="alert alert-info">
                    Start typing to see products
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
          <div class="text-center">
            <candy-table-paginate :pagination="requestParams" @change="changePage" v-if="!loading"></candy-table-paginate>
          </div>
        </div>
        <template slot="footer">
            <button class="btn btn-primary" @click="save()" v-if="!processing">Associate products</button>
            <button class="btn btn-primary"  v-else><fa icon="sync" spin></fa> Processing</button>
        </template>
      </candy-modal>
    </div>
  </div>
</template>