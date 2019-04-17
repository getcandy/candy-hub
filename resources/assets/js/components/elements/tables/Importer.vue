<template>
    <div>
        <button type="button" class="btn btn-default btn-full" @click="show = true">
            <i class="fa fa-floppy-o fa-first" aria-hidden="true"></i> Import Products
        </button>
        <candy-modal title="Import" v-show="show" size="modal-md" @closed="close()">
            <div slot="body">
                <div class="form-group">
                    <label>CSV File</label>
                    <input type="file" @change="processFile">
                    <template v-if="errors && errors.file">
                        <span class="text-danger" v-for="error in errors.file" :key="error">{{ error }}</span><br>
                    </template>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <small class="help-txt">So we can notify you when the import has completed</small>
                    <input class="form-control" v-model="email" type="email">
                    <template v-if="errors && errors.email">
                        <span class="text-danger" v-for="error in errors.email" :key="error">{{ error }}</span>
                    </template>
                </div>
            </div>
            <template slot="footer">
                <button type="button" class="btn btn-primary" :disabled="processing" @click="trigger">
                    <span v-if="processing">Processing</span>
                    <span v-else>Start Import</span>
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
                show: false,
                email: null,
                file: null,
                processing: false,
                errors: {},
            }
        },
        methods: {
            processFile(event) {
                var files = event.target.files || event.dataTransfer.files;
                this.file = files[0];
            },
            trigger() {
                this.processing = true;
                var formData = new FormData();
                formData.append('email', this.email);
                formData.append('type', this.type);
                formData.append('file', this.file);

                apiRequest.send('POST', 'import', formData, {}, {
                    'Content-Type': 'multipart/form-data'
                }).then(response => {
                    this.close();
                    CandyEvent.$emit('notification', {
                        level: 'success',
                        message: 'Import successfully queued',
                    });
                }).catch(errors => {
                    this.errors = errors.response.data;
                }).finally(() => {
                    this.processing = false;
                });
            },
            close() {
                this.show = false;
                this.errors = [];
                this.file = null;
                this.email = null;
            }
        }
    }
</script>

<style scoped>

</style>