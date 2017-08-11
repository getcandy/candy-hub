<template>
    <div>
        <multiselect
            v-model="tags"
            :multiple="true"
            :taggable="true"
            label="name"
            @tag="addTag"
            @input="updateSelected"
            :options="options"
        >
        </multiselect>
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect'
    export default {
        data() {
            return {
                options: [],
                tags: []
            }
        },
        props: {
            value: {
                type: Array
            }
        },
        mounted() {
            this.tags = this.value;
        },
        components: {
            Multiselect
        },
        methods: {
            addTag(value) {
                let newTag = {
                    id: null,
                    name: value
                };
                this.options.push(newTag);
                this.tags.push(newTag);
            },
            updateSelected(value) {
                this.$emit('input', this.tags);
            }
        }
    }
</script>

<style lang="scss">
    @import "~vue-multiselect/dist/vue-multiselect.min.css";
</style>