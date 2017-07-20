<template>
    <div>
        <button @click="fire()" :class="className">
            <div v-if="!processing">
                <i class="fa fa-floppy-o fa-first" aria-hidden="true"></i> <slot></slot>
            </div>
            <div v-else>
               <i class="fa fa-refresh fa-spin" aria-hidden="true"></i> Saving
            </div>
        </button>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                job: null,
                className: 'btn btn-success',
                processing: false
            }
        },
        created () {
            Event.$on('current-tab', tab => this.job = tab);
            Event.$on('notification', finished => this.processing = false);
        },
        methods : {
            fire () {
                this.processing = true;
                this.job.save();
            }
        }
    }
</script>