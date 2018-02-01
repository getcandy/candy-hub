<script>
    export default {
        data() {
            return {
                code: '',
                value: null
            }
        },
        props: {
            criteria: {
                type: String
            }
        },
        mounted() {
            this.value = this.criteria;
        }
    }
</script>


<template>
    <div>
        <div class="form-group">
            <label for="">When the coupon equals</label>
            <input type="text" class="form-control" v-model="value">
        </div>
    </div>
</template>
