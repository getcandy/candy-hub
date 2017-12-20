<script>
    export default {
        data() {
            return {
                request : apiRequest,
                create: false,
                zone: this.baseZone()
            }
        },
        mounted() {
        },
        methods: {
            save() {
                this.request.send('post', '/shipping/zones', this.zone)
                .then(response => {
                    CandyEvent.$emit('notification', {
                        level: 'success'
                    });
                    this.create = false;
                    this.zone = this.baseZone();
                    CandyEvent.$emit('shipping-zone-added', response.data);
                }).catch(response => {
                    CandyEvent.$emit('notification', {
                        level: 'error',
                        message: 'Missing / Invalid fields'
                    });
                });
            },
            baseZone() {
                return {
                    name: ''
                }
            }
        }
    }
</script>

<template>
    <div>
        <button class="btn btn-success" @click="create = true"><fa icon="plus" /> Add Shipping Zone</button>
        <candy-modal title="Create Shipping Zone" v-show="create" size="modal-md" @closed="create = false">
            <div slot="body">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" v-model="zone.name" @input="request.clearError('name')">
                    <span class="text-danger" v-if="request.getError('name')" v-text="request.getError('name')"></span>
                </div>
            </div>
            <template slot="footer">
                <button type="button" class="btn btn-primary" @click="save">Create zone</button>
            </template>
        </candy-modal>
    </div>
</template>
