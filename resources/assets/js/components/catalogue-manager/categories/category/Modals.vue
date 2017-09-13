<template>
    <div>
        <!-- Add Category Modal -->
        <div class="modal fade" id="createCategory" tabindex="-1" role="dialog" aria-labelledby="createCategory">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Create Category</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" v-model="category.name" @input="request.clearError('url')">
                        </div>
                        <div class="form-group">
                            <label>URL</label>
                            <input type="text" class="form-control" :value="this.slugify" v-model="category.slug">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary">Create Category</button>
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
                    slug: ''
                }
            }
        },
        computed: {
            slugify: {
                get() {
                    return this.category.name.slugify();
                },
                set() {
                    return this.category.slug = this.category.name.slugify();
                }
            }
        },
    }
</script>