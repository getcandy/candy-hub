<template>
    <div>
        <lazy-component>
            <img :src="imageSrc">
        </lazy-component>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                imageSrc: '/candy-hub/images/placeholder/no-image.png'
            }
        },
        props: {
            item: {
                type: Object
            }
        },
        methods: {
            load() { //_.debounce(function () {
                    this.imageSrc = this.item.thumbnail.data.thumbnail
                }
        },
        mounted() {
            if (this.item.thumbnail) {
               this.load();
            }
        }
    }
</script>