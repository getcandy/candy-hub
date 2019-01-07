<template>
    <codemirror
        class="code-editor"
        :value="value" :options="options"
        @ready="$emit('ready', $event)"
        @focus="$emit('focus', $event)"
        @input="$emit('input', $event)"
    >
    </codemirror>
</template>

<script>
    // require component
    import { codemirror } from 'vue-codemirror'

    // require styles
    import 'codemirror/lib/codemirror.css'

    export default {
        components: {
            codemirror,
        },
        props: {
            value: {

            },
            options: {
                default() {
                    return {
                        tabSize: 4,
                        mode: 'text/html',
                        lineNumbers: true,
                    };
                }
            }
        },
    }
</script>

<style scoped>
    .code-editor {
        border:1px solid #a2a1a1;
    }
</style>