<template>
    <div>
        Foo bar
    </div>
</template>

<script>
    export default {
        data() {
            return {
                isActive: false,
                isNested: false
            }
        },
        props: {
            name: {
                type: String
            },
            handle: {
                type: String,
                default: ''
            },
            selected: {
                default: false
            }
        },
        computed: {
        },
        mounted() {
            Event.$emit('tab-change', this);
        }
    }
</script>