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
                console.log('hit');
            });
            CandyEvent.$on('notification', finished => this.processing = false);
        },
        methods : {
            fire () {
                var ref = Dispatcher.resolve(this.tab.dispatch);
                ref.save();
            }
        }
    }
</script>