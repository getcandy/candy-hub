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
            },
            size: {
                default: 'thumbnail'
            },
        },
        methods: {
            load(url) { //_.debounce(function () {
                this.imageSrc = url
            }
        },
        mounted() {
            const asset = _.find(this.item.assets.data, asset => {
                return asset.primary;
            });

            if (asset) {
                let thumbnail = _.find(asset.transforms.data, transform => {
                    return transform.handle == this.size;
                });

                if (!thumbnail) {
                    thumbnail = asset.transforms.data[0];
                }

                this.load(thumbnail.url);
            }
        }
    }
</script>