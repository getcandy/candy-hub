<template>
    <div>
        <img :src="imageSrc"></img>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                imageSrc: '/images/placeholder/no-image.svg'
            }
        },
        props: {
            item: {
                type: Object
            }
        },
        mounted() {
            if (this.item.thumbnail) {
                let src = this.item.thumbnail.data.thumbnail,
                    img = new Image(),
                    that = this;

                img.onload = function() {
                    that.imageSrc = this.src
                }
                img.src = src;
            }
        }
    }
</script>