<script>
    export default {
        data() {
            return {
              request: apiRequest,
              addAssociationModal: false,
              loading: false,
              keywords: '',
              products: null
            }
        },
        props: {
          product: {
            type: Object
          }
        },
        methods: {
          getResults(keywords) {
            let results = this.request.send('GET', 'search/internal', {}, {keywords: keywords}).then(response => {
              this.products = response.data.filter(entity => {
                if (entity.id != this.product.id) {
                  return entity;
                }
              })
              this.loading = false;
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
          <input type="radio" name="products" id="showAll" value="showAll" checked="checked">
          <label for="showAll">
            <span class="check"></span>
            <span class="faux-label">Show All</span>
          </label>
        </div>
        <div class="toggle-radio">
          <input type="radio" name="products" id="upsells" value="upsells">
          <label for="upsells">
            <span class="check"></span>
            <span class="faux-label">Upsells</span>
          </label>
        </div>
        <div class="toggle-radio">
          <input type="radio" name="products" id="crossSells" value="crossSells">
          <label for="crossSells">
            <span class="check"></span>
            <span class="faux-label">Cross-sells</span>
          </label>
        </div>
        <div class="toggle-radio">
          <input type="radio" name="products" id="alternate" value="alternate">
          <label for="alternate">
            <span class="check"></span>
            <span class="faux-label">Alternate</span>
          </label>
        </div>
      </div>

      <table id="productAssociations" class="table">
        <thead>
          <tr>
            <th width="80"></th>
            <th>Name</th>
            <th>URL</th>
            <th colspan="2">Type</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="product in products">
            <td width="80"><img src="/images/placeholder/product.jpg" alt="Aquacomb"></td>
            <td>AquaComb</td>
            <td><input type="text" class="form-control" value="/aquacomb/" disabled></td>
            <td><span class="label label-primary">Upsell</span></td>
            <td align="right"><button class="btn btn-sm btn-default btn-action" data-toggle="modal" data-target="#removeProduct"><i class="fa fa-trash-o" aria-hidden="true"></i></button></td>
          </tr>
        </tbody>
      </table>
    </div>
    <candy-modal title="Add product associations" v-show="addAssociationModal" @closed="addAssociationModal = false">
        <div slot="body">
          <div class="form-group">
            <label class="sr-only">Search</label>
            <input type="text" class="form-control search" placeholder="Search Products" v-on:input="updateKeywords">
          </div>
          <hr>
          <p><em>Work in progress, need to think about how to select as Upsell, Cross-sell and Alternate Products</em></p>
          <div class="custom-radio-group">
            <span class="group-label">Selection Type:</span>
              <div class="toggle-radio">
                <input type="radio" name="products" id="selectUpsell" value="selectUpsell" checked="checked">
                <label for="selectUpsell">
                  <span class="check"></span>
                  <span class="faux-label">Upsell</span>
                </label>
              </div>
              <div class="toggle-radio">
                <input type="radio" name="products" id="selectCrossSell" value="selectCrossSell">
                <label for="selectCrossSell">
                  <span class="check"></span>
                  <span class="faux-label">Cross-sell</span>
                </label>
              </div>
              <div class="toggle-radio">
                <input type="radio" name="products" id="selectAlternate" value="selectAlternate">
                <label for="selectAlternate">
                  <span class="check"></span>
                  <span class="faux-label">Alternate</span>
                </label>
              </div>
            </div>
            <table class="table">
              <thead>
                <tr>
                  <th> </th>
                  <th>Name</th>
                  <th>SKU</th>
                  <th>URL</th>
                  <th>Categories</th>
                  <th>Collections</th>
                  <th></th>
                </tr>
              </thead>
              <tbody class="list">
                <tr v-for="product in products">
                  <td width="50"><img :src="productThumbnail(product)" alt="Aquacomb" class="img-sm"></td>
                  <td class="name">{{ product.attribute_data.name.ecommerce.gb }}</td>
                  <td class="sku">SKU0000001</td>
                  <td><input type="text" class="form-control" value="/aquacomb/" disabled></td>
                  <td class="category">Cat 1, Cat 2</td>
                  <td class="collection">Col A, Col B</td>
                  <td align="right">
                    <div class="checkbox">
                      <input :id="product.id" type="checkbox">
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
            <button class="btn btn-primary" @click="saveUrl()">Associate products</button>
        </template>
    </candy-modal>
  </div>
</template>