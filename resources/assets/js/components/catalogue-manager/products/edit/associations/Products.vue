<script>
    export default {
        data() {
            return {
              request: apiRequest,
              selected: [],
              results: [],
              types: [],
              associations : [],
              addAssociationModal : false,
              toDelete : {},
              filter: '',
              showRemoval : false,
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
          product: {
            type: Object
          }
        },
        mounted() {
          this.associations = this.product.associations.data;

          this.request.send('GET', 'associations/groups').then(response => {
            this.types = response.data;
          });

          _.each(this.associations, item => {
            this.selected.push(item.association.data.id);
          });

          Dispatcher.add('product-associations', this);
        },
        methods: {
          /**
           *
           * Listing Methods
           *
           */
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
          showRemovalModal(item) {
            this.toDelete = item;
            this.showRemoval = true;
          },
          deleteAssociation() {
            let product = this.associations[this.toDelete].association.data.id;
            this.request.send('DELETE', 'products/' + this.product.id + '/associations', {'associations' : product}).then(response => {
              CandyEvent.$emit('notification', {
                level: 'success',
                message: 'Association removed'
              });
              this.associations.splice(this.toDelete, 1);
              this.toDelete = null;
              this.showRemoval = false;
            });
          },
          save() {
            // Map it out so our API can understand it.
            let relations = _.map(this.associations, item => {
              return {
                'association_id': item.association.data.id,
                'type' : item.type.data.id
              }
            });
            this.request.send('POST', 'products/' + this.product.id + '/associations', {'relations' : relations}).then(response => {
              CandyEvent.$emit('notification', {
                level: 'success'
              });
              this.addAssociationModal = false;
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
                  this.results = response.data;
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
            return this.selected.contains(product.id);
          },
          assign(product) {
            this.selected.push(product.id);
            let association = {
              type: {
                data: this.types[0]
              },
              association: {
                data : product
              }
            }
            this.associations.push(association);
          },
          detatch(product) {
            this.selected.splice(this.selected.indexOf(product.id), 1);

            let association = _.filter(this.associations, item => {
              return item.association.data.id == product.id;
            });


            this.associations.splice(this.associations.indexOf(association[0]), 1);
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
        <div class="col-xs-12 col-sm-6">
          <h4>Product Associations</h4>
        </div>
        <div class="col-xs-12 col-sm-6 text-right">
          <button type="button" class="btn btn-primary" @click="showProductAssociationModal">Add a Product</button>
        </div>
      </div>
      <!--
        Listing Table
      -->
      <table id="productAssociations" class="table">
        <thead>
          <tr>
            <th width="80"></th>
            <th>Name</th>
            <th width="20%">Type</th>
            <th></th>

            <!-- <th colspan="2">Type</th> -->
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in getAssociations(filter)">
            <td width="80">
              <img :src="productThumbnail(item.association.data)">
            </td>
            <td>{{ item.association.data|attribute('name') }}</td>
            <td>
              <select class="form-control" v-model="item.type.data.id">
                <option value>-- Please select</option>
                <option :value="item.id" v-for="item in types">{{ item.name }}</option>
              </select>
            </td>
            <!-- <td><span class="label label-primary">{{ item.type.data.name }}</span></td> -->
            <td align="right">
              <button class="btn btn-sm btn-default btn-action" @click="showRemovalModal(index)">
                <i class="fa fa-trash-alt" aria-hidden="true"></i>
              </button>
            </td>
          </tr>
        </tbody>
        <tfoot v-if="!getAssociations(filter).length">
          <tr>
            <td colspan="2">
              <span class="text-muted">No products associated</span>
            </td>
          </tr>
        </tfoot>
      </table>

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
                            <span><i class="fa fa-sync fa-spin fa-3x fa-fw"></i></span> <strong>Loading</strong>
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
            <button class="btn btn-primary" @click="save()">Associate products</button>
        </template>
      </candy-modal>

      <!--
        Delete Association
      -->
      <candy-modal title="Are you wish to remove this product?" v-show="showRemoval" @closed="showRemoval = false">
        <template slot="body">
          This action cannot be undone
        </template>
        <template slot="footer">
          <button class="btn btn-primary" @click="deleteAssociation()">Confirm removal</button>
        </template>
      </candy-modal>
    </div>
  </div>
</template>