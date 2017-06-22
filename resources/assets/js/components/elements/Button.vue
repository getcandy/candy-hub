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
                jobs: [],
                className: 'btn btn-success',
                processing: false
            }
        },
        props: {
            event: {
                type: String,
                required: true
            },
        },
        methods : {
            fire () {
                this.toggleState(true);
                Event.$emit(this.event);
            },
            toggleState (firing) {
                this.className = firing ? 'btn btn-primary' : 'btn btn-success';
                this.processing = firing;
            },
            processRequest (data) {
                this.jobs.push(data);
                this.jobs.forEach(job => {
                    axios({
                      method: job.method,
                      url: job.url,
                      data: job.data
                    }).then(response => this.toggleState(false));
                });
            }
        },
        mounted () {
            Event.$on(this.event + '-request', (data) => this.processRequest(data))
        }
    }
</script>