<template>
    <div>

        <div class="checkbox">
            <input type="checkbox" :checked="checked" :id="id" @change="updateValue">
            <label :for="id">
                <span class="check"></span>
                <slot></slot>
            </label>
        </div>

    </div>
</template>

<script>
    export default {
        data() {
            return {
                value: this.checked
            }
        },
        props: {
            id:{
                type: String
            },
            required: {
                type: Boolean
            },
            checked: {
                type: Boolean,
                default: false
            }
        },
        methods: {
            updateValue(value) {
                this.value = !this.value;
                this.$emit('change', {id: this.id, checked: this.value})
            }
        }
    }
</script>