<template>
    <div role="tabpanel" :class="{ 'active' : isActive, 'tab-pane' : true }" :id="href">
        <div class="sub-panel" v-if="!isNested">
            <slot></slot>
        </div>
        <slot v-else></slot>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                isActive: false,
                isNested: false
            }
        },
        props: {
            name: {
                type: String,
                required: true
            },
            selected: {
                default: false
            }
        },
        computed: {
            href() {
                return '#' + this.name.toLowerCase().replace(/[^0-9a-zA-Z]+/g, '');
            }
        },
        mounted() {
            this.isNested = this.$parent.nested;
            this.isActive = this.selected;
        }
    }
</script>