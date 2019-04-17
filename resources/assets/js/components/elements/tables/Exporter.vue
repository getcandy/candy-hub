<template>
    <div>
        <button type="button" class="btn btn-default btn-full" @click="show = true">
            <i class="fa fa-floppy-o fa-first" aria-hidden="true"></i> Export Products
        </button>
        <candy-modal title="Export" v-show="show" size="modal-md" @closed="close">
            <div slot="body">
                <p>You will be emailed a link to download the CSV export</p>
                <div class="form-group">
                    <label>Email Address</label>
                    <input class="form-control" v-model="email" type="email">
                    <template v-if="errors && errors.email">
                        <span class="text-danger" v-for="error in errors.email" :key="error">{{ error }}</span>
                    </template>
                </div>
            </div>
            <template slot="footer">
                <button type="button" class="btn btn-primary" @click="trigger">
                    <span v-if="processing">Processing</span>
                    <span v-else>Start Export</span>
                </button>
            </template>
        </candy-modal>
    </div>
</template>

<script>
    export default {
        props: {
            type: {
                type: String,
                default: 'product',
            }
        },
        data() {
            return {
                processing: false,
                show: false,
                email: null,
                errors: [],
            }
        },
        methods: {
            trigger() {
                this.processing = true;
                axios.post(`export/${this.type}`, {
                    email : this.email,
                }).then(response => {
                    this.close();
                    CandyEvent.$emit('notification', {
                        message: 'Export successfully started',
                    })
                }).catch(errors => {
                    this.errors = errors.response.data.errors;
                }).finally(() => {
                    this.processing = false;
                });
            },
            close() {
                this.show = false;
                this.errors = [];
                this.email = null;
                this.processing = false;
            }
        }
    }
</script>

<style scoped>

</style>