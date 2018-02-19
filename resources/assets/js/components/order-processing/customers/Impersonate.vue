<!--
  Product Edit
  This component is responsible for displaying the product edit page.
 -->
<script>
    const UserMixin = require('../../../mixins/UserMixin.js');
    export default {
        data() {
            return {
                impersonate: false,
                processing: false,
                target: '',
                token: null,
                errors: [],
                sent: false,
                channels: []
            }
        },
        created() {
            apiRequest.send('GET', '/channels').then(response => {
                this.channels = _.filter(response.data, channel => {
                    return channel.url;
                });
            });
        },
        methods: {
            clearErrors() {
                this.errors = [];
                this.sent = false;
            },
            process() {
                this.clearErrors();
                this.processing = true;

                if (!this.target) {
                    this.errors = ['You must choose a channel'];
                    this.processing = false;
                    return false;
                }

                console.log({
                    'customer_id' : this.customerId
                });
                apiRequest.send('POST', '/auth/impersonate', {
                    'customer_id' : this.customerId
                }).then(response => {
                    let token = response.access_token;

                    if (token) {
                        window.open(this.target + '/impersonate/' + token, "_blank");
                    } else {
                        this.errors = ['Unable to obtain access token'];
                    }

                    this.processing = false;
                    target = '';
                    errors = [];
                    this.sent = true;

                }).catch(error => {
                    let response = error.response.data;
                    this.errors.push(response.error.message);
                    this.processing = false;
                });

            }
        },
        props: {
            customerId: {
                type: String
            }
        },
        computed: {
        },
        mixins: [UserMixin]
    }
</script>

<template>
    <div>
        <button class="btn btn-default white" v-if="isAdmin" @click="impersonate = true">Impersonate</button>
        <candy-modal title="Impersonate customer" v-show="impersonate" size="modal-md" @closed="impersonate = false">
            <div slot="body">
                <div class="form-group" v-if="!processing">
                    <label>Choose the storefront to impersonate on</label>
                    <select class="form-control" @change="clearErrors"  v-model="target">
                        <option value>Select a channel</option>
                        <option :value="channel.url" v-for="channel in channels">{{ channel.name }}</option>
                    </select>
                </div>
                <span v-else>
                    <p>
                        <fa icon="spinner" spin></fa> &nbsp; Processing impersonation request, you will be redirected shortly.
                    </p>
                </span>

                <span v-if="sent" class="text-success">
                    We have opened a new tab with the logged in session.
                </span>

                <div v-if="errors.length">
                    <span class="text-danger" v-for="error in errors">{{ error }}</span>
                </div>
            </div>
            <template slot="footer">
                <button type="button" class="btn btn-primary" @click="process" :disabled="processing">Impersonate</button>
            </template>
        </candy-modal>
    </div>
</template>