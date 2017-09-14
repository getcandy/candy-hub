class ComponentLoader {
    constructor()
    {
        this.basePath = './components';
        this.path = '';
    }

    src(path) {
        path = path.replace(/\./g, '/');
        console.log(path);
        return this.basePath + '/' + path + '/';
    }

    get(component) {
        return this.path + component;
    }
}
module.exports = ComponentLoader;