<script>
    import flatPickr from 'vue-flatpickr-component';
    import 'flatpickr/dist/flatpickr.css';

    export default {
        data() {
            return {
                discount: {},
                languages: [],
                loaded: false,
            }
        },
        props: {
            id: {
                type: String
            }
        },
        mounted() {
            apiRequest.send('GET', 'discounts/' + this.id, {}, {
                includes: 'channels,sets,rewards,sets.items,attribute_groups,attribute_groups.attributes'
            }).then(response => {
                this.discount = response.data;
                this.loaded = true;
            });
        }
    }
</script>

<template>
    <div>
        <template v-if="loaded">
            <candy-tabs initial="productdetails">
                <candy-tab name="Basic information" :selected="true" dispatch="product-details">
                    <candy-discount-details :discount="discount"></candy-discount-details>
                </candy-tab>
                <candy-tab name="Conditions">
                    <div class="tab-content sub-content section block">
                        <div class="row">
                            <div class="col-md-12">
                                Conditions
                            </div>
                        </div>
                    </div>
                </candy-tab>
                <candy-tab name="Rewards">
                    <div class="tab-content sub-content section block">
                        <div class="row">
                            <div class="col-md-12">
                                Rewards
                            </div>
                        </div>
                    </div>
                </candy-tab>
            </candy-tabs>
        </template>
        <div v-else>
            <div class="page-loading loading">
                <span><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i></span> <strong>Loading</strong>
            </div>
        </div>
        <!--<div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Name</label>
                    <input class="form-control" v-model="discount.name">
                </div>
            </div>
            <div class="col-md-6">
                <label>Stackable</label><br>
                <div class="checkbox">
                    <input id="stackable" type="checkbox" v-model="discount.stackable">
                    <label for="stackable"><span class="check"></span></label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Start date</label>
                    <div class="input-group input-group-full">
                        <flat-pickr class="datetimepicker" v-model="discount.start_at" :config="flatPickrConfig"></flat-pickr>
                        <div class="input-group-addon">
                            <i class="fa fa-calendar-o" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>End date</label>
                    <div class="input-group input-group-full">
                        <flat-pickr class="datetimepicker" v-model="discount.end_at" :config="flatPickrConfig"></flat-pickr>
                        <div class="input-group-addon">
                            <i class="fa fa-calendar-o" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Result</label>
                    <candy-select :options="[
                            {label: 'Take percentage off original', value: 'percentage_amount'},
                            {label: 'To fixed price', value: 'fixed_amount'},
                            {label: 'Take fixed amount off original', value: 'to_fixed_price'}
                        ]" v-model="discount.result"></candy-select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Value</label>
                    <input class="form-control" v-model="discount.value">
                </div>
            </div>
        </div>
        <template v-for="(set, setIndex) in discount.sets">
            <div class="row">
                <div class="col-md-11">
                    <div class="panel">
                        <div class="panel-body">
                            <template v-for="(item, index) in set.items">
                                <div class="row">
                                    <div class="col-md-3">
                                        <span class="criteria-label">{{ item.target }}</span>
                                    </div>
                                    <div class="col-md-3">
                                        <span class="criteria-modifier">{{ item.operator }}</span>
                                    </div>
                                    <div class="col-md-3">
                                        <candy-select :options="item.choices" v-model="item.value"></candy-select>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-sm btn-danger" @click="removeCriteria(setIndex, index)">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="criteria-divider" v-if="index != set.items.length - 1">
                                    AND
                                </div>
                            </template>
                            
                            <template v-if="!set.items.length">
                                <span class="text-info">You do not have any criteria set</span>
                            </template>
                        </div>
                        <div class="panel-footer">
                            <div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Add Criteria <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="#" @click="addCoupon(setIndex)">Coupon</a></li>
                                    <li><a href="#" @click="addCustomerGroup(setIndex)">Customer Group</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-default" @click="removeSet(setIndex)"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="criteria-divider" v-if="setIndex != discount.sets.length - 1">
                OR
            </div>
        </template>
        <hr>
        <button class="btn btn-primary" @click="addSet">Add Set</button>-->
    </div>
</template>
