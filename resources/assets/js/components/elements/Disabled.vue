<template>
    <div :class="divClass">
        <div class="disabled-container" data-toggle="tooltip" :data-placement="pos" title="Coming soon!" @click="stop">
            <div class="disabled">
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                divClass: null
            }
        },
        props: {
            inline: {
                type: Boolean,
                default: false
            },
            pos : {
                type: String,
                default: 'top'
            }
        },
        mounted() {
            if (this.inline) {
                this.divClass = 'inline-block'
            }
        },
        methods : {
            stop() {
                return false;
            }
        }
    }
</script>

<style lang="scss" scoped>
    .inline-block {
        display: inline-block;
    }
    .disabled-container {
        cursor: not-allowed;
    }
    .disabled {
        opacity: .5;
        
        pointer-events: none;
    }
</style>
