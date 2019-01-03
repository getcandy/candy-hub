<template>
    <div class="uploader">
        <dropzone v-if="!url" :id="id"
            ref="mediaDropzone"
            :url="uploadUrl"
            v-on:vdropzone-success="uploadSuccess"
            v-bind:dropzone-options="dzOptions"
            v-bind:use-custom-dropzone-options="true"
            v-on:vdropzone-error="uploadError"
            v-on:vdropzone-sending="errors = false"
            :maxFileSizeInMB="50"
        >
            <div class="dz-default dz-message media-box">
                <i class="fa fa-upload icon" aria-hidden="true"></i>
                <p>Drop files here or click to upload</p>
            </div>
            <input type="hidden" name="_token" :value="token">
        </dropzone>

        <span class="text-danger" v-if="error">There was an issue uploading the file</span>
        <figure v-if="url">
            <img :src="url" /><button @click="url = null"><i class="fa fa-trash"></i></button>
        </figure>
    </div>
</template>

<script>
    import Dropzone from 'vue2-dropzone'

    export default {
        components: {
            Dropzone
        },
        data() {
            return {
                request: apiRequest,
                processing: false,
                error: false,
                url: this.initial,
                dzOptions: {
                    headers: {
                        'X-CSRF-TOKEN': window.Laravel.csrfToken
                    }
                }
            }
        },
        mounted() {
            console.log(this.initial);
        },
        methods: {
            uploadSuccess(file, response) {
                this.$refs.mediaDropzone.removeFile(file);
                this.$emit('input', response.path);
                this.url = response.thumbnail_url;
            },
            uploadError(file, response) {
                this.$refs.mediaDropzone.removeFile(file);
                this.error = true;
            }
        },
        computed: {
            uploadUrl() {
                return this.assetable && this.assetable.id ? '/api/v1/assets' : '/api/v1/assets/simple';
            },
            id() {
                var text = "";
                var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

                for (var i = 0; i < 5; i++)
                    text += possible.charAt(Math.floor(Math.random() * possible.length));

                return text;
            },
        },
        props: {
            value: {

            },
            initial: {

            },
            assetable: {
                type: String
            },
            parent: {
                type: Object
            },
            token: {
                type: String,
                default: Laravel.csrfToken
            }
        },
    }
</script>

<style scoped>
    figure img {
        max-width:100%;
    }
    .uploader {
        text-align:center;
    }
</style>