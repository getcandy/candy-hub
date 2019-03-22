<template>
    <div>
        <input class="bootstrap-switch" type="checkbox" :value="value" :required="required" :checked="value">
    </div>
</template>

<script>
    export default {
        mounted() {
            const $bootstrapSwitch = $(this.$el).find('.bootstrap-switch');
            $bootstrapSwitch.bootstrapSwitch(this.config);

            $bootstrapSwitch.on('switchChange.bootstrapSwitch', (event, state) =>
                this.$emit('input', state)
            );
        },
        props: {
            config: {
                type: Object,
                default() {
                    return {};
                }
            },
            value: {
                type: String|Boolean
            },
            required: {
                type: Boolean
            }
        }
    }
</script>