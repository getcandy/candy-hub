<script>
    export default {
        props: {
            item: {
                type: Object
            }
        }
    }
</script>

<template>
    <div>
        <div class="form-group">
            <label for="">When the coupon equals</label>
            <input type="text" class="form-control"  v-model="item.value" />
        </div>
    </div>
</template>
