<template>
    <div>
        <button class="variant-option-img" @click="changeImage = true">
            <figure>
                <img :src="thumbnail" :alt="current.id" class="placeholder">
            </figure>
            <span class="change-img">
                <span v-if="hasThumbnail">Change image</span>
                <span v-else>Choose image</span>
            </span>
        </button>

        <candy-modal title="Change variant image" v-show="changeImage" @closed="changeImage = false">
            <div slot="body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="media-upload">
                            <dropzone id="media-upload"
                                        ref="variantMediaDropzone"
                                        :url="dropzoneUrl"
                                        v-on:vdropzone-success="uploadSuccess"
                                        v-bind:dropzone-options="dzOptions"
                                        v-bind:use-custom-dropzone-options="true"
                                        v-on:vdropzone-error="uploadError"
                                        :maxFileSizeInMB="50"
                            >
                                <div class="dz-default dz-message media-box">
                                    <i class="fa fa-upload icon" aria-hidden="true"></i>
                                    <p>Drop files here or click to upload</p>
                                </div>
                            </dropzone>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="media-thumbs">
                            <div class="row">
                                <div class="col-md-3" v-for="(asset, index) in assets" :key="asset.id">
                                     <label class="thumbnail-select"
                                            :class="{'selected': asset.id == current.thumbnail && current.thumbnail.data.id}">
                                        {{ getThumbnail(asset, true) }} --
                                        <img :src="asset.thumbnail" :alt="asset.title"
                                                v-if="getThumbnail(asset, true)" width="100px">
                                        <img :src="getIcon(asset.extension)"
                                                :alt="asset.title" v-else width="100px">
                                        <input type="radio" :id="asset.id" :value="asset.id"
                                                @click="setImage(asset)">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </candy-modal>
    </div>
</template>

<script>
    import Dropzone from 'vue2-dropzone'
    export default {
        props: ['current', 'product', 'assets'],
        components: {
            Dropzone
        },
        computed: {
            hasThumbnail() {
                return this.current.image.length;
            },
            dropzoneUrl() {
                return '/api/v1/products/' + this.product + '/assets';
            },
            thumbnail() {
                if (!this.current.image) {
                    return '/candy-hub/images/placeholder/no-image.svg';
                }
                let asset = _.first(this.current.image.transforms.data);
                return asset.url;
            },
        },
        data() {
            return {
                changeImage: false,
                dzOptions: {
                    headers: {
                        'X-CSRF-TOKEN': window.Laravel.csrfToken
                    }
                },
            }
        },
        methods: {
            getIcon(type) {
                return '/candy-hub/icons/file-types/' + type + '.svg';
            },
            /***
             * Dropzone event Methods
             */
            uploadSuccess(file, response) {
                this.$refs.variantMediaDropzone.removeFile(file);
                this.assets.push(response.data);
                CandyEvent.$emit('variant_asset_uploaded', {
                    asset: response.data
                });
                this.current.thumbnail = {};
                this.$set(this.current.thumbnail, 'data', response.data);
                this.save();
            },
            uploadError(file, response) {
                this.$refs.variantMediaDropzone.removeFile(file);
                this.failedUploads.push({
                    filename: file.name,
                    errors: response.file ? response.file : [response]
                });
            },
        }
    }
</script>

<style scoped>

</style>