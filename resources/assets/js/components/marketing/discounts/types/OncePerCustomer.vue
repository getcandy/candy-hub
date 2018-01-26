<script>
    export default {
        data() {
            return {
                value: false
            }
        },
        props: {
            criteria: {
                type: String
            },
            thing: {
                type: {}
            }
        },
        mounted() {
            this.value = this.criteria;
        },
        methods: {
        }
    }
</script>


<template>
    <div>
        <div class="checkbox rounded small">
            <input type="checkbox" id="wz6d39dj" value="Y" v-model="value">
                <label for="wz6d39dj"><span class="check"></span>
                <span class="faux-label">Once per customer</span>
            </label>
        </div>
    </div>
</template>
