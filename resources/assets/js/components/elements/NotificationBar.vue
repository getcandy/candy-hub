<template>
    <div>
        <div class="notification-bar animated fadeIn flash" :class="classname" v-if="isActive">
            <div class="animated bounceInUp">
              <span class="icon"><i class="fa fa-check"></i></span> {{ message }}
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                message: 'Changes Saved',
                classname: 'success',
                isActive: false
            }
        },
        mounted() {
            Event.$on('notification', data => this.update(data));
        },
        methods : {
            update(data) {
                this.isActive = true;
                this.classname = data.level;
                this.message = data.message ? data.message : this.message;

                setTimeout(() => this.classname = 'fadeOut', 2000);
                setTimeout(() => this.isActive = false, 5000);
            }
        }
    }
</script>