<script>
    export default {
        data() {
            return {
                modal: false
            }
        },
        mounted() {
        },
        methods: {
            save() {
                // apiRequest.send('get', '/attributes', [], this.params)
                //     .then(response => {
                //         this.attributes = response.data;
                //         this.pagination = response.meta.pagination;
                //         this.loaded = true;
                //     });
            }
        }
    }
</script>

<template>
    <div>
        <button class="btn btn-success" @click="modal = true"><fa icon="plus" class="fa-first"></fa> Create Attribute Group</button>
        <candy-modal title="Create Attribute" v-show="modal" size="modal-md" @closed="modal = false">
            <div slot="body">
                <div class="form-group">
                    <label>Name</label>
                    <input class="form-control">
                </div>
                <div class="form-group">
                    <label>Handle</label>
                    <input class="form-control">
                </div>
            </div>
            <template slot="footer">
                <button type="button" class="btn btn-primary">Create Attribute Group</button>
            </template>
        </candy-modal>
    </div>
</template>