<script>
    export default {
        data() {
            return {
                request : apiRequest,
                create: false,
                model: this.base(),
                families: []
            }
        },
        computed: {
            name: {
                get() {
                    return this.model.name[locale.current()];
                },
                set(value) {
                    this.model.name[locale.current()] = value;
                }
            },
            sluggedName() {
                return this.model.name[locale.current()].slugify();
            },
            url: {
                get() {
                    return this.model.url.slugify();
                }
            }
        },
        methods: {
            save() {
                this.model.url = this.model.url.slugify();
                this.request.send('post', '/categories', this.model)
                .then(response => {
                    CandyEvent.$emit('notification', {
                        level: 'success'
                    });
                    this.create = false;
                    this.model = this.base();
                    CandyEvent.$emit('category-added', response.data);
                }).catch(response => {
                    CandyEvent.$emit('notification', {
                        level: 'error',
                        message: 'Missing / Invalid fields'
                    });
                });
            },
            base() {
                return {
                    name: {
                        [locale.current()] : ''
                    },
                    description: {
                        [locale.current()] : ''
                    },
                    url: '',
                }
            }
        }
    }
</script>

<template>
    <div>
        <button class="btn btn-success" @click="create = true"><i class="fa fa-plus fa-first" aria-hidden="true"></i> Add Category</button>
        <candy-modal title="Create Product" v-show="create" size="modal-md" @closed="create = false">
            <div slot="body">
                <div class="form-group">
                    <label for="name">Category name</label>
                    <input type="text" class="form-control" id="name" v-model="name" @input="request.clearError('name')">
                    <span class="text-danger" v-if="request.getError('name')" v-text="request.getError('name')"></span>
                </div>
                <div class="form-group">
                    <label for="redirectURL">Category URL</label>
                    <input type="text" id="redirectURL" class="form-control" v-model="model.url">
                    <span class="text-info" v-if="model.url">Your url will be sanitized to: <code>{{ url }}</code></span>
                    <span class="text-danger" v-if="request.getError('url')" v-text="request.getError('url')"></span>
                </div>
            </div>
            <template slot="footer">
                <button type="button" class="btn btn-primary" @click="save">Create category</button>
            </template>
        </candy-modal>
    </div>
</template>
