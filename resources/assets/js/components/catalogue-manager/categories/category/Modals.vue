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
                            <span class="text-danger" v-if="request.getError('name')" v-text="request.getError('name')"></span>
                        </div>
                        <div class="form-group">
                            <label for="slug">URL</label>
                            <input id="slug" type="text" class="form-control" v-model="category.url" @change="slugify(category.url)">
                            <span class="text-danger" v-if="request.getError('url')" v-text="request.getError('url')"></span>
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
                    url: '',
                }
            }
        },
        methods: {
            slugify: function (value) {
                this.category.url = value.slugify();
                console.log(window.modalParentID);
            },
            save() {

                let data = {
                    'attributes': {
                        'name': this.category.name,
                        'url': this.category.url,
                    },
                    'parent-id': window.modalParentID
                };

                this.request.send('post', '/categories/', data)
                    .then(response => {
                        this.resetForm();
                        this.$emit('categoryCreated', response);
                        $('#createCategoryModal').modal('toggle');
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
                    url: '',
                };
                window.modalParentID = '';
            }
        }
    }
</script>