<script>
    export default {
        data() {
            return {
                request: apiRequest,
                modalOpen: false,
                newRedirect: {
                    locale: 'en',
                    url: ''
                }
            }
        },
        computed: {
            slugify: {
                get() {
                    return this.newRedirect.url.slugify();
                }
            }
        },
        props: {
            product: {
                type: Object
            },
            routes: {
                type: Array
            }
        },
        methods: {
            getFlag: function(locale) {
                return 'flag-icon-' + locale;
            },
            save() {

            },
            saveRedirect() {
                let data = this.newRedirect;
                this.request.send('post', '/products/' + this.product.id + '/urls', data)
                    .then(response => {
                        this.routes.push({
                            "slug": data.url,
                            "locale": data.locale,
                            "default": false
                        });
                        this.modalOpen = false;
                    });
            }
        }
    }
</script>

<template>
  <div>
    <div class="row">
      <div class="col-xs-12 col-md-11">
        <div class="row">
          <div class="col-xs-12 col-sm-6">
            <h4>Locale URLs</h4>
          </div>
          <div class="col-xs-12 col-sm-6 text-right">
            <button type="button" class="btn btn-primary" @click="modalOpen = true">Add Locale URL</button>
          </div>
        </div>
        <hr>
        <table class="table">
          <thead>
          <tr>
            <td>URL</td>
            <td colspan="3">Locale</td>
          </tr>
          </thead>
          <tbody>
          <tr v-for="route in routes">
            <td><input type="text" class="form-control" :value="route.slug"></td>
            <td><span class="flag-icon" :class="getFlag(route.locale)"></span></td>
            <td><em v-if="route.default">Default</em></td>
            <td align="right"><button class="btn btn-sm btn-default btn-action" data-toggle="modal" data-target="#deleteURL"><i class="fa fa-trash-o" aria-hidden="true"></i></button></td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>
    <candy-modal title="Add Locale URL" v-show="modalOpen" @closed="modalOpen = false">
      <div slot="body">
        <div class="row">
          <div class="col-xs-12 col-sm-3">
            <div class="form-group">
              <label>Locale</label>
              <candy-language-select v-model="newRedirect.locale"></candy-language-select>
            </div>
          </div>
          <div class="col-xs-12 col-sm-9">
            <div class="form-group">
              <label for="redirectURL">Enter the Locale URL you wish to add to this product.</label>
              <input type="text" id="redirectURL" class="form-control" v-model="newRedirect.url" @input="request.clearError('url')">
              <span class="text-info" v-if="newRedirect.url && slugify != newRedirect.url">Your url will be sanatized to: <code>{{ slugify }}</code></span>
            </div>
            <span class="text-danger" v-if="request.getError('url')" v-text="request.getError('url')"></span>
          </div>
        </div>
      </div>
      <template slot="footer">
        <button class="btn btn-primary" @click="saveRedirect()">Save Redirect</button>
      </template>
    </candy-modal>
  </div>
</template>