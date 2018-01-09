<template>
    <div>
        <template v-if="richtext">
            <trumbowyg class="form-control"
                    rows="8"
                    :value="value"
                    @tbw-change="updateValue($event.target.value)"
                    :required="required"
                    :config="config"
                    :placeholder="placeholder">
                {{ value }}
            </trumbowyg>
        </template>
        <template v-else>
            <textarea class="form-control"
                  rows="8"
                  :value="value"
                  @input="updateValue($event.target.value)"
                  :required="required"
                  :placeholder="placeholder"
                  :disabled="disabled">
                {{ value }}
            </textarea>
        </template>
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
                    btns: [['formatting'], ['bold', 'italic'], ['link'], ['unorderedList', 'orderedList'], ['removeformat'], ['viewHTML']]
                }
            }
        },
        watch: {
            disabled() {
                this.setState();
            }
        },
        mounted() {
            this.setState();
        },
        props: {
            richtext: {
                type: Boolean,
                default: false
            },
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
            setState() {
                if (this.richtext) {
                    let textarea = this.$children[0].$el;
                    $(textarea).trumbowyg(this.disabled ? 'disable' : 'enable');
                }
            },
            updateValue: function (value) {
                this.$emit('input', value);
            }
        },
        components: {
          trumbowyg
        }
    }
</script>