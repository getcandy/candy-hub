<template>
    <candy-modal title="Move Category" v-show="show" size="modal-md" @closed="close">
        <div slot="body">
            <div class="form-group">
                <strong>Move</strong><br>
                {{ name }}
            </div>

            <div class="form-group">
                <select class="form-control" v-model="type">
                    <option value="before">Before</option>
                    <option value="after">After</option>
                    <option value="over">Into</option>
                </select>
            </div>
            <div class="form-group" v-if="loaded && !this.loading">
                <template v-if="hasResults">
                <select v-model="target" class="form-control" placeholder="Select a category">
                    <option v-for="category in categories" :value="category.id" :key="category.id">{{ category|attribute('name') }}</option>
                </select>
                </template>
                <template v-else>
                    No results
                </template>
            </div>
            <div class="loading" v-else-if="!loaded && this.loading">
                <i class="fa fa-sync fa-spin"></i>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" v-model="term" placeholder="Start typing to search categories" @keyup="search">
            </div>
        </div>
        <template slot="footer">
            <button type="button" class="btn btn-primary" @click="move">Move Category</button>
        </template>
    </candy-modal>
</template>

<script>
    export default {
        props: ['show', 'name', 'id'],
        data() {
            return {
                type: 'after',
                categories: [],
                loaded: false,
                loading: false,
                term: null,
                hasResults: false,
                target: null,
            }
        },
        methods: {
            close() {
                this.$emit('close');
            },
            search: _.debounce(function (){
                this.categories = [];
                    this.loaded = false;
                    this.loading = true;
                    apiRequest.send('GET', 'search', [], {
                        keywords: this.term,
                        type: 'category',
                        fields: 'name',
                    })
                    .then(response => {
                        this.categories = _.filter(response.data, cat => {
                            return cat.id != this.id;
                        });
                        this.loaded = true;
                        this.loading = false;
                        this.target = this.categories[0].id;
                        this.hasResults = this.categories.length;
                    });
                }, 500
            ),
            move() {
                apiRequest.send('post', 'categories/reorder', {}, {
                    node: this.target,
                    'moved-node': this.id,
                    action: this.type,
                }).then(response => {
                    this.$emit('close');
                    CandyEvent.$emit('moved-category');
                    CandyEvent.$emit('notification', {
                        level: 'success',
                        message: 'Successfully Moved Category'
                    });
                });
            }
        }
    }
</script>

<style scoped>
    .loading {
        margin:5px 0;
        text-align: center;
    }
</style>