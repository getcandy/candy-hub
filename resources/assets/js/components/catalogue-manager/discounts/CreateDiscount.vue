<script>
    export default {
        data() {
            return {
                groups: [],
                customerGroups: []
            }
        },
        props: {
        },
        mounted() {
            apiRequest.send('GET', 'customers/groups').then(response => {
                this.customerGroups = _.map(response.data, function (item) {
                    return {
                        label: item.name,
                        value: item.id
                    };
                })
            });
        },
        methods: {
            addGroup() {
                this.groups.push({
                    rows: []
                })
            },
            addCustomerGroup(group) {
                this.groups[group].rows.push({
                    target: 'Customer',
                    modifier: 'IN',
                    choices: this.customerGroups
                });
            },
            addCoupon(group) {
                this.groups[group].rows.push({
                    target: 'Coupon',
                    modifier: 'EQUALS',
                    choices: ['Coupons']
                })
            },
            removeGroup(index) {
                this.groups.splice(index, 1);
            },
            removeCriteria(group, index) {
                this.groups[group].rows.splice(index, 1);
            }
        }
    }
</script>

<style lang="scss">
    .group {
        border:2px solid #ebebeb;
        padding:2em;
    }
    .criteria-label, .criteria-modifier {
        line-height:40px;
        display:block;
    }
    .criteria-divider {
        margin:1em 0;
        text-align:left;
        text-transform: uppercase;
        color:#BDBDBD;
    }
</style>

<template>
    <div>
        <template v-for="(group, groupIndex) in groups">
            <div class="row">
                <div class="col-md-10">
                    <div class="panel">
                        <div class="panel-body">
                            <template v-for="(row, index) in group.rows">
                                <div class="row">
                                    <div class="col-md-1">
                                        <span class="criteria-label">{{ row.target }}</span>
                                    </div>
                                    <div class="col-md-1">
                                        <span class="criteria-modifier">{{ row.modifier }}</span>
                                    </div>
                                    <div class="col-md-3">
                                        <candy-select :options="row.choices"></candy-select>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-sm btn-danger" @click="removeCriteria(groupIndex, index)">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="criteria-divider" v-if="index != group.rows.length - 1">
                                    AND
                                </div>
                            </template>
                            
                            <template v-if="!group.rows.length">
                                <span class="text-info">You do not have any criteria set</span>
                            </template>
                        </div>
                        <div class="panel-footer">
                            <div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Add Criteria <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="#" @click="addCoupon(groupIndex)">Coupon</a></li>
                                    <li><a href="#" @click="addCustomerGroup(groupIndex)">Customer Group</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-default" @click="removeGroup(groupIndex)"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="criteria-divider" v-if="groupIndex != groups.length - 1">
                OR
            </div>
        </template>
        <hr>
        <button class="btn btn-primary" @click="addGroup">Add Group</button>
        <hr>
        <div class="row">
            <div class="col-md-1">
                Then
            </div>
            <div class="col-md-3">
                <candy-select :options="[
                    {label: 'Take percentage off original', value: 'percent'},
                    {label: 'To fixed price', value: 'fixed'},
                    {label: 'Take fixed amount off original', value: 'flat'}
                ]"></candy-select>
            </div>
            <div class="col-md-2">
                <input class="form-control">
            </div>
        </div>
    </div>
</template>
