<template>
    <div role="tabpanel" :class="{ 'active' : isActive, 'tab-pane' : true, 'hidden' : hidden }" :id="href" :ref="href">
        <slot></slot>
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
            handle: {
                type: String,
                default: ''
            },
            selected: {
                default: false
            },
            hidden: {
                default: false
            }
        },
        computed: {
            href() {
                return this.getHref();
            }
        },
        methods: {
            getHref() {
                return '#' + this.name.toLowerCase().replace(/[^0-9a-zA-Z]+/g, '');
            }
        },
        mounted() {
            this.isNested = this.$parent.nested;
            this.isActive = this.selected;
        }
    }
</script>
