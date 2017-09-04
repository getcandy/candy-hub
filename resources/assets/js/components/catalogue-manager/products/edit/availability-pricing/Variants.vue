<script>
    export default {
        data() {
            return {
                editing: false,
                options: [],
                variants: [],
                optionRowExists: false,
                sortListOptions: {
                    onEnd: this.reorderOptionTags,
                    filter: '.disabled',
                    handle: '.handle',
                    animation: 150
                },
                sortTableOptions: {
                    filter: '.disabled',
                    onEnd: this.reorder,
                    handle: '.handle',
                    animation: 150
                }
            }
        },
        mounted() {
            this.variants = this.product.variants.data;
            Object.keys(this.product.option_data).map(key => {
                this.options.push(this.product.option_data[key]);
            });

            this.options.forEach((option, index) => {
                var options = option.options;
                option.options = [];
                Object.keys(options).map(key => {
                    option.options.push(options[key]);
                });
            });
            this.generateVariants();
        },
        props: {
            product: {
                type: Object
            }
        },
        methods: {
            addOption(option, handle) {
                let value = this.$refs[handle + '_option'][0].value;

                let deny = false;

                if (!value) {
                    return;
                }

                option.options.forEach(option => {
                    if (option.values.en.toUpperCase() == value.toUpperCase()) {
                        deny = true;
                        return;
                    }
                });

                if (deny) {
                    return;
                }

                option.options.push({
                    position: 3,
                    values: {
                        en: value
                    }
                });
                this.$refs[handle + '_option'][0].value = null;
                this.generateVariants();
            },
            getOptionRef(handle) {
                return handle + '_option';
            },
            getHandleRef(handle) {
                return handle + '_handle';
            },
            deleteTag(handle, itemHandle) {
                this.$delete(this.options[handle].options, itemHandle);
                this.generateVariants();
            },
            addOptionRow(event) {
                if (this.optionRowExists) {
                    return;
                }
                this.options.push({
                    options: [],
                    label: {
                        en: event.target.value
                    },
                    position: this.options.length + 1
                });
                // this.$set(this.options, value.slugify(), );
                event.target.value = null;
            },
            reorder({oldIndex, newIndex, item}) {
                const movedItem = this.options.splice(oldIndex, 1)[0];
                this.options.splice(newIndex, 0, movedItem);
                this.generateVariants();
                let pos = 1;
                this.options.forEach(option => {
                    option.position = pos;
                    pos++;
                });
            },
            reorderOptionTags({oldIndex, newIndex, item}) {
                var parent = $(item).data('parent');

                this.options.forEach((option, handle) => {
                    if (option.label.en == parent) {
                        const movedItem = option.options.splice(oldIndex, 1)[0];
                        option.options.splice(newIndex, 0, movedItem);
                        this.generateVariants();
                        let pos = 1;
                        option.options.forEach(option => {
                            option.position = pos;
                            pos++;
                        });
                    }
                });
            },
            /**
             * Generates the variants
             * @return {Array}
             */
            generateVariants() {
                let optionValues = [];
                this.variants = [];
                // console.log(this.options);

                /**
                 * We want to get the options into a format where we can
                 * get all the variations whilst keeping the option name
                 * associated to it. The logic below will give us something like:
                 * [[{size: 10}, {size: 20}, {size: 30}],[{colour: 'red'}]]
                 *
                 * So we can pass this to our allPossibleCases method and hopefully
                 * it will generate the variants
                 */
                this.options.forEach((option, index) => {
                    let childValues = [];

                    option.options.forEach(child => {
                        childValues.push({[option.label.en.trim()] : child.values.en});
                    });
                    optionValues.push(childValues);
                });

                // Get all possible values
                optionValues = this.getAllCombinations(optionValues);

                optionValues.forEach(variant => {
                    let label = '';
                    let data = {};
                    variant.forEach((value, index) => {
                        let keys = Object.keys(value);
                        label += keys + ' ' + value[keys] + ((index + 1) < variant.length ? ', ' : ' ');
                        data[keys] = value[keys];
                    });

                    this.variants.push({
                        label: label,
                        price: '',
                        data: data,
                        inventory: 1,
                        sku: ''
                    });
                });


                this.product.variants.data = this.variants;
            },
            /**
             * Gets all the possible combinations for the variants
             * @param  {Array} arraysToCombine
             * @return {Array}
             */
            getAllCombinations(arraysToCombine) {
                var divisors = [];
                for (var i = arraysToCombine.length - 1; i >= 0; i--) {
                    divisors[i] = divisors[i + 1] ? divisors[i + 1] * arraysToCombine[i + 1].length : 1;
                }
                function getPermutation(n, arraysToCombine) {
                    var result = [],
                        curArray;
                    for (var i = 0; i < arraysToCombine.length; i++) {
                        curArray = arraysToCombine[i];
                        result.push(curArray[Math.floor(n / divisors[i]) % curArray.length]);
                    }
                    return result;
                }
                var numPerms = arraysToCombine[0].length;
                for (var i = 1; i < arraysToCombine.length; i++) {
                    numPerms *= arraysToCombine[i].length;
                }
                var combinations = [];
                for (var i = 0; i < numPerms; i++) {
                    combinations.push(getPermutation(i, arraysToCombine));
                }
                return combinations;
            },
            validateOptionRow(val) {
                this.optionRowExists = false;
                this.options.forEach(option => {
                    if (option.label.en.toUpperCase() == event.target.value.toUpperCase()) {
                        this.optionRowExists = true;
                        return;
                    }
                });
            },
            deleteFromArray(array, index) {
               array.splice(index, 1);
            }
        },
        computed: {}
    }
</script>

<template>
    <div>
        <div class="row">
            <div class="col-xs-12 col-md-11">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-md-8">
                                <h4>Product Availability</h4>
                            </div>
                            <div class="col-md-4 text-right">
                                <!-- <candy-create-variant :product="product"></candy-create-variant> -->
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <h4>Options</h4>
                <p>need to get a unique identy for the options for loop</p>
                <table class="table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>
                                Label
                            </th>
                            <th>
                                Values
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody v-sortable="sortTableOptions">
                        <tr v-for="(option, handle) in options" :key="option.label.en">

                            <td class="handle">
                                <svg width="13px" viewBox="0 0 13 19" version="1.1" xmlns="http://www.w3.org/2000/svg"
                                     xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <g id="Artboard" fill="#D8D8D8">
                                            <rect id="Rectangle" x="2" y="2" width="3" height="3"></rect>
                                            <rect id="Rectangle-Copy-2" x="2" y="8" width="3" height="3"></rect>
                                            <rect id="Rectangle-Copy-4" x="2" y="14" width="3" height="3"></rect>
                                            <rect id="Rectangle-Copy-5" x="8" y="14" width="3" height="3"></rect>
                                            <rect id="Rectangle-Copy" x="8" y="2" width="3" height="3"></rect>
                                            <rect id="Rectangle-Copy-3" x="8" y="8" width="3" height="3"></rect>
                                        </g>
                                    </g>
                                </svg>
                            </td>
                            <td width="20%">{{ option.label.en }}</td>
                            <td>
                                <ul v-sortable="sortListOptions" class="sortable-tags-list">
                                    <li v-for="(item, itemHandle) in option.options" :data-parent="option.label.en" :data-handle="itemHandle" :key="item.values.en">
                                        <span class="handle">
                                            <svg width="13px" height="19px" viewBox="0 0 13 19" version="1.1"
                                               xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                              <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                  <g id="Artboard" fill="#D8D8D8">
                                                      <rect id="Rectangle" x="2" y="2" width="3" height="3"></rect>
                                                      <rect id="Rectangle-Copy-2" x="2" y="8" width="3" height="3"></rect>
                                                      <rect id="Rectangle-Copy-4" x="2" y="14" width="3" height="3"></rect>
                                                      <rect id="Rectangle-Copy-5" x="8" y="14" width="3" height="3"></rect>
                                                      <rect id="Rectangle-Copy" x="8" y="2" width="3" height="3"></rect>
                                                      <rect id="Rectangle-Copy-3" x="8" y="8" width="3" height="3"></rect>
                                                  </g>
                                              </g>
                                            </svg>
                                        </span>
                                        <span class="tag-text">
                                            {{ item.values.en }}
                                        </span>
                                        <button @click="deleteTag(handle, itemHandle)" class="tag-delete">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </li>
                                </ul>
                                <div class="sortable-tags-list-input">
                                    <form class="option-form">
                                        <input type="text" placeholder="Add option" :ref="getOptionRef(handle)">
                                        <button @click.prevent="addOption(option, handle)"><i class="fa fa-plus"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            <td class="text-right">
                                <button class="btn btn-sm btn-default btn-action" @click="deleteFromArray(options, handle)"><i aria-hidden="true" class="fa fa-trash-o"></i></button>
                            </td>
                        </tr>
                    </tbody>

                    <tfoot>
                        <tr>
                            <td colspan="25">
                                <input type="text" class="form-control" placeholder="Type new option and press enter"
                                       @keyup.enter="addOptionRow" @keyup="validateOptionRow">
                            </td>
                        </tr>
                    </tfoot>

                </table>

                <p class="text-danger" v-if="optionRowExists">
                    This option already exists
                </p>

                <hr>
                <h4>Variants</h4>
                <table class="table">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>SKU</th>
                        <th v-for="option in options">
                            {{ option.label.en }}
                        </th>
                        <th>Price</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(variant, index) in variants">
                        <td>{{ variant.label }}</td>
                        <td><input type="text" v-model="variant.sku" class="form-control"></td>
                        <td v-for="(option, handle) in options">
                            <input type="text" class="form-control" v-model="variant.data[option.label.en]">
                        </td>
                        <td><input type="text" v-model="variant.price" class="form-control"></td>
                        <td>
                            <button class="btn btn-sm btn-default btn-action" @click="deleteFromArray(variants, index)">
                                <i aria-hidden="true" class="fa fa-trash-o"></i>
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
