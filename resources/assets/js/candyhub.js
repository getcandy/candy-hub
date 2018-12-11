class CandyHub {
    constructor() {
        this.plugins = [];
    }
    addPlugin(config) {
        this.plugins.push(config);
    }

    getComponents(section) {
        let components = [];
        this.plugins.forEach(plugin => {
            if (plugin.components && plugin.components.length) {
                plugin.components.forEach(component => {
                    if (component.section == section) {
                        components.push(component);
                    }
                });
            }
        });
        return components;
    }
}
export default new CandyHub();