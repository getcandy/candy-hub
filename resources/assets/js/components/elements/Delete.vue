

<template>
    <div>
        <button class="btn btn-default white" @click="showModal = true"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
        <candy-modal title="Confirm deletion" v-show="showModal" size="modal-md" @closed="showModal = false">
            <div slot="body">
                This will send this {{ element }} into the recycling bin.
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