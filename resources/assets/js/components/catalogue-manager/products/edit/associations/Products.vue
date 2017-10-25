<script>
    export default {
        data() {
            return {
              request: apiRequest,
              addAssociationModal: false,
              loading: false,
              keywords: '',
              products: null,
              groups: [],
              groupFilter: '',
              loaded: {},
              associations: []
            }
        },
        props: {
          product: {
            type: Object
          }
        },
        mounted() {
          this.groups = this.request.send('GET', 'associations/groups').then(response => {
            this.groups = response.data;
          });
          this.associations = this.product.associations.data;
        },
        methods: {
          getResults(keywords) {
            let results = this.request.send('GET', 'products', {}, {includes: 'variants', keywords: keywords}).then(response => {
              this.products = response.data.filter(entity => {
                if (entity.id != this.product.id) {

                  this.$set(this.loaded, entity.id, {
                    association_id: entity.id,
                    selected: false,
                    type: _.first(this.groups).handle
                  });

                  return entity;
                }
              });
              this.loading = false;
            });
          },
          getAssociations(type) {
            if (type) {
              return this.associations.filter(item => {
                if (type == item.type.data.handle) {
                  return true;
                } else {
                  return false;
                }
              });
            }
            return this.associations;
          },
          saveAssociations() {
            let selected = _.filter(this.loaded, item => { return item.selected});
            this.request.send('POST', 'products/' + this.product.id + '/associations', {'relations' : selected}).then(response => {
              CandyEvent.$emit('notification', {
                level: 'success'
              });
              this.addAssociationModal = false;
              this.loaded = {};
              this.products = [];
            });
          },
          productThumbnail(product) {
              if (product.thumbnail) {
                  return product.thumbnail.data.thumbnail;
              }
              return '/images/placeholder/no-image.svg';
          },
          showProductAssociationModal() {
            this.addAssociationModal = true;;
          },
          updateKeywords: _.debounce(function (e) {
            this.products = null;
            this.loading = true;
            this.getResults(e.target.value);
          }, 500)
        }
    }
</script>
<template>
  <div class="row">
    <div class="col-xs-12 col-md-11">
      <div class="row">
        <div class="col-xs-12 col-sm-6">
          <h4>Product Associations</h4>
        </div>
        <div class="col-xs-12 col-sm-6 text-right">
          <button type="button" class="btn btn-primary" @click="showProductAssociationModal">Add a Product</button>
        </div>
      </div>
      <hr>
      <div class="custom-radio-group">
        <span class="group-label">Toggle Product Association:</span>
        <div class="toggle-radio">
          <input type="radio" name="products" id="showAll" value="" v-model="groupFilter">
          <label for="showAll">
            <span class="check"></span>
            <span class="faux-label">Show All</span>
          </label>
        </div>
        <div class="toggle-radio" v-for="group in groups" :key="group.handle">
          <input type="radio" :id="group.handle" :value="group.handle" v-model="groupFilter">
          <label :for="group.handle">
            <span class="check"></span>
            <span class="faux-label">{{ group.name }}</span>
          </label>
        </div>
      </div>
      <table id="productAssociations" class="table">
        <thead>
          <tr>
            <th width="80"></th>
            <th>Name</th>
            <th colspan="2">Type</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in getAssociations(groupFilter)">
            <td width="80">
              <img :src="productThumbnail(item.association.data)">
            </td>
            <td>{{ item.association.data|attribute('name') }}</td>
            <td><span class="label label-primary">{{ item.type.data.name }}</span></td>
            <td align="right"><button class="btn btn-sm btn-default btn-action" data-toggle="modal" data-target="#removeProduct"><i class="fa fa-trash-o" aria-hidden="true"></i></button></td>
          </tr>
        </tbody>
        <tfoot v-if="!associations.length">
          <tr>
            <td colspan="2">
              <span class="text-muted">No products associated</span>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
    <candy-modal title="Add product associations" v-show="addAssociationModal" @closed="addAssociationModal = false">
        <div slot="body">
          <div class="form-group">
            <label class="sr-only">Search</label>
            <input type="text" class="form-control search" placeholder="Search Products" v-on:input="updateKeywords">
          </div>
          <hr>
          <table class="table">
            <thead>
              <tr>
                <th> </th>
                <th>Name</th>
                <th>Type</th>
                <th></th>
              </tr>
            </thead>
            <tbody class="list">
              <tr v-for="product in products">
                <td width="50"><img :src="productThumbnail(product)" :alt="product|attribute('name')" class="img-sm"></td>
                <td class="name">{{ product|attribute('name') }}</td>
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
              </tr>
              <tr v-if="loading">
                <td colspan="25">
                  <span><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></span> <strong>Loading</strong>
                </td>
              </tr>
              <tr v-if="!loading && !products">
                <td colspan="25">
                  <div class="alert alert-info">
                    Start typing to see products
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
          <hr>
          <p><small>Need to make tbody scrollable with a fixed height<br>Should selected product names with selection type show below search area as well? With a potential to remove?</small></p>
        </div>
        <template slot="footer">
            <button class="btn btn-primary" @click="saveAssociations()">Associate products</button>
        </template>
    </candy-modal>
  </div>
</template>