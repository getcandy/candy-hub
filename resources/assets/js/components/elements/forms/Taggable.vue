<template>
    <div>

        <select id="tag-input" multiple data-role="tagsinput" @change="updateValue($event.target.value)">

            <option v-for="tag in value" :value="tag.name">
                {{ tag.name }}
            </option>

        </select>

    </div>
</template>

<script>
    export default {
        props: {
            value: {
                type: Array
            },
            required: {
                type: Boolean
            },
        },
        mounted() {

            const $taginput = $(this.$el).find('select');
            $taginput.tagsinput();

            $taginput.tagsinput().on("itemAddedOnInit", function(event) {
                alert('sagwgd');
                this.$emit('input', value);
            });

        },
        methods: {
            updateValue(value) {
                console.log(value);
                this.$emit('input', value);
            }
        }
    }
</script>