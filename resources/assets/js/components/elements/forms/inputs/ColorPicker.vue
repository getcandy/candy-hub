<template>
    <div class="picker-holder">
        <div  v-click-outside="hide">
        <button class="btn btn-default" @click="show = true"><span class="btn-bg" :style="{
            'background': value,
        }"></span></button>
        <div class="picker"  v-if="show" >
            <color-picker :value="value" @input="update" /></color-picker>
        </div>

        </div>
    </div>
</template>

<script>
    import { Sketch } from 'vue-color';

    export default {
        data() {
            return {
                show: false,
            }
        },
        components: {
            'color-picker': Sketch,
        },
        props: {
            value: {
                type: String
            },
        },
        methods: {
            hide() {
                this.show = false;
            },
            update(value) {
                this.$emit('input', value.hex);
            }
        }
    }
</script>

<style scoped>
    .picker-holder {
        position:relative;
    }
    .picker {
        position:absolute;
        z-index: 500;
    }
    .btn-bg {
        min-width:40px;
        min-height: 20px;
        display:block;
    }
</style>