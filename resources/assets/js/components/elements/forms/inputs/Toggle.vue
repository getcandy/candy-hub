<template>
    <div>
        <input class="bootstrap-switch" type="checkbox" :value="value" :required="required" :checked="value">
    </div>
</template>

<script>
    export default {
        mounted() {
            const $bootstrapSwitch = $(this.$el).find('.bootstrap-switch');
            $bootstrapSwitch.bootstrapSwitch();

            $bootstrapSwitch.on('switchChange.bootstrapSwitch', (event, state) =>
                this.$emit('input', state)
            );
        },
        props: {
            value: {
                type: String
            },
            required: {
                type: Boolean
            }
        }
    }
</script>