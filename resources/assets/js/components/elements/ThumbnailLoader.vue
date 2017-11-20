<template>
    <div>
        <img :src="imageSrc"></img>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                imageSrc: '/images/placeholder/no-image.png'
            }
        },
        props: {
            item: {
                type: Object
            }
        },
        methods: {
            load: _.debounce(function () {
                    let src = this.item.thumbnail.data.thumbnail,
                        img = new Image(),
                        that = this;

                    img.onload = function() {
                        that.imageSrc = this.src
                    }
                    img.src = src;
                }, 1000
            ),
        },
        mounted() {
            if (this.item.thumbnail) {
               this.load();
            }
        }
    }
</script>