<template>
    <div>
        <input type="text" v-model="editableTags"  @change="updateValue($event.target.value)" class="form-control" data-role="tagsinput" :required="required">
    </div>
</template>

<script>
    export default {
        props: {
            value: {
                type: Array
            },
            required: {
                type: Boolean
            }
        },
        mounted() {
            $(this.$el).find('input').tagsinput();
        },
        computed: {
            editableTags: {
                get () {
                    let tags = this.value.map( function(tag){
                        return tag.name;
                    });
                    return tags;
                },
                set (val) {
                    this.$emit('input', val.trim().split(','));
                }
            }
        },
        methods: {
            updateValue: function (value) {
/*
                let tags = value.trim().split(',');

                tags.map(function(tag){
                    return ['name'] = tag;
                });

                console.log(value.trim().split(','));
*/
                this.$emit('input', '');
            }
        }
    }
</script>