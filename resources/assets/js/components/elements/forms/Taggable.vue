<template>
    <div>
        {{ tags }}
        <select id="tag-input" multiple data-role="tagsinput">
            
            <option v-for="tag in tags" :value="tag">
                {{ tag }}
            </option>

        </select>

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
        mounted() {

            const $taginput = $(this.$el);
            $taginput.tagsinput();

            $taginput.on("itemAdded", event => {
                this.updateValue(event.item);
            });

        },
        methods: {
            updateValue(value) {
                this.tags.push(value);
                this.$emit('input', this.tags);
            }
        }
    }
</script>