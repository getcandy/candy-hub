<script>
    export default {
        data() {
            return {
                request: apiRequest,
                saveModalOpen: false,
                redirectToDelete: {},
                deleteModalOpen: false,
                newUrl: {
                    locale: 'en',
                    redirect:true,
                    description: '',
                    slug: ''
                },
                redirects: []
            }
        },
        computed: {
            slugify: {
                get() {
                    return this.newUrl.slug.slugify(true);
                },
                set() {
                    return this.newUrl.slug.slugify(true);
                }
            }
        },
        props: {
            product: {
                type: Object
            },
            routes: {
                type: Array,
                default() {
                    return [];
                }
            }
        },
        mounted() {
            this.routes.forEach(route => {
                if (route.redirect) {
                    this.redirects.push(route);
                }
            });
        },
        methods: {
            saveRedirect() {
                let data = this.newUrl;
                data.slug = data.slug.slugify(true);
                this.request.send('post', '/products/' + this.product.id + '/redirects', data)
                    .then(response => {
                        CandyEvent.$emit('notification', {
                            level: 'success'
                        });
                        this.redirects.push({
                            slug: data.slug,
                            description: data.description,
                            locale: data.locale
                        });
                        this.newUrl = {};
                        this.saveModalOpen = false;
                    });
            },
            deleteRedirect() {
                this.request.send('delete', '/routes/' + this.redirectToDelete.id)
                    .then(response => {
                        CandyEvent.$emit('notification', {
                            level: 'success'
                        });
                        this.redirects.splice(this.deletedIndex, 1);

                        this.redirectToDelete = {};
                        this.deletedIndex = null;
                        this.deleteModalOpen = false;
                    });
            },
            showDeleteModal(index) {
                this.deletedIndex = index;
                this.redirectToDelete  = this.redirects[index];
                this.deleteModalOpen = true;
            },
            closeDeleteModal() {
                this.request.clearError();
                this.deleteModalOpen = false;
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
                <h4>Redirect URLs</h4>
              </div>
              <div class="col-xs-12 col-sm-6 text-right">
                <button type="button" class="btn btn-primary" @click="saveModalOpen = true">Add Redirect</button>
              </div>
            </div>
            <hr>
            <table class="table">
              <thead>
                <tr>
                  <td width="30%">Redirect URL</td>
                  <td>Description</td>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(redirect, index) in redirects">
                  <td><input type="text" class="form-control" :value="redirect.slug"></td>
                  <td>
                    {{ redirect.description }}
                  </td>
                  <td align="right">
                      <button class="btn btn-sm btn-default btn-action"  @click="showDeleteModal(index)"><i class="fa fa-trash-alt" aria-hidden="true"></i></button>
                  </td>
                </tr>
              </tbody>
              <tfoot v-if="!redirects.length">
                <tr>
                  <td colspan="2">
                    <span class="text-muted">You currently have no redirects</span>
                  </td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
       <candy-modal title="Are you wish to delete this Route?" v-show="deleteModalOpen" @closed="closeDeleteModal">
           <div slot="body">
               <p>Once deleted this action can not be undone</p>
               <div class="form-group">
                   <input type="text" class="form-control" :value="redirectToDelete.slug" disabled>
                   <span class="text-danger" v-if="request.getError()" v-text="request.getError()"></span>
                   <!-- Dynamically add corresponding URL -->
               </div>
           </div>
           <template slot="footer">
               <button type="button" class="btn btn-primary" @click="deleteRedirect">Confirm Deletion</button>
           </template>
       </candy-modal>
       <candy-modal title="Add redirect" v-show="saveModalOpen" @closed="saveModalOpen = false">
           <div slot="body">
               <div class="form-group">
                   <label for="redirectURL">Enter the URL you wish to redirect to this product.</label>
                   <input type="text" class="form-control" v-model="newUrl.slug" @input="request.clearError('url')">
                   <span class="text-info" v-if="newUrl.slug && slugify != newUrl.slug">Your url will be sanitized to: <code>{{ slugify }}</code></span>
                   <span class="text-danger" v-if="request.getError('slug')" v-text="request.getError('slug')"></span>
               </div>
               <div class="form-group">
                   <label for="explaination">Brief description for the redirect</label>
                   <textarea id="explaination" class="form-control" v-model="newUrl.description"></textarea>
               </div>
           </div>
           <template slot="footer">
               <button class="btn btn-primary" @click="saveRedirect()">Save Redirect</button>
           </template>
       </candy-modal>
   </div>
</template>