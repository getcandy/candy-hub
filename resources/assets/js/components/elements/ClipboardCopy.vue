<template>
    <button ref="btn" @click.stop.prevent="copy" @hover="copied = false" :class="{ copied: copied }">
        <span v-if="copied">copied</span><span v-else>copy</span>
    </button>
</template>

<script>
    export default {
        props: ['text'],
        data() {
            return {
                copied: false,
            }
        },
        watch: {
            copied(val) {
                if (val) {
                    setTimeout(() => {
                        this.copied = false;
                    }, 500);
                }
            }
        },
        methods: {
            copy() {
                if (!navigator.clipboard) {
                    this.fallbackCopyTextToClipboard(this.text);
                    return;
                }
                navigator.clipboard.writeText(this.text).then(function() {
                    this.copied = true;
                }, function(err) {
                    this.copied = false;
                });
            },
            fallbackCopyTextToClipboard(text) {
                var textArea = document.createElement("textarea");
                textArea.value = text;
                this.$refs['btn'].appendChild(textArea);
                textArea.focus();
                textArea.select();

                try {
                    var successful = document.execCommand('copy');
                    var msg = successful ? 'successful' : 'unsuccessful';
                    this.copied = true;
                } catch (err) {
                    this.copied = false;
                }
                this.$refs['btn'].removeChild(textArea);
            }
        }
    }
</script>

<style scoped>
    button {
        border: none;
        background-color: #EBEBEB;
        display:inline-block;
    }
    button.copied {
        color: white;
        background-color:#339C5E;
    }
</style>