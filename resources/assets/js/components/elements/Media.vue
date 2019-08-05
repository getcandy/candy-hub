<script>
    import Dropzone from 'vue2-dropzone'
    export default {
        data() {
            return {
                request: apiRequest,
                deleteModalOpen: false,
                assetToDelete: {},
                filter: '',
                processingAssetUrl: false,
                failedUploads: [],
                assetUrlType: 'external',
                defaultTags: [],
                urlUpload: {
                    type: 'youtube',
                    url: ''
                },
                sortableOptions: {
                    onEnd: this.reorder,
                    filter: '.disabled',
                    handle: '.handle',
                    animation: 150
                },
                mimeTypes: [
                    {label: 'YouTube', value: 'youtube'},
                    {label: 'Vimeo', value: 'vimeo'},
                    {label: 'URL', value: 'external'},
                ],
                urlUploadModalOpen: false,
                assets: [],
                dzOptions: {
                    headers: {
                        'X-CSRF-TOKEN': window.Laravel.csrfToken,
                        'X-CANDY-HUB' : true,
                    }
                }
            }
        },
        mounted() {
            this.parent.assets.data.forEach(asset => {
                if (asset.tags.data) {
                    asset.tags = asset.tags.data;
                    delete asset.tags.data;
                } else {
                    asset.tags = [];
                }
                this.assets.push(asset);
            });
            this.urlUpload.type = this.mimeTypes[0].value;

            CandyEvent.$on('variant_asset_uploaded', event => {
                this.assets.push(event.asset);
            });

            Dispatcher.add('save-media', this);

            apiRequest.send('GET', '/tags').then(response => {
                response.data.forEach(tag => {
                    this.defaultTags.push(tag);
                });
            });
        },
        computed: {
            dropzoneUrl() {
                return '/api/v1/assets';
            }
        },
        props: {
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
        methods: {
            save() {
                this.request.send('put', '/assets', {'assets' : this.assets})
                    .then(response => {
                        CandyEvent.$emit('notification', {
                            level: 'success'
                        });
                    });
            },
            getThumbnail(asset) {
                if (asset.thumbnail) {
                    return asset.thumbnail;
                }
                if (!asset.transforms || !asset.transforms.data.length) {
                    return false;
                }
                return _.first(asset.transforms.data).url;
            },
            uploadUrlMedia() {
                this.processingAssetUrl = true;
                this.request.send('post', 'assets', {
                    'url': this.urlUpload.url,
                    'parent_id' : this.parent.id,
                    'parent' : this.assetable,
                    'mime_type': this.urlUpload.type
                }).then(response => {
                    this.processingAssetUrl = false;
                    this.assets.push(response.data);
                    CandyEvent.$emit('media_asset_uploaded', {
                        asset: response.data
                    });
                    this.urlUpload = {};
                    this.urlUploadModalOpen = false;
                }).catch(response => {
                    this.processingAssetUrl = false;
                });
            },
            deleteAsset(event) {
                apiRequest.send('delete', '/assets/' + this.assetToDelete.id)
                    .then(response => {
                        CandyEvent.$emit('notification', {
                            level: 'success'
                        });
                        CandyEvent.$emit('asset_deleted', {
                            asset: this.assetToDelete,
                            index: this.deletedIndex
                        });
                        this.assets.splice(this.deletedIndex, 1);
                        this.assetToDelete = {};
                        this.deletedIndex = null;
                        this.deleteModalOpen = false;
                    });
            },
            reorder ({oldIndex, newIndex}) {
                const movedItem = this.assets.splice(oldIndex, 1)[0];
                this.assets.splice(newIndex, 0, movedItem);
                let pos = 1;
                this.save();
                this.assets.forEach(asset => {
                    asset.position = pos;
                    pos++;
                });
            },
            setPrimary(newPrimary) {
                this.assets.forEach(asset => {
                    if (asset.id == newPrimary.id) {
                        asset.primary = 1;
                    } else {
                        asset.primary = 0;
                    }
                });
                this.save();
            },
            getUrl(asset) {
                if (asset.kind == 'youtube') {
                    return 'https://youtube.com/embed/' + asset.url;
                } else if (asset.kind == 'vimeo') {
                    return "https://player.vimeo.com/video/" + asset.url;
                }
                return asset.url;
            },
            /**
             * Gets filtered results for the assets
             * @param  {string} type
             * @return {Object}
             */
            getFilteredResults(type) {
                if (type) {
                    return this.assets.filter(asset => {
                        if (type == 'images') {
                            return asset.kind == 'image';
                        } else if (type == 'videos') {
                            return asset.external && asset.kind != 'image';
                        } else {
                            return asset.kind != 'image' && !asset.external;
                        }
                    });
                }
                return this.assets;
            },
            /**
             * Shows the delete modal for an asset
             * @param  int index
             * @return void
             */
            showDeleteModal(index) {
                this.deletedIndex = index;
                this.assetToDelete  = this.assets[index];
                this.deleteModalOpen = true;
            },
            openUrlModal() {
                this.urlUploadModalOpen = true;
            },
            closeUrlModal() {
                this.urlUploadModalOpen = false;
            },
            closeDeleteModal() {
                this.deleteModalOpen = false;
            },
            getIcon(type) {
                return '/candy-hub/icons/file-types/' + type + '.svg';
            },
            detectAssetUrlType() {
                // First clear any errors
                this.request.clearError('url')

                let value = this.urlUpload.url;

                if (value.match(/youtube\.com/)) {
                    this.urlUpload.type = 'youtube';
                } else if (value.match(/vimeo\.com/)) {
                    this.urlUpload.type = 'youtube';
                } else {
                    this.urlUpload.type = 'external';
                }

                // Refresh selectpicker
                this.$refs.urlTypeDropdown.refresh();
            },
            /**
             * Dropzone event Methods
             */
            uploadSuccess(file, response) {
                this.$refs.mediaDropzone.removeFile(file);
                response.data.tags = response.data.tags.data;

                CandyEvent.$emit('media_asset_uploaded', {
                    asset: response.data
                });

                this.assets.push(response.data);
            },
            appendParams(file, xhr, formData) {
                formData.append('parent', this.assetable);
                formData.append('parent_id', this.parent.id);
            },
            uploadError(file, response) {
                this.$refs.mediaDropzone.removeFile(file);
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
                <div class="col-xs-12 col-md-12">
                    <h4>Media</h4>
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
                        <div class="toggle-radio">
                            <input type="radio" id="videos" value="videos" v-model="filter">
                            <label for="videos">
                                <span class="check"></span>
                                <span class="faux-label">Videos</span>
                            </label>
                        </div>
                    </div>
                    <table class="table sortable">
                        <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>Title/Alt Tag</th>
                            <th>Description</th>
                            <th width="230">Tags</th>
                            <th>File Type</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody  v-sortable="sortableOptions">
                            <tr v-for="(asset, index) in getFilteredResults(filter)" :key="asset.id">
                                <td class="handle">
                                    <svg width="13px" viewBox="0 0 13 19" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <g id="Artboard" fill="#D8D8D8">
                                                <rect id="Rectangle" x="2" y="2" width="3" height="3"></rect>
                                                <rect id="Rectangle-Copy-2" x="2" y="8" width="3" height="3"></rect>
                                                <rect id="Rectangle-Copy-4" x="2" y="14" width="3" height="3"></rect>
                                                <rect id="Rectangle-Copy-5" x="8" y="14" width="3" height="3"></rect>
                                                <rect id="Rectangle-Copy" x="8" y="2" width="3" height="3"></rect>
                                                <rect id="Rectangle-Copy-3" x="8" y="8" width="3" height="3"></rect>
                                            </g>
                                        </g>
                                    </svg>
                                </td>
                                <td>
                                    <div class="toggle-radio rounded small" v-if="getThumbnail(asset)">
                                        <input type="radio" :id="asset.id" value="true" v-model="asset.primary" @click="setPrimary(asset)">
                                        <label :for="asset.id">
                                            <span class="check"></span>
                                            <span class="faux-label">Primary</span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <a :href="getUrl(asset)" v-if="getThumbnail(asset)" data-lity>
                                        <img :src="getThumbnail(asset)" :alt="asset.title">
                                    </a>
                                    <img :src="getIcon(asset.extension)" :alt="asset.title" v-else>
                                </td>
                                <td><input v-model="asset.title" placeholder="Add a title" type="text" class="form-control"></td>
                                <td><input v-model="asset.caption" placeholder="Add a description" type="text" class="form-control"></td>
                                <td>
                                    <candy-taggable :options="defaultTags" v-model="asset.tags"></candy-taggable>
                                </td>
                                <td><span v-if="asset.extension">.{{ asset.extension }}</span><span v-else>-</span></td>
                                <td align="right">
                                    <a class="btn btn-sm btn-default btn-action" :href="asset.url" target="_blank"><i class="fa fa-download" aria-hidden="true" title="Download"></i></i>
                                    </a>
                                    <button class="btn btn-sm btn-default btn-action" @click="showDeleteModal(index)"><i class="fa fa-trash-alt" aria-hidden="true" title="Delete"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot v-if="!getFilteredResults(filter).length">
                            <tr>
                              <td colspan="2">
                                <span class="text-muted">No assets found</span>
                              </td>
                            </tr>
                        </tfoot>
                    </table>
                    <!-- File icons sourced from Flaticon, we'd need to purchase these or mention the author if we want to use them for free.-->
                </div>
            </div>
        </div>
        <div class="sub-nav media-upload">
            <button type="button" class="btn btn-primary btn-full" @click="openUrlModal">Add by URL</button>
            <candy-alert :shown="true" level="danger" v-for="(file, index) in failedUploads" :key="index">
                <strong>{{ file.filename }}</strong> <br>
                <ul class="list-unstyled">
                    <li v-for="error in file.errors">
                       {{ error }}
                    </li>
                </ul>
            </candy-alert>

            <dropzone id="variant-media-upload"
                ref="mediaDropzone"
                :url="dropzoneUrl"
                v-on:vdropzone-success="uploadSuccess"
                v-on:vdropzone-sending="appendParams"
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
        <candy-modal title="Add media by URL" v-show="urlUploadModalOpen" @closed="closeUrlModal">
            <div slot="body">
                <div class="row">
                    <div class="col-xs-12 col-sm-3">
                        <div class="form-group">
                            <label>Type</label>
                            <candy-select ref="urlTypeDropdown" :options="mimeTypes" v-model="urlUpload.type"></candy-select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-9">
                        <div class="form-group">
                            <label for="urlUpload">Enter the URL to the asset.</label>
                            <input type="text" id="urlUpload" class="form-control" v-model="urlUpload.url" @blur="detectAssetUrlType()">
                        </div>
                        <span class="text-danger" v-if="request.getError('url')" v-text="request.getError('url')"></span>
                    </div>
                </div>
            </div>
            <template slot="footer">
                <button type="button" class="btn btn-primary" @click="uploadUrlMedia" :disabled="processingAssetUrl">
                    <template v-if="!processingAssetUrl">Add media</template>
                    <template v-else>Processing</template>
                </button>
            </template>
        </candy-modal>
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