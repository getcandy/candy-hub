import Base from './Base.js';

class Attributes extends Base {

    endpoint() {
        return 'attributes';
    }

    foo() {
        return 'bar';
    }
}

export default new Attributes;