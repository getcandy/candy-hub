module.exports = {
    methods: {
        visibility(collection, ref) {
            let groups = collection[ref].data,
                visible = [];

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
    },
}