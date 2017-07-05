<template>
    <div>
        <input type="text" :value="value" class="form-control">
    </div>
</template>

<script>
    export default {

        props: {
            value: {
                type: String
            }
        },
        data() {
            return {
                input: ''
            }
        }

    }
</script>