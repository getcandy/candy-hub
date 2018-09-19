<template>
    <div>
        <candy-select :options="options" v-if="loaded" v-model="selected" @input="update"></candy-select>
    </div>
</template>

<script>
    export default {
        props: ['value'],
        data() {
            return {
                selected: this.value,
                loaded: false,
                options: [{
                    value: null,
                    label: "None selected"
                }],
            }
        },
        mounted() {
            this.getOptions();
        },
        methods: {
            update() {
                console.log(this.selected);
                this.$emit('input', this.selected);
            },
            getOptions() {
                apiRequest.send('get', '/settings/orders', [], [])
                    .then(response => {
                        _.each(response.data.data.statuses, (label, value) => {
                            this.options.push({
                                label: label,
                                value: value
                            });
                        });
                        this.loaded = true;
                    });
            }
        }
    }
</script>

<style scoped>

</style>