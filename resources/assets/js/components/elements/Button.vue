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
        mounted () {
            Event.$on('tab-change', tab => this.job = tab);
        },
        props: {
            event: {
                type: String,
                required: true
            },
        },
        methods : {
            fire () {
                console.log(this.job);
                // dispatcher.dispatch(this.job).then(response => console.log('success!')).catch(error => console.log(error));
            },
            save() {
                console.log('Started Saving a button...');
                for (let $i=0; $i<=1000000000; $i++) {
                    //
                }
                console.log('Saving button done.');
                return true;
            }
        }
    }
</script>