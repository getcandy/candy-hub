<template>
    <div>
        <select multiple>
            <option v-for="option in inputOptions" :value="option" :selected="value.contains(option)">
                {{ option }}
            </option>
        </select>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                tags: [],
                inputOptions: []
            }
        },
        props: {
            options: {
                type: Array,
                default() {
                    return [];
                }
            },
            value: {
                type: Array
            },
            required: {
                type: Boolean
            },
        },
        mounted() {
            const $taginput = $(this.$el).find('select');


            var self = this;

            $taginput.selectize({
                delimiter: ',',
                create: true,
                load() {
                    alert('hello');
                    self.inputOptions = self.value.concat(self.options);
                },
                onItemAdd(value) {
                    self.value.push(value);
                    self.updateValue();
                },
                onItemRemove(item) {
                    let index = self.value.indexOf(item);
                    self.value.splice(index,1);
                    console.log(self.value);
                    self.updateValue();
                }
            });
        },
        methods: {
            updateValue() {
                this.$emit('input', this.value);
            }
        }
    }
</script>

<style lang="scss">
    @import "~selectize/dist/css/selectize.bootstrap3.css";
</style>