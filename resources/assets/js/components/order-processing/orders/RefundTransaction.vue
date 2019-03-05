<template>
    <div>
        <button class="btn btn-small btn-info" @click="showModal = true">Refund</button>
        <candy-modal title="Issue Refund" v-show="showModal" size="modal-md" @closed="showModal = false">
            <div slot="body" class="text-left">
                <template v-if="max && amount > max">
                    <div class="alert alert-danger">
                        Amount cannot be more than {{ max }}
                    </div>
                </template>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Amount</label>
                            <input type="number" class="form-control" v-model="amount" :max="max">
                            <template v-if="this.errors.amount">
                                <span class="text-danger" v-for="error in this.errors.amount">{{ error }}</span> <br>
                            </template>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" id="fullRefundCheckbox">
                            <div class="checkbox">
                                <input type="checkbox" id="fullAmount" value="1" :checked="amount == initial" @change="setFull">
                                <label for="fullAmount">
                                    <span class="check"></span>
                                    Refund full amount
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Notes</label>
                            <textarea type="text" v-model="notes" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Enter <code>REFUND</code> below to continue</label>
                            <input type="text" v-model="confirmation" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <template slot="footer">
                <button @click="refund" type="button" class="btn btn-primary" :disabled="confirmation != 'REFUND' || amount > initial">
                    <template v-if="!processing">
                        Issue Refund
                    </template>
                    <template v-else>
                        <i class="fa fa-sync fa-spin text-white"></i></span> Processing
                    </template>
                </button>
            </template>
        </candy-modal>

    </div>
</template>

<script>
    export default {
        props: {
            initial: {
                type: Number,
                required: true,
            },
            max: {
                type: Number,
            },
            id: {
                type: String,
                required: true
            },
            reference: {
                type: String,
                required: true,
            }
        },
        data() {
            return {
                enabled: true,
                showModal: false,
                errors: {},
                processing: false,
                confirmation: null,
                amount: this.initial,
                notes: null,
            }
        },
        computed: {
            integer() {
                return Math.round(this.amount * 100);
            }
        },
        mounted() {
            if (this.max) {
                this.amount = this.max;
            }
        },
        methods: {
            setFull() {
                this.amount = this.initial;
            },
            refund() {
                this.processing = true;
                apiRequest.send('post', '/payments/' + this.id + '/refund', {
                    amount: this.integer,
                    notes: this.notes,
                }).then(response => {
                    this.processing = false;
                    this.showModal = false;
                    this.message = null;
                    this.confirmation = null;
                    this.amount = this.value;
                    this.$emit('refunded', response);
                    CandyEvent.$emit('log-updated');
                    CandyEvent.$emit('notification', {
                        level: 'success',
                        message: 'Refund Processed'
                    });

                }).catch(error => {
                    this.errors = error.response.data;

                    if (this.errors.error && this.errors.error.message) {
                        CandyEvent.$emit('notification', {
                            level: 'error',
                            message: this.errors.error.message
                        });
                    }
                    this.processing = false;
                });
            }
        }
    }
</script>

<style scoped>
    #fullRefundCheckbox {
        margin-top:2.25em;
    }
</style>