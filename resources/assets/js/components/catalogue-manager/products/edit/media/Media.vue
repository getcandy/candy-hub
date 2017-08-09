<script>
    import Dropzone from 'vue2-dropzone'

    export default {
        data() {
            return {
                request: apiRequest,
                deleteModalOpen: false,
                assetToDelete: {},
                filter: '',
                failedUploads: [],
                assets: [],
                dzOptions: {
                    headers: {
                        'X-CSRF-TOKEN': window.Laravel.csrfToken
                    }
                }
            }
        },
        mounted() {
            this.product.assets.data.forEach(asset => {
                this.assets.push(asset);
            });
        },
        computed: {
            dropzoneUrl() {
                return '/api/v1/products/' + this.product.id + '/assets';
            }
        },
        props: {
            product: {
                type: Object
            },
            token: {
                type: String,
                default: Laravel.csrfToken
            }
        },
        methods: {
            save() {
                this.request.send('put', '/assets', {'assets' : this.assets})
                    .then(response => {
                        CandyEvent.$emit('notification', {
                            level: 'success'
                        });
                    });
            },
            deleteAsset(event) {
                apiRequest.send('delete', '/assets/' + this.assetToDelete.id)
                    .then(response => {
                        CandyEvent.$emit('notification', {
                            level: 'success'
                        });
                        this.assets.splice(this.deletedIndex, 1);
                        this.assetToDelete = {};
                        this.deletedIndex = null;
                        this.deleteModalOpen = false;
                    });
            },
            getFilteredResults(type) {
                if (type) {
                    return this.assets.filter(asset => {
                        if (type == 'images') {
                            return asset.kind == 'image';
                        } else {
                            return asset.kind != 'image';
                        }
                    });
                }
                return this.assets;
            },
            showDeleteModal(index) {
                this.deletedIndex = index;
                this.assetToDelete  = this.assets[index];
                this.deleteModalOpen = true;
            },
            closeDeleteModal() {
                this.deleteModalOpen = false;
            },
            getIcon(type) {
                return '/icons/file-types/' + type + '.svg';
            },
            /**
             * Dropzone event Methods
             */
            uploadSuccess(file, response) {
                this.$refs.mediaDropzone.removeFile(file);
                this.assets.push(response.data);
            },
            uploadError(file, response) {
                this.$refs.mediaDropzone.removeFile(file);
                console.log(response);
                this.failedUploads.push({
                    filename: file.name,
                    errors: response.file ? response.file : [response]
                });
            }
        },
        components: {
            Dropzone
        }
    }
</script>

<template>
    <div class="sub-panel">
        <div class="sub-content section block">
            <div class="row">
                <div class="col-xs-12 col-md-11">
                    <h4>Media</h4>
                    <hr>

                    <div class="custom-radio-group">
                        <span class="group-label">Toggle Media:</span>
                        <div class="toggle-radio">
                            <input type="radio" id="allMedia" value="" checked="checked" v-model="filter">
                            <label for="allMedia">
                                <span class="check"></span>
                                <span class="faux-label">All Media</span>
                            </label>
                        </div>
                        <div class="toggle-radio">
                            <input type="radio" id="images" value="images" v-model="filter">
                            <label for="images">
                                <span class="check"></span>
                                <span class="faux-label">Images</span>
                            </label>
                        </div>
                        <div class="toggle-radio">
                            <input type="radio" id="files" value="files" v-model="filter">
                            <label for="files">
                                <span class="check"></span>
                                <span class="faux-label">Files</span>
                            </label>
                        </div>
                    </div>

                    <table class="table">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Title/Alt Tag</th>
                            <th>Description</th>
                            <th width="230">Tags</th>
                            <th>File Type</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(asset, index) in getFilteredResults(filter)">
                            <td>
                                <a href="/images/placeholder/product.jpg" class="fresco" v-if="asset.thumbnail">
                                    <img :src="asset.thumbnail" :alt="asset.title">
                                </a>
                                <img :src="getIcon(asset.extension)" :alt="asset.title" v-else>
                            </td>
                            <td><input v-model="asset.title" type="text" class="form-control"></td>
                            <td><input v-model="asset.caption" type="text" class="form-control"></td>
                            <td><input type="text" class="form-control" data-role="tagsinput"></td>
                            <td>.{{ asset.extension }}</td>
                            <td align="right">
                                <button class="btn btn-sm btn-default btn-action" @click="showDeleteModal(index)"><i class="fa fa-trash-o" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <!-- File icons sourced from Flaticon, we'd need to purchase these or mention the author if we want to use them for free.-->
                </div>
            </div>
        </div>
        <div class="sub-nav media-upload">
            <button type="button" class="btn btn-primary btn-full">Browse existing media</button>
            
            <candy-alert :shown="true" level="danger" v-for="(file, index) in failedUploads" :key="index">
                <strong>{{ file.filename }}</strong> <br>
                <ul class="list-unstyled">
                    <li v-for="error in file.errors">
                       {{ error }}
                    </li>
                </ul>
            </candy-alert>

            <dropzone id="media-upload"
                ref="mediaDropzone"
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
                <input type="hidden" name="_token" :value="token">
            </dropzone>

            

        </div>
        <candy-modal title="Are you wish to delete this asset?" v-show="deleteModalOpen" @closed="closeDeleteModal">
            <div slot="body">
                <p>Once deleted this action can not be undone</p>
            </div>
            <template slot="footer">
                <button type="button" class="btn btn-primary" @click="deleteAsset">Confirm Deletion</button>
            </template>
        </candy-modal>
    </div>
</template>