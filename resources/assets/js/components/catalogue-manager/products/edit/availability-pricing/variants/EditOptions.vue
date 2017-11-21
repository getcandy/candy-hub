<script>
    export default {
        data() {
            return {
                request: apiRequest,
                modalOpen: false,
                generated: [],
                options: [
                ],
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
        props: {
            product: {
                type: Object
            },
            showModal: {
                type: Boolean,
                default: false
            }
        },
        created() {
            this.modalOpen = this.showModal;
        },
        mounted() {
            this.variants = this.product.variants.data;

            if (this.variants.length == 1 & !this.variants[0].options.length) {
                this.variants = [];
            }

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
            if (!this.variants) {
                this.generateVariants();
            }
        },
        methods: {
            save() {
                this.request.send('post', '/products/' + this.product.id + '/variants', {'variants' : this.variants, 'options': this.options})
                    .then(response => {
                        CandyEvent.$emit('notification', {
                            level: 'success'
                        });
                        CandyEvent.$emit('product-updated', {
                            variants: this.variants
                        });
                        this.modalOpen = false;
                    }).catch(response => {
                        CandyEvent.$emit('notification', {
                            level: 'error',
                            message: 'Missing / Invalid fields'
                        });
                    });
            },
            addOption(option, handle) {
                let value = this.$refs[handle + '_option'][0].value;

                let deny = false;

                if (!value) {
                    return;
                }

                option.options.forEach(option => {
                    if (option.values[locale.current()].toUpperCase() == value.toUpperCase()) {
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
                        [locale.current()]: value
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
                        [locale.current()]: event.target.value
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
                let optionValues = this.getOptionValues();

                let optionsToRemove = [];

                _.each(this.variants, variant => {
                    optionValues.forEach((options, optionValueIndex) => {
                        options.forEach((value, optionIndex) => {
                            let field = Object.keys(value);
                            if (!variant.options[field[0].slugify()]) {
                                variant.options[field[0].slugify()] = {
                                    [locale.current()] : value[field]
                                };
                            };
                            if (variant.options[field[0].slugify()][locale.current()] == value[field]) {
                                console.log(optionValues[optionValueIndex]);
                                optionsToRemove.push(optionValueIndex);
                            }
                        });
                    });
                });

                optionValues = _.filter(optionValues, (item, index) => {
                    if (optionsToRemove.includes(index)) {
                        return false;
                    }
                    return true;
                });

                optionValues.forEach(variant => {
                    let label = '';
                    let data = {};


                    variant.forEach((value, index) => {
                        let keys = Object.keys(value);
                        label += keys + ' ' + value[keys] + ((index + 1) < variant.length ? ', ' : ' ');
                        data[keys[0].slugify()] = {[locale.current()]: value[keys]};
                    });

                    this.variants.push({
                        label: label,
                        price: '',
                        options: data,
                        inventory: 1,
                        sku: ''
                    });
                });


                this.product.variants.data = this.variants;
            },
            getOptionValues() {
                let optionValues = [];
                this.options.forEach((option, index) => {
                    let childValues = [];
                    option.options.forEach(child => {
                        childValues.push({[option.label.en.trim()] : child.values.en});
                    });
                    optionValues.push(childValues);
                });
                return this.getAllCombinations(optionValues);
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
            },
            getOptionValue(option, variant) {
                var handle = option.label['en'].slugify();
                if (variant.options[handle]) {
                    return variant.options[handle]['en'];
                }
                return null;
            }
        }
    }
</script>

<template>
    <div>
        {{ request.getError('variants.0.sku') }}
        <button class="btn btn-primary" @click="modalOpen = true">Edit options</button>
        <candy-modal title="Edit options" v-show="modalOpen" @closed="modalOpen = false">
            <div slot="body" class="text-left">
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
                <table class="table association-table">
                    <thead>
                    <tr>
                        <th>SKU</th>
                        <th v-for="option in options">
                            {{ option.label.en }}
                        </th>
                        <th>Price</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(variant, index) in variants">
                        <td>
                            <input type="text" v-model="variant.sku" class="form-control">
                            <span class="text-danger"
                                  v-if="request.hasError('variants.' + index + '.sku')"
                                  v-text="request.getError('variants.' + index + '.sku')">
                            </span>
                        </td>
                        <td v-for="(option, handle) in options">
                            <input type="text" class="form-control" :value="getOptionValue(option, variant)" disabled>
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
                <!-- <table class="table association-table variants-table">
                    <thead>
                    <tr>
                        <th width="20%">Variant</th>
                        <th width="20%">Price</th>
                        <th width="40%">SKU</th>
                        <th width="10%">Inventory</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody class="list">
                    <tr v-for="(variant, index) in variants">
                        <td width="20%" align="left">
                            {{ variant.label }}
                        </td>
                        <td width="20%">
                            <div class="input-group input-group-full">
                                <span class="input-group-addon">&pound;</span>
                                <input type="text" class="form-control" v-model="variant.price">
                            </div>
                        </td>
                        <td width="40%">
                            <input type="text" class="form-control" v-model="variant.sku" required>
                        </td>
                        <td width="10%"><input type="number" class="form-control" v-model="variant.inventory"></td>
                        <td><button class="btn btn-sm btn-default" @click="removeVariant(index)"><i class="fa fa-times" aria-hidden="true"></i></button></td>
                    </tr>
                    </tbody>
                </table>-->
            </div>
            <template slot="footer">
                <button class="btn btn-primary" @click="save()">Save Options</button>
            </template>
        </candy-modal>
    </div>
</template>
