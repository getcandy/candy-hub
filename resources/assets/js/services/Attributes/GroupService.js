'use strict';

import Language from '../Locale/Language';

export default {

    /**
     * Gets the appropriate group label
     * @param {array} groups
     */
    getLabel(groups) {

        let visible = [];

        groups.forEach(group => {
            visible.push(trans(group.name));
        });

        if (visible.length == groups.length) {
            return 'All';
        }

        if (!visible.length) {
            return 'None';
        }

        return visible.join(', ');
    }
}