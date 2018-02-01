<script>
    export default {
        data() {
            return {
                code: '',
                payload: {}
            }
        },
        props: {
            criteria: {
                type: Object
            }
        },
        mounted() {
            this.payload = this.criteria;

            if (!this.payload.value) {
                this.$set(this.payload, 'value', '');
            }
        }
    }
</script>


<template>
    <div>
        <div class="form-group">
            <label for="">When the coupon equals</label>
            <input type="text" class="form-control" v-model="payload.value">
        </div>
    </div>
</template>
