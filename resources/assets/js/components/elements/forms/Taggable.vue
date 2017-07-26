<template>
    <div>
        <input type="text" v-model="editableTags" class="form-control" data-role="tagsinput" :required="required">
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
            }
        },
        computed: {
            editableTags: {
                get () {
                  return this.value.join(',')
                },
                set (val) {
                  this.$emit('input', val.trim().split(','));
                  Event.$emit('tagged', val.split(','));
                }
            }
        },
        methods: {
            updateValue: function (value) {
                this.$emit('input', value);
            }
        }
    }
</script>