<script>
  import Dropzone from 'vue2-dropzone'
  export default {
        data() {
            return {
              request: apiRequest,
              current: {},
              currentIndex: 0,
              createVariant: false,
              editOptions: false,
              changeImage: false,
              assets: [],
              variants: [],
              dzOptions: {
                headers: {
                  'X-CSRF-TOKEN': window.Laravel.csrfToken
                }
              }
            }
        },
        props: {
            product: {
                type: Object
            }
        },
        created() {
          this.variants = this.product.variants.data;
          this.current = this.variants[0];
        },
        mounted()  {
          this.assets = this.product.assets.data;
          CandyEvent.$on('asset_deleted', event => {
            this.assets.splice(event.index, 1);
            this.variants.forEach(variant => {
              if (this.hasThumbnail(variant) && variant.thumbnail.data.id == event.asset.id) {
                variant.thumbnail = null;
              }
            });
          });
          CandyEvent.$on('media_asset_uploaded', event => {
            this.assets.push(event.asset);
          });
        },
        methods: {
            saveVariant() {
              this.request.send('put', '/products/variants/' + this.current.id, this.current)
                .then(response => {
                    CandyEvent.$emit('notification', {
                        level: 'success',
                        message: 'Changes saved'
                    });
                    this.changeImage = false;
                }).catch(response => {
                CandyEvent.$emit('notification', {
                    level: 'error',
                    message: 'Missing / Invalid fields'
                });
              });
            },
            selectVariant(index) {
                this.current = this.variants[index];
                this.currentIndex = index;
            },
            setImage(asset) {
              this.current.thumbnail = {};
              this.$set(this.current.thumbnail, 'data', asset);
              this.saveVariant();
            },
            deleteVariant(index) {
                if (confirm('Are you sure you want to delete this variant?')) {
                    apiRequest.send('delete', '/products/variants/' + this.variants[index].id)
                        .then(response => {
                            CandyEvent.$emit('notification', {
                                level: 'success'
                            });
                            this.variants.splice(index, 1);
                            this.current = this.variants[0];
                        }).catch(response => {
                        CandyEvent.$emit('notification', {
                            level: 'error',
                            message: 'An error occurred, please refresh and try again'
                        });
                    });
                }
            },
            capitalize(string) {
                return string.charAt(0).toUpperCase() + string.slice(1);
            },
            convertToCm(measurement) {
                let rate = 1;
                if (measurement.unit == 'mm') {
                    rate = 0.1;
                } else if (measurement.unit == 'in') {
                    rate = 2.54;
                }
                return measurement.value * rate;
            },
            /***
             * Dropzone event Methods
             */
            uploadSuccess(file, response) {
                this.$refs.variantMediaDropzone.removeFile(file);
                this.assets.push(response.data);
                CandyEvent.$emit('variant_asset_uploaded', {
                  asset: response.data
                });
                this.current.thumbnail = {};
                this.$set(this.current.thumbnail, 'data', response.data);
                this.saveVariant();
            },
            uploadError(file, response) {
                this.$refs.variantMediaDropzone.removeFile(file);
                this.failedUploads.push({
                    filename: file.name,
                    errors: response.file ? response.file : [response]
                });
            },
            hasThumbnail(variant) {
              if (variant.thumbnail) {
                return true;
              }
              return false;
            }
        },
        computed: {
            volume() {
                // Convert height to cm...
                let height = this.convertToCm(this.current.height),
                    width = this.convertToCm(this.current.width),
                    depth = this.convertToCm(this.current.depth),
                    cmsquared = height * width * depth;

                if (this.current.volume.unit == 'l') {
                    return cmsquared / 1000;
                }
                return cmsquared;
            },
            dropzoneUrl() {
              return '/api/v1/products/' + this.product.id + '/assets';
            }
        },
        components: {
            Dropzone
        }
    }
</script>

<template>
  <div>

      <template v-if="variants.length > 1">
      <h4>Options</h4>
      <hr>
      <div class="row">
        <div class="col-xs-12 col-md-9">
          <template v-for="(value, label, index) in current.options">
            <div class="form-group">
              <label>{{ capitalize(label) }}</label>
              <input type="text" class="form-control" v-model="current.options[label].en">
            </div>
          </template>
        </div>
        <div class="col-xs-12 col-md-3">
          
            <button class="variant-option-img" @click="changeImage = true">
              <figure>
                <img :src="current.thumbnail.data.thumbnail" alt="Placeholder" class="placeholder" v-if="hasThumbnail(current)">
                <img src="/images/placeholder/no-image.svg" alt="Placeholder" class="placeholder" v-else>
              </figure>
              <span class="change-img">
                <span v-if="hasThumbnail(current)">Change image</span>
                <span v-else>Choose image</span>
              </span>
            </button>
          
          <candy-modal title="Change variant image" v-show="changeImage" @closed="changeImage = false">
            <div slot="body">
              <div class="row">
                <div class="col-md-4">
                  <div class="media-upload">
                    <dropzone id="media-upload"
                        ref="variantMediaDropzone"
                        :url="dropzoneUrl"
                        v-on:vdropzone-success="uploadSuccess"
                        v-bind:dropzone-options="dzOptions"
                        v-bind:use-custom-dropzone-options="true"
                        v-on:vdropzone-error="uploadError"
                        :maxFileSizeInMB="50"
                    >
                        <div class="dz-default dz-message media-box">
                            <i class="fa fa-upload icon" aria-hidden="true"></i>
                            <p>Drop files here or click to upload</p>
                        </div>
                    </dropzone>
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="media-thumbs">
                    <div class="row">
                      <div class="col-md-3"  v-for="(asset, index) in assets" :key="asset.id">
                        <label class="thumbnail-select" :class="{'selected': asset.id == current.thumbnail && current.thumbnail.data.id}">
                          <img :src="asset.thumbnail" :alt="asset.title" v-if="asset.thumbnail">
                          <img :src="getIcon(asset.extension)" :alt="asset.title" v-else>
                          <input type="radio" :id="asset.id" :value="asset.id" @click="setImage(asset)" >
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </candy-modal>
        </div>
      </div>
      </template>
      <h4>Pricing</h4>
      <hr>
      <div class="row">
        <div class="col-xs-12 col-md-5">
          <div class="form-group">
            <label>Price</label>
            <div class="input-group input-group-full">
              <span class="input-group-addon">&pound;</span>
              <input type="number" class="form-control" v-model="current.price">
            </div>
          </div>
        </div>
        <div class="col-xs-12 col-md-5">
          <div class="form-group">
            <label>Compare at Price</label>
            <div class="input-group input-group-full">
              <span class="input-group-addon">&pound;</span>
              <input type="number" class="form-control">
            </div>
          </div>
        </div>
        <div class="col-xs-6 col-md-2">
          <div class="form-group">
            <label>Tax</label>
            <candy-select :options="['0%','5%','20%']"></candy-select>
          </div>
        </div>
      </div>

      <h4>Inventory</h4>
      <hr>
      <div class="row">
        <div class="col-xs-12 col-md-5">
          <div class="form-group">
            <label>Inventory Policy</label>
            <candy-select :options="['Option 1','Option 2','Option 3']"></candy-select>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-6 col-md-5">
          <div class="form-group">
            <label>SKU</label>
            <input type="text" class="form-control" v-model="current.sku">
          </div>
        </div>
        <div class="col-xs-12 col-md-5">
          <div class="form-group">
            <label>Quantity</label>
            <input type="number" class="form-control" v-model="current.inventory">
          </div>
        </div>
        <div class="col-xs-12 col-md-2">
          <div class="form-group">
            <label>Incoming</label>
            <br><a href="#" class="btn btn-lg btn-link">0</a>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12">
          <div class="form-group">
            <label for="backorder">
              <input id="backorder" type="checkbox" v-model="current.backorder">
              <span class="faux-label">Allow customers to purchase this product when it's out of stock</span>
            </label>
          </div>
        </div>
      </div>

      <h4>Shipping</h4>
      <hr>
      <div class="form-group">
        <label for="requiresShipping">
          <input id="requiresShipping" type="checkbox" v-model="current.requires_shipping">
          <span class="faux-label"> This product requires shipping</span>
        </label>
      </div>
      <div class="row">
        <div class="col-xs-12 col-md-5">
          <div class="form-group">
            <label>Fulfillment Service</label>
            <candy-select :options="['Option','Option','Option']"></candy-select>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-md-5">
          <div class="form-group">
            <label>
              Weight
              <em class="help-txt">Description on what weigth is used for.</em>
            </label>
            <div class="input-group input-group-full">
              <input type="number" class="form-control">
              <candy-select :options="['lb', 'oz', 'kg', 'g']" v-model="current.weight.unit" :addon="true"></candy-select>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <p>Fields below will show dependant on fulfillment service.</p>
      <div class="row">
        <div class="col-xs-12 col-md-5">
          <div class="form-group">
            <label>
              Height
              <em class="help-txt">Description on what height is used for.</em>
            </label>
            <div class="input-group input-group-full">
              <input type="number" class="form-control" v-model="current.height.value">
              <candy-select :options="['cm','mm', 'in']" v-model="current.height.unit" :addon="true"></candy-select>
            </div>
          </div>
          <div class="form-group">
            <label>
              Width
              <em class="help-txt">Description on what width is used for.</em>
            </label>
            <div class="input-group input-group-full">
              <input type="number" class="form-control" v-model="current.width.value">
              <candy-select :options="['cm','mm', 'in']" v-model="current.width.unit" :addon="true"></candy-select>
            </div>
          </div>
          <div class="form-group">
            <label>
              Depth
              <em class="help-txt">Description on what depth is used for.</em>
            </label>
            <div class="input-group input-group-full">
              <input type="number" class="form-control" v-model="current.depth.value">
              <candy-select :options="['cm','mm', 'in']" v-model="current.depth.unit" :addon="true"></candy-select>
            </div>
          </div>
          <div class="form-group">
            <label>
              Volume
              <em class="help-txt">Description on what volume is used for.</em>
            </label>
            <div class="input-group input-group-full">
              <input type="number" class="form-control" :value="volume">
              <candy-select :options="['l', 'ml']" v-model="current.volume.unit"></candy-select>
            </div>
          </div>
          <button class="btn btn-danger" @click="deleteVariant(currentIndex)" v-if="variants.length > 1"><i class="fa fa-trash"></i> Delete variant</button>
        </div>
      </div>
    </div>

</template>
