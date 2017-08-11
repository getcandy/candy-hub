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
                job: null,
                className: 'btn btn-success',
            }
        },
        created () {
            CandyEvent.$on('current-tab', tab => this.job = tab);
            CandyEvent.$on('notification', finished => this.processing = false);
        },
        methods : {
            fire () {
                this.job.save();
            }
        }
    }
</script>