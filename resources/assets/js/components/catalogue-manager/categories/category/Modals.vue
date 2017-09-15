<template>
    <div>
        <!-- Add Category Modal -->
        <div class="modal fade" id="createCategoryModal" tabindex="-1" role="dialog" aria-labelledby="createCategoryModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="resetForm()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Create Category</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input id="name" type="text" class="form-control" v-model="category.name" @input="slugify(category.name)">
                        </div>
                        <div class="form-group">
                            <label for="slug">URL</label>
                            <input id="slug" type="text" class="form-control" v-model="category.slug" @change="slugify(category.slug)">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" @click="resetForm()">Cancel</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" @click="save()">Create Category</button>
                        <!-- On button click save product and go to product screen -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                category: {
                    name: '',
                    slug: '',
                },
                defaultLang: locale.current()
            }
        },
        methods: {
            slugify: function (value) {
                this.category.slug = value.slugify()
            },
            save() {

                let data = {
                    'attributes': {'name': {'ecommerce': {'en': this.category.name}},
                        'slug': {'ecommerce': {'en': this.category.slug}}},
                    'parent-id': window.modalParentID
                };

                apiRequest.send('post', '/categories/', data)
                    .then(response => {
                        this.resetForm();
                        this.$emit('categoryCreated', response);
                        CandyEvent.$emit('notification', {
                            level: 'success',
                            message: this.category.name +' Category was successfully created'
                        });
                    }).catch(response => {
                        CandyEvent.$emit('notification', {
                            level: 'error',
                            message: 'Missing / Invalid fields'
                        });
                    });
            },
            resetForm() {
                this.category = {
                    name: '',
                    slug: '',
                };
                window.modalParentID = '';
            }
        }
    }
</script>