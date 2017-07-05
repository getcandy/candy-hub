<template>
    <div>
        <select class="form-control">
            <option v-for="option in options" :value="option.value">{{ option.label }}</option>
        </select>
    </div>
</template>

<script>
    export default {
        props: {
            input: {
                type: Object
            },
            options: {
                type: Array
            },
            value: {
                type: String
            }
        },
        created() {
            //console.log(this.options)
         }
    }
</script>