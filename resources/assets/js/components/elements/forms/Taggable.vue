<template>
    <div>
        <input type="text" :value="tagsString">
    </div>
</template>

<script>
    export default {
        data() {
            return {
                tags: []
            }
        },
        props: {
            value: {
                type: Array
            },
            required: {
                type: Boolean
            },
        },
        computed: {
            tagsString() {
                return this.tags.join();
            }
        },
        mounted() {
            // const $taginput = $(this.$el).find('input');
            this.value.forEach(tag => {
                this.tags.push(tag.name);
            });
            // $taginput.tagsinput();
            // $taginput.on("itemAdded", event => {
            //     this.updateValue(event.item);
            // });
        },
        methods: {
            updateValue(value) {
                this.value.push({
                    id: '',
                    name: value
                });
                this.$emit('input', this.value);
            }
        }
    }
</script>