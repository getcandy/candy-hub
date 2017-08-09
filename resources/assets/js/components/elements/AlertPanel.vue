<template>
    <div>
        <div v-bind:class="classObject" v-if="isShown">
            <button type="button" class="close" @click="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <slot></slot>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                isShown: false,
                isError: false,
                isInfo: true,
                isWarn: false,
            }
        },
        computed: {
            classObject() {

                if (this.level == 'danger') {
                    this.isError = true;
                    this.isInfo = false;
                } else if (this.level == 'warn') {
                    this.isWarn = true;
                    this.isInfo = false;
                }

                return {
                    alert: true,
                    'alert-danger': this.isError,
                    'alert-warning': this.isWarn,
                    'alert-info' : this.isInfo
                }
            }
        },
        mounted() {
            this.isShown = this.shown;
        },
        props: {
            shown: {
                type: Boolean
            },
            level: {
                type: String
            }
        },
        methods : {
            close() {
                return this.isShown = false;
            }
        }
    }
</script>