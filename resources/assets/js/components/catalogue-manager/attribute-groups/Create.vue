<script>
    export default {
        data() {
            return {
                modal: false,
                group: this.base(),
                request: apiRequest,
            }
        },
        mounted() {
        },
        watch: {
            name() {
                if (!this.customHandle) {
                    this.group.handle = this.group.name[locale.current()].slugify('_');
                }
            }
        },
        computed: {
            name: {
                get() {
                    return this.group.name[locale.current()];
                },
                set(value) {
                    this.group.name[locale.current()] = value;
                }
            }
        },
        methods: {
            base() {
                return {
                    name: {
                        [locale.current()] : ''
                    },
                    handle: '',
                };
            },
            save() {
                this.request.send('post', '/attribute-groups', this.group)
                .then(response => {
                    CandyEvent.$emit('notification', {
                        level: 'success'
                    });
                    this.modal = false;
                    this.group = this.base();
                    CandyEvent.$emit('group-added', response.data);
                }).catch(response => {
                    CandyEvent.$emit('notification', {
                        level: 'error',
                        message: 'Missing / Invalid fields'
                    });
                });
            }
        }
    }
</script>

<template>
    <div>
        <button class="btn btn-success" @click="modal = true"><fa icon="plus" class="fa-first"></fa> Create Attribute Group</button>
        <candy-modal title="Create Attribute" v-show="modal" size="modal-md" @closed="modal = false">
            <div slot="body">
                <div class="form-group">
                    <label>Name</label>
                    <input class="form-control" v-model="name">
                </div>
                <div class="form-group">
                    <label>Handle</label>
                    <input class="form-control" @keyup="customHandle = true" v-model="group.handle">
                    <span class="text-danger" v-if="request.getError('handle')" v-text="request.getError('handle')"></span>
                </div>
            </div>
            <template slot="footer">
                <button type="button" class="btn btn-primary" @click="save">Create Attribute Group</button>
            </template>
        </candy-modal>
    </div>
</template>