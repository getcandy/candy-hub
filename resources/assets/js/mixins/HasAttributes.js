module.exports = {
    data() {
        return {
            attributes: {
                default() {
                    return {};
                }
            }
        }
    },
    methods: {
        setUp(data) {

            let groups = [];

            _.each(data.attributes.data, attribute => {
                let exists = _.find(groups, group => {
                    return group.handle == attribute.group.data.handle;
                });
                if (attribute.group && !exists) {
                    // Filter out the attributes that don't apply within this group.

                    let attributes = attribute.group.data.attributes.data;

                    let attributables = _.map(data.attributes.data, att => {
                        return att.handle;
                    });

                    attributes = _.filter(attributes, att => {
                        return attributables.includes(att.handle);
                    });

                    attribute.group.data.attributes.data = attributes;

                    groups.push(attribute.group.data);
                }
            });

            this.attribute_groups = groups;
            return data;
        },
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