<script>
    export default {
        props: {
            title: {
                default: 'My Modal',
                type: String
            },
        },
        data () {
            return {
                effect: 'fadeIn',
                backdropEffect: 'fadeInBack'
            }
        },
        methods: {
            closeModal: function () {
                this.effect = 'fadeOut'
                this.backdropEffect = 'fadeOutBack'
                var $this = this;
                setTimeout(function(){
                    $this.$emit('closed')
                    $this.effect = 'fadeIn'
                    $this.backdropEffect = 'fadeInBack'
                }, 500);
            },
        }
    }
</script>

<template>
    <div>
        <div :class="['modal','animated','animatedFast',effect]" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button @click="closeModal" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">{{ title }}</h4>
                    </div>
                    <div class="modal-body">
                        <slot name="body"></slot>
                    </div>
                    <div class="modal-footer">
                        <button @click="closeModal" type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <slot name="footer"></slot>
                    </div>
                </div>
            </div>
        </div>
        <div :class="['modal-backdrop','in','animated','animatedFast',backdropEffect]"></div>
    </div>
</template>

<style scoped>
    .modal {
        display: block;
    }

    .animatedFast {
        -webkit-animation-duration: 0.5s;
        animation-duration: 0.5s;
    }

    @keyframes fadeInBack {
        from {
            opacity: 0;
        }

        to {
            opacity: 0.8;
        }
    }

    .fadeInBack {
        animation-name: fadeInBack;
    }

    @keyframes fadeOutBack {
        from {
            opacity: 0.8;
        }

        to {
            opacity: 0;
        }
    }

    .fadeOutBack {
        animation-name: fadeOutBack;
    }
</style>
