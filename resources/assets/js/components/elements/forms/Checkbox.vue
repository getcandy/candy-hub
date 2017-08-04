<template>
    <div>

        <div class="checkbox">
            <input type="checkbox" :id="id" @change="updateValue">
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
                checked: false
            }
        },
        props: {
            id:{
                type: String
            },
            required: {
                type: Boolean
            }
        },
        watch: {
            id: function(newVal, oldVal) {
                this.checked = false;
            }
        },
        methods: {
            updateValue(value) {
                this.checked = !this.checked;
                this.$emit('change', {id: this.id, checked: this.checked})
            }
        }
    }
</script>