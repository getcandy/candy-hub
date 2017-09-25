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
                        <h4 v-if="modalData.name" class="modal-title">Create Sub Category <small>Under {{ modalData.name }}</small></h4>
                        <h4 v-else="" class="modal-title">Create Category</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input id="name" type="text" class="form-control" v-model="category.name" @input="slugify(category.name)">
                            <span class="text-danger" v-if="request.getError('attributes.name')" v-text="request.getError('attributes.name')"></span>
                        </div>
                        <div class="form-group">
                            <label for="slug">URL</label>
                            <input id="slug" type="text" class="form-control" v-model="category.slug" @change="slugify(category.slug)">
                            <span class="text-danger" v-if="request.getError('routes.slug')" v-text="request.getError('routes.slug')"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" @click="resetForm()">Cancel</button>
                        <button type="button" class="btn btn-primary" @click="save()">Create Category</button>
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
                request: apiRequest,
                category: {
                    name: '',
                    slug: '',
                },
                language: locale.current()
            }
        },
        props: {
            modalData: {
                type: Object
            }
        },
        mounted() {

        },
        methods: {
            slugify: function (value) {
                this.category.slug = value.slugify();
            },
            save() {

                let data = {
                    'attributes': {
                        'name': this.category.name,
                    },
                    'routes': {
                        'slug': this.category.slug,
                        'locale': this.language,
                        'default': 1
                    },
                    'parent-id': this.modalData.id
                };

                this.request.send('post', '/categories/create', data)
                    .then(response => {
                        this.$emit('categoryCreated', response);
                        CandyEvent.$emit('notification', {
                            level: 'success',
                            message: 'Category '+ this.category.name +' Created'
                        });
                        this.resetForm();
                        $('#createCategoryModal').modal('toggle');
                    }).catch(response => {
                        console.log(response);
                        CandyEvent.$emit('notification', {
                            level: 'error',
                            message: 'There was an error creating your category'
                        });
                    });
            },
            resetForm() {
                this.category = {
                    name: '',
                    slug: '',
                };
            }
        }
    }
</script>