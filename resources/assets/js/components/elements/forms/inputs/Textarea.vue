<template>
    <div>
        <trumbowyg class="form-control"
                  rows="8"
                  :value="value"
                  @tbw-change="updateValue($event.target.value)"
                  :required="required"
                  :config="config"
                  :placeholder="placeholder"
                  :disabled="disabled">
            {{ value }}
        </trumbowyg>
    </div>
</template>

<script>
    import trumbowyg from 'vue-trumbowyg';
    // Import editor css
    import 'trumbowyg/dist/ui/trumbowyg.css';

    export default {
        data() {
            return {
                config: {
                    btns: [['formatting'], ['bold', 'italic'], ['link']]
                }
            }
        },
        props: {
            value: {
                type: String
            },
            required: {
                type: Boolean
            },
            placeholder: {
                type: String
            },
            disabled: {
                type: Boolean,
                default: false
            }
        },
        methods: {
            updateValue: function (value) {
                this.$emit('input', value);
            }
        },
        components: {
          trumbowyg
        }
    }
</script>