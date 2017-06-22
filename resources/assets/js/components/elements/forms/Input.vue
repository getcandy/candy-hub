<template>
    <div>
        <input type="text" v-model="value" class="form-control">
        {{ value }}
    </div>
</template>

<script>
    export default {
        data() {
            return {
                value: null
            }
        },
        props: {
        },
        mounted () {

        }
    }
</script>