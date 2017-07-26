<template>
    <div>
        <select @input="updateValue($event.target.value)" :value="value">
            <option value disabled>Please select</option>
            <option v-for="language in languages" :value="language.code" :data-content="getFlag(language.code)">
                {{ language.name }}
            </option>
        </select>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                languages: localStorage.getItem('languages')
            }
        },
        props: {
            value: {
                type: String
            }
        },
        mounted() {
            if (!this.languages) {
                this.getLanguages();
            }
            $(this.$el).on('changed.bs.select', (event, clickedIndex, newValue, oldValue) => {
                this.$emit('input', $(this.$el).find("option:selected").val());
            });
        },
        methods: {
            getFlag(code) {
                return "<span class='flag-icon flag-icon-" + code + "'></span> English";
            },
            updateValue(value) {
                this.$emit('input', value)
            },
            getLanguages() {
                apiRequest.send('get', '/languages').then(response => {
                  this.languages = response.data;
                  $(this.$el).selectpicker('render');
                }).catch(response => {
                    // Do something...
                });
            }
        }
    }
</script>