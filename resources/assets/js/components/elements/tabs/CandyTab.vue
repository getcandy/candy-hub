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
        watch: {
            isActive(val) {
                if (val) {
                    this.$emit('selected', true);
                }
            }
        },
        props: {
            name: {
                type: [String, Object],
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
            },
            badge: {
                type: Number
            },
            dispatch: {
                type: String
            },
            save: {
                type: String
            }
        },
        computed: {
            href() {
                return this.getHref();
            }
        },
        methods: {
            getHref() {
                let name = this.name;
                if (typeof this.name == 'object') {
                     name = this.name[locale.current()];
                }
                return '#' + name.toLowerCase().replace(/[^0-9a-zA-Z]+/g, '');
            }
        },
        mounted() {
            this.isNested = this.$parent.nested;
            this.isActive = this.selected;
        }
    }
</script>
