<!--
  Product Edit
  This component is responsible for displaying the product edit page.
 -->
<script>
    export default {
        data () {
            return {
              product: [],
              attribute_groups: [],
            }
        },
        props: {
          productId: {
            type: String,
            required: true
          }
        },
        created() {
          this.loadProduct(this.productId);
        },
        methods: {
          /**
           * Decorates the data ready for the template to use
           * @param  {Object} data
           * @return
           */
          decorate (data) {
            this.attribute_groups = data.attribute_groups.data;
            this.product = data;
            this.product.attributes = this.product.attribute_data;
          },
          /**
           * Loads the product by its encoded ID
           * @param  {String} id
           */
          loadProduct (id) {
            axios.get('/api/v1/products/' + id, {
              params: {
                includes : 'family,attribute_groups,attribute_groups.attributes,layout'
              }
            }).then(response => this.decorate(response.data.data))
              .catch(error => console.log(error));
          }
        }
    }
</script>

<template>
  <candy-tabs>
    <candy-tab name="Product Details" handle="product-details" :selected="true">
      <candy-tabs nested="true">
        <template v-for="(group, index) in attribute_groups">
          <candy-tab :name="group.name" :selected="index == 0 ? true : false">
              <candy-product-details :group="group" :product="product"></candy-product-details>
          </candy-tab>
        </template>

        <div class="row">
          <div class="col-xs-12 col-md-11">
            <div class="form-inline">
              <div class="form-group">
                <label class="sr-only">Store Channels</label>
                <select class="form-control selectpicker">
                  <option data-content="<i class='fa fa-shopping-cart'></i> Storefront" selected>Store Front</option>
                  <option data-content="<i class='fa fa-shopping-bag'></i> eBay">eBay</option>
                  <option data-content="<i class='fa fa-facebook'></i> Facebook">Facebook</option>
                </select>
              </div>
              <div class="form-group">
                <label class="sr-only">Language</label>
                <select class="form-control selectpicker">
                  <option data-content="<span class='flag-icon flag-icon-gb'></span> English" selected>English</option>
                  <option data-content="<span class='flag-icon flag-icon-fr'></span> French">French</option>
                  <option data-content="<span class='flag-icon flag-icon-de'></span> German">German</option>
                </select>
              </div>
              <button class="btn btn-default">Translate</button>
            </div>
            <hr>
            <div class="form-group">
              <label>
                Name
                <em class="help-txt">Helper text, use this if a field needs some explanation or an example entry.</em>
              </label>
              <input type="text" class="form-control">
            </div>
            <div class="form-group">
              <label>Description</label>
              <textarea class="form-control" rows="8"></textarea>
            </div>
            <div class="row">
              <div class="col-xs-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label>Date</label>
                  <div class="input-group input-group-full date" data-provide="datepicker">
                    <span class="input-group-addon">
                      <i class="fa fa-calendar" aria-hidden="true"></i>
                    </span>
                    <label class="sr-only" for="search">Date</label>
                    <input type="text" class="form-control">
                  </div>
                </div>
              </div>

              <div class="col-xs-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label>Time</label>
                  <div class="input-group input-group-full">
                    <span class="input-group-addon">
                      <i class="fa fa-clock-o" aria-hidden="true"></i>
                    </span>
                    <select class="form-control selectpicker">
                      <option disabled selected value>Select a time</option>
                      <option>12:00 am</option>
                      <option>12:30 am</option>
                      <option>1:00 am</option>
                      <option>1:30 am</option>
                      <option>2:00 am</option>
                      <option>2:30 am</option>
                      <option>3:00 am</option>
                      <option>Etc.</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label>
                Numeric Field
              </label>
              <input type="number" class="form-control">
            </div>

            <div class="form-group">
              <label>Standard Dropdown</label>
              <select class="form-control selectpicker">
                <option disabled selected value>Select an Option</option>
                <option>Option 1</option>
                <option>Option 2</option>
                <option>Option 3</option>
              </select>
            </div>

            <div class="form-group">
              <label>Search Dropdown</label>
              <select class="form-control selectpicker" data-live-search="true">
                <option disabled selected value>Select an Option</option>
                <option>Option 1</option>
                <option>Option 2</option>
                <option>Option 3</option>
              </select>
            </div>

            <div class="form-group">
              <label>Multiple Select Dropdown</label>
              <select class="form-control selectpicker" multiple="">
                <option>Option 1</option>
                <option>Option 2</option>
                <option>Option 3</option>
              </select>
            </div>

            <div class="form-group toggle">
              <label>Toggle</label>
              <input type="checkbox">
            </div>

          </div>
        </div>
      </candy-tabs>
    </candy-tab>

    <candy-tab name="Media">
    </candy-tab>

    <candy-tab name="Availability &amp; Pricing" handle="product-availability">
      <h1>Availability &amp; Pricing</h1>
    </candy-tab>

    <candy-tab name="Associations">
    </candy-tab>

    <candy-tab name="Display">
    </candy-tab>

    <candy-tab name="URLS">
    </candy-tab>

  </candy-tabs>
</template>