<script>
    export default {
        data() {
            return {
                request: apiRequest,
                addLocaleModalOpen: false,
                addRedirectModalOpen: false,
                deleteUrlModalOpen: false,
                deletedIndex : null,
                urlToDelete:{},
                urls: [],
                newUrl: {
                    locale: locale.current(),
                    slug: ''
                }
            }
        },
        mounted() {
            this.routes.forEach(route => {
                if (!route.redirect) {
                    this.urls.push(route);
                }
            });
        },
        computed: {
            slugify: {
                get() {
                    return this.newUrl.slug.slugify();
                }
            }
        },
        props: {
            languages: {
                type: Array
            },
            product: {
                type: Object
            },
            routes: {
                type: Array
            }
        },
        methods: {
            getFlag: function(locale) {
                if (locale == 'en') {
                    locale = 'gb';
                }
                return 'flag-icon-' + locale;
            },
            save() {
                alert('Hey!');
            },
            saveUrl() {
                let data = this.newUrl;
                data.slug = data.slug.slugify();
                this.request.send('post', '/products/' + this.product.id + '/urls', data)
                    .then(response => {
                        CandyEvent.$emit('notification', {
                            level: 'success'
                        });
                        this.urls.push({
                            slug: data.slug.slugify(),
                            locale: data.locale
                        });
                        this.newUrl = {};
                        this.addLocaleModalOpen = false;
                    });
            },
            deleteUrl() {
                this.request.send('delete', '/routes/' + this.urlToDelete.id)
                    .then(response => {
                        CandyEvent.$emit('notification', {
                            level: 'success'
                        });
                        this.urls.splice(this.deletedIndex, 1);

                        if (this.urlToDelete.default == true) {
                            this.urls[0].default = true;
                        }
                        this.urlToDelete = {};
                        this.deletedIndex = null;
                        this.deleteUrlModalOpen = false;
                    });
            },
            showUrlDeleteModal(index) {
                this.deletedIndex = index;
                this.urlToDelete  = this.urls[index];
                this.deleteUrlModalOpen = true;
            },
            closeUrlDeleteModal() {
                this.request.clearError();
                this.deleteUrlModalOpen = false;
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
                        <button type="button" class="btn btn-primary" @click="addLocaleModalOpen = true">Add Locale URL</button>
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
                    <tr v-for="(url, index) in urls">
                        <td><input type="text" class="form-control" :value="url.slug"></td>
                        <td><span class="flag-icon" :class="getFlag(url.locale)"></span></td>
                        <td><em v-if="url.default">Default</em></td>
                        <td align="right">
                            <button class="btn btn-sm btn-default btn-action" @click="showUrlDeleteModal(index)">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </button>
                        </td>
                    </tr>
                    <tfoot v-if="!urls.length">
                        <tr>
                          <td colspan="2">
                            <span class="text-muted">You currently have no URLs</span>
                          </td>
                        </tr>
                      </tfoot>
                    </tbody>
                </table>
            </div>
        </div>

        <candy-modal title="Are you wish to delete this URL?" v-show="deleteUrlModalOpen" size="modal-sm" @closed="closeUrlDeleteModal">
            <div slot="body">
                <p>Once deleted this action can not be undone</p>
                <div class="form-group">
                    <input type="text" class="form-control" :value="urlToDelete.slug" disabled>
                    <span class="text-danger" v-if="request.getError()" v-text="request.getError()"></span>
                    <!-- Dynamically add corresponding URL -->
                </div>
            </div>
            <template slot="footer">
                <button type="button" class="btn btn-primary" @click="deleteUrl">Confirm Deletion</button>
            </template>
        </candy-modal>

        <candy-modal title="Add Locale URL" v-show="addLocaleModalOpen" size="modal-md" @closed="addLocaleModalOpen = false">
            <div slot="body">
                <div class="row">
                    <div class="col-xs-12 col-sm-3">
                        <div class="form-group">
                            <label>Locale</label>
                            <candy-select :options="languages" v-model="newUrl.locale"></candy-select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-9">
                        <div class="form-group">
                            <label for="redirectURL">Enter the Locale URL you wish to add to this product.</label>
                            <input type="text" id="redirectURL" class="form-control" v-model="newUrl.slug" @input="request.clearError('url')">
                            <span class="text-info" v-if="newUrl.slug && slugify != newUrl.slug">Your url will be sanitized to: <code>{{ slugify }}</code></span>
                        </div>
                        <span class="text-danger" v-if="request.getError('slug')" v-text="request.getError('slug')"></span>
                    </div>
                </div>
            </div>
            <template slot="footer">
                <button class="btn btn-primary" @click="saveUrl()">Save URL</button>
            </template>
        </candy-modal>

  </div>
</template>