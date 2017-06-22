<template>
    <div>
        <div class="form-group">
            <label for="">{{ label }}</label>
            <candy-input :value="value"></candy-input>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            label: {
                type: String,
                required: true
            },
            value: {
                default: null
            }
        },
        mounted () {

        }
    }
</script>