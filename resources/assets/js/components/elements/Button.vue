<template>
    <div>
        <button @click="fire()" :class="className">
            <i class="fa fa-floppy-o fa-first" aria-hidden="true"></i> <slot></slot>
        </button>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                tab: null,
                className: 'btn btn-success',
            }
        },
        mounted () {
            CandyEvent.$on('current-tab', tab => {
                this.tab = tab;
            });
            CandyEvent.$on('notification', finished => this.processing = false);
        },
        props: {
            override: {
                type: String,
                default: null
            }
        },
        methods : {
            fire () {
                if (this.override) {
                    var ref = Dispatcher.resolve(this.override);
                } else {
                    var ref = Dispatcher.resolve(this.tab.dispatch);
                }
                if (this.tab && this.tab.save) {
                    ref[this.tab.save]()
                } else {
                    ref.save();
                }
            }
        }
    }
</script>