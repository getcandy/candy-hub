<template>
    <div class="btn-holder">
            <button class="btn btn-primary" @click="show = true">Update Status</button>
            <candy-modal title="Create Attribute" v-show="show" size="modal-lg" @closed="reset">
                <div slot="body" class="text-left">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Order Status</label>
                                <select class="form-control" v-model="status" @change="update">
                                    <option :value="handle" v-for="(status, handle) in statuses" :key="handle">{{ status.label }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="checkbox">
                                    <input type="checkbox" id="sendEmails" v-model="sendEmails" value="1">
                                    <label for="sendEmails">
                                        <span class="check"></span> &nbsp; Send notification emails
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Additional Content</label>
                                <textarea class="form-control" @keyup="loadEmailPreview" v-model="additionalCopy"></textarea>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h5>Preview Email</h5>
                            <div class="alert alert-info"  v-if="!emailTemplate">
                                Select a status with a configured mailer to see preview.
                            </div>
                            <template v-if="loadingEmail">
                                <span><i class="fa fa-sync fa-spin fa-3x fa-fw"></i></span> <strong>Loading</strong>
                            </template>
                            <iframe v-if="emailTemplate" :srcdoc="emailTemplate" ref="emailPreview">
                            </iframe>
                        </div>
                    </div>
                </div>
                <template slot="footer">
                    <button type="button" class="btn btn-primary" @click="save">
                        <template v-if="!saving">
                            Update Status
                        </template>
                        <template v-else>
                            <i class="fa fa-sync fa-spin fa-3x fa-fw"></i></span> Saving
                        </template>
                    </button>
                </template>
            </candy-modal>
    </div>
</template>

<script>
    export default {
        props: {
            value: {
                type: String,
            },
            orderId: {
                type: String,
                required: true,
            },
            statuses : {
                type: Array|Object,
                default() {
                    return [];
                }
            },
            saving: {
                default: false,
            },
            showModal: {
                default: false,
            }
        },
        watch: {
            showModal(val) {
                this.show = val;
            }
        },
        mounted() {
            this.status = this.value;
            this.show = this.showModal;
            this.loadEmailPreview();
        },
        data() {
            return {
                show: false,
                status: null,
                loadingEmail: false,
                sendEmails: true,
                emailTemplate: null,
                additionalCopy: null,
            }
        },
        methods: {
            update() {
                this.$emit('input', this.status);
                this.loadEmailPreview();
            },
            reset() {
                this.additionalCopy = null;
                this.sendEmails = true;
                this.show = false;
                this.loadEmailPreview();
            },
            loadEmailPreview: _.debounce(function (){
                    this.loadingEmail = true;
                    apiRequest.send('POST', '/orders/email-preview/' + this.status, {
                        id: this.orderId,
                        data: {
                            content: this.additionalCopy,
                        }
                    }).then(response => {
                        this.loadingEmail = false;
                        this.emailTemplate = window.atob(response.content);
                    }).catch(response => {
                        this.loadingEmail = false;
                        this.emailTemplate = null;
                    });
                }, 500
            ),
            save() {
                this.$emit('save', {
                    text: this.additionalCopy,
                    sendEmails: this.sendEmails,
                    status: this.status,
                });
            }
        }
    }
</script>

<style scoped>
    iframe {
        width:100%;
        border:1px solid #ebebeb;
        height:400px;
    }
    .btn-holder {
        display:inline-block;
    }
</style>