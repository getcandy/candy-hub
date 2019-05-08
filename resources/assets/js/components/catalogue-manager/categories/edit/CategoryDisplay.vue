<template>
    <div>
        <candy-layout-picker v-model="layout" v-if="loaded"></candy-layout-picker>
    </div>
</template>

<script>
    export default {
        props: ['current', 'categoryId'],
        data() {
            return {
                layout: null,
                loaded: false
            }
        },
        mounted() {
            this.loaded = true;
            if (this.current.data && this.current.data) {
                this.layout = this.current.data.id;
            }
            Dispatcher.add('category-display', this);
        },
        methods: {
            save() {
                apiRequest.send('PUT', '/categories/' + this.categoryId + '/layouts', {}, {
                    layout_id: this.layout
                })
                .then(response => {
                    CandyEvent.$emit('notification', {
                        level: 'success',
                        message: 'Category Updated'
                    });
                }).catch(error => {
                    CandyEvent.$emit('notification', {
                        level: 'error',
                        message: error.message
                    });
                });
            }
        }
    }
</script>

<style scoped>

</style>