module.exports = {
    methods: {
        visibility(collection, ref) {
            console.log(collection);
            let groups,
                visible = [];

            if (ref) {
                groups = collection[ref].data;
            } else {
                groups = collection.data;
            }

            if (ref == 'customer_groups') {
            }

            groups.forEach(group => {
                let label = group.name;
                // If this is time based visibility, we need to account for it.
                if (group.hasOwnProperty('published_at')) {
                    // Is this visible?
                    if (group.published_at) {
                        // Is it in the future or is it now.
                        let date = moment(group.published_at),
                            now = moment();
                        if (date.isAfter(now)) {
                            label += ' ' + date.fromNow();
                        }
                        visible.push(label);
                    }
                } else if (group.visible) {
                    visible.push(group.name);
                } else if (group.visible == undefined && group.published_at == undefined) {
                    visible.push(group.name);
                }
            });

            if (visible.length == groups.length) {
                return 'All';
            }
            if (!visible.length) {
                return 'None';
            }
            return visible.join(', ');
        },
        purchasable(collection, ref) {
            let groups,
            purchasable = [];

            if (ref) {
                groups = collection[ref].data;
            } else {
                groups = collection.data;
            }

            groups.forEach(group => {
                let label = group.name;
                // If this is time based visibility, we need to account for it.
                if (group.hasOwnProperty('published_at')) {
                    // Is this visible?
                    if (group.published_at) {
                        // Is it in the future or is it now.
                        let date = moment(group.published_at),
                            now = moment();
                        if (date.isAfter(now)) {
                            label += ' ' + date.fromNow();
                        }
                        visible.push(label);
                    }
                } else if (group.purchasable) {
                    purchasable.push(group.name);
                } else if (group.purchasable == undefined && group.published_at == undefined) {
                    purchasable.push(group.name);
                }
            });

            if (purchasable.length == groups.length) {
                return 'Yes';
            }
            if (!purchasable.length) {
                return 'No';
            }
            return purchasable.join(', ');
        },
    },
}