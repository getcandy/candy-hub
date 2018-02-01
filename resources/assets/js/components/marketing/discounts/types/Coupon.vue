<script>
    export default {
        data() {
            return {
                code: ''
            }
        },
        props: {
            item: {
                type: Object
            }
        },
        methods: {
            sync(value) {
                this.$set(this.item, 'value', this.code);
            },
        }
    }
</script>

<template>
    <div>
        <div class="form-group">
            <label for="">When the coupon equals</label>
            <input type="text" class="form-control" @input="sync" v-model="code" />
        </div>
    </div>
</template>
