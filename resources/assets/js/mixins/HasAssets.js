export default {
    methods: {
        /**
         * Get the thumbnail
         * @param {string} item
         * @param {string} size
         */
        thumbnail(item, size) {
            if (item.thumbnail) {
                return item.thumbnail.data.thumbnail;
            }
            return '/candy-hub/images/placeholder/no-image.png';
        }
    }
}