<template>
    <div>
        <multiselect
            v-model="tags"
            :multiple="true"
            :taggable="true"
            label="name"
            @tag="addTag"
            @input="updateSelected"
            :options="inputOptions"
        >
        </multiselect>
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect'
    export default {
        data() {
            return {
                inputOptions: [],
                tags: []
            }
        },
        props: {
            value: {
                type: Array
            },
            options: {
                type: Array,
                default() {
                    return [];
                }
            }
        },
        mounted() {
            this.inputOptions = this.options;
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
                this.inputOptions.push(newTag);
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