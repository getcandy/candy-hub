<template>
    <div>

        <select multiple data-role="tagsinput" @change="updateValue($event.target.value)">

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
            $(this.$el).find('select').tagsinput();

            $(this.$el).find('select').on('afterItemAdd', function(event) {
                alert('sd');
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