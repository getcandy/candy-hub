<template>
    <div >
        <button type="button" class="btn btn-primary" @click="show = true">{{ buttonText }}</button>

        <candy-modal title="Add product associations" v-show="show" @closed="show = false">

            <div slot="body">
                <table class="table association-table text-left">
                    <thead>
                        <tr>
                            <th> </th>
                            <th>Name</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tfoot v-if="loading" class="text-center">
                        <tr>
                            <td colspan="25" style="padding:40px;">
                                <div class="loading">
                                    <span><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i></span> <strong>Loading</strong>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                    <tbody class="list">
                    <tr v-for="collection in collections" :key="collection.id">
                        <td width="10%">
                        <!-- <img :src="productThumbnail(product)" :alt="product|attribute('name')" class="img-sm"> -->
                        </td>
                        <td class="name" width="40%">{{ collection|attribute('name') }}</td>
                        <td align="right">
                            <button @click="attach(collection)" class="btn btn-sm btn-action btn-success" v-if="!alreadyLinked(collection)">
                                <fa icon="plus"></fa>
                            </button>
                            <button @click="detatch(collection)" class="btn btn-sm btn-default btn-action" v-else>
                                <fa icon="trash"></fa>
                            </button>
                        </td>
                    </tr>
                    <!-- <tr v-for="product in products">
                        <td width="20%"><img :src="productThumbnail(product)" :alt="product|attribute('name')" class="img-sm"></td>
                        <td class="name" width="60%">{{ product|attribute('name') }}</td>
                        <td>
                        <select class="form-control selectize" v-model="loaded[product.id].type">
                            <option v-for="group in groups" :value="group.handle">
                            {{ group.name }}
                            </option>
                        </select>
                        </td>

                        <td align="right">
                        <div class="checkbox">
                            <input :id="product.id" type="checkbox" :value="product.id" v-model="loaded[product.id].selected">
                            <label :for="product.id"><span class="check"></span></label>
                        </div>
                        </td>
                    </tr> -->
                    </tbody>
                </table>
            </div>
            <template slot="footer">
                <button class="btn btn-primary" @click="emit">{{ buttonConfirm }}</button>
            </template>
        </candy-modal>
    </div>
</template>

<script>
    export default {
        props: {
            buttonText: {
                type: String,
                default: 'Browse Collections',
            },
            buttonConfirm: {
                type: String,
                default: 'Attach Collections',
            },
            current: {
                type: Array|Object,
                default() {
                    return [];
                }
            },
        },
        data() {
            return {
                show: false,
                loading: false,
                collections: [],
                selected: [],
            }
        },
        mounted() {
            this.selected = this.current;

            apiRequest.send('GET', '/collections').then(response => {
                this.collections = response.data;
            });
        },
        computed: {
            ids() {
                return _.map(this.selected, item => {
                    return item.id;
                });
            }
        },
        methods: {
            emit() {
                this.$emit('saved', this.selected);
                this.show = false;
            },
            alreadyLinked(collection) {
                const result = _.find(this.selected, item => {
                    return item.id == collection.id;
                });
                return result;
            },
            attach(collection) {
                this.selected.push(collection);
            },
            detatch(collection) {
                this.selected.splice(this.selected.indexOf(collection), 1);
            },
        }
    }
</script>

<style scoped>

</style>