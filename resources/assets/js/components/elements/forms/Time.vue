<template>
    <div>
<!--
        <div class="input-group input-group-full">

            <span class="input-group-addon">
                <i class="fa fa-clock-o" aria-hidden="true"></i>
            </span>

            <div class="btn-group bootstrap-select input-group-btn form-control dropup open">

                <button type="button" class="btn dropdown-toggle bs-placeholder btn-default" data-toggle="dropdown" role="button" title="Select a time" aria-expanded="true">
                    <span class="filter-option pull-left">Select a time</span>&nbsp;<span class="bs-caret"><span class="caret"></span></span>
                </button>

                <div class="dropdown-menu open" role="combobox" style="max-height: 213px; overflow: hidden; min-height: 92px;">
                    <ul class="dropdown-menu inner" role="listbox" aria-expanded="true" style="max-height: 201px; overflow-y: auto; min-height: 80px;">

                        <li data-original-index="0" class="disabled selected">
                            <a tabindex="-1" class="" data-tokens="null" role="option" href="#" aria-disabled="true" aria-selected="true">
                                <span class="text">Select a time</span>
                                <span class="glyphicon glyphicon-ok check-mark"></span>
                            </a>
                        </li>
                        <li data-original-index="0" v-for="(option, index) in options">
                            <a tabindex="0" class="" data-tokens="null" role="option" aria-disabled="false" aria-selected="false">
                                <span class="text">{{ option }}</span><span class="glyphicon glyphicon-ok check-mark"></span>
                            </a>
                        </li>

                    </ul>
                </div>

                <select class="form-control selectpicker" tabindex="-98" :value="value" :required="required" @input="updateValue($event.target.value)">

                    <option v-for="option in options" :value="option">{{ option }}</option>

                </select>
            </div>

        </div>
-->
    </div>
</template>

<script>
    export default {
        props: {
            options: {
                type: Array,
                default: ['12:00 am', '12:30 am']

            },
            value: {
                type: String
            },
            required: {
                type: Boolean
            }
        },
        methods: {
            updateValue: function (value) {
                this.$emit('input', value);
            },
            getTimes: function(inc = 30) {
                var arr = [], i, j;
                for(i=0; i<24; i++) {
                    for(j=0; j<4; j++) {
                        arr.push(i + ":" + (j===0 ? "00" : inc*j) );
                    }
                }

                return arr;
            }
        }
    }
</script>