

<template>
    <div>
        <button class="btn btn-default white" @click="showModal = true"><i class="fa fa-trash-alt" aria-hidden="true"></i></button>
        <candy-modal title="Confirm deletion" v-show="showModal" size="modal-md" @closed="showModal = false">
            <div slot="body">
                <p>Are you sure you want to delete this {{ element }}?</p>
                <template v-if="warning">
                    <div class="alert alert-danger">
                        <p>{{ warning }}</p>
                    </div>
                </template>
            </div>
            <template slot="footer">
                <button type="button" class="btn btn-primary" @click="trash">Delete {{ element }}</button>
            </template>
        </candy-modal>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                request : apiRequest,
                showModal: false
            }
        },
        props : {
            warning: {
                type: String,
                default: null
            },
            endpoint : {
                type: String
            },
            element: {
                type: String
            },
            redirect: {
                type: String,
                default: '/'
            },
            id: {
                type: String
            }
        },
        created () {
        },
        methods : {
            trash() {
                this.request.send('delete', this.endpoint)
                .then(response => {
                    CandyEvent.$emit('notification', {
                        level: 'success'
                    });
                    window.location = this.redirect;
                }).catch(response => {
                    CandyEvent.$emit('notification', {
                        level: 'error',
                        message: response.message
                    });
                });
            }
        }
    }
</script>