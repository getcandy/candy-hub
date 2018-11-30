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
                apiRequest.send('POST', '/product-families', {
                    name: {
                        [locale.current()] : this.name,
                    }
                })
                    .then(response => {
                        this.name = null;
                        this.modal = false;
                        CandyEvent.$emit('product-family-added', response.data);
                    });
            }
        }
    }
</script>

<template>
    <div>
        <button class="btn btn-success" @click="modal = true"><fa icon="plus" class="fa-first"></fa> Create Product Family</button>
        <candy-modal title="Create Product Family" v-show="modal" size="modal-md" @closed="modal = false">
            <div slot="body">
                <div class="form-group">
                    <label>Name</label>
                    <input class="form-control" v-model="name">
                </div>
            </div>
            <template slot="footer">
                <button type="button" class="btn btn-primary" @click="save">Save</button>
            </template>
        </candy-modal>
    </div>
</template>