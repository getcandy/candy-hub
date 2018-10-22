module.exports = {
    methods: {
        getAttributeGroups(collection) {
            let groups = collection.attribute_groups.data,
                visible = [];

            groups.forEach(group => {
                visible.push(group.name.en);
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