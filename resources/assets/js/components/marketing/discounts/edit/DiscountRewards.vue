<script>

    export default {
        data() {
            return {
                rewards: [],
                newReward: {
                    type: 'percentage_amount',
                    amount: ''
                },
                sortableOptions: {
                    onEnd: this.reorder,
                    filter: '.disabled',
                    handle: '.handle',
                    animation: 150
                }
            }
        },
        props: {
            discount: {
                type: Object,
                default() {
                    return {};
                }
            }
        },
        mounted() {
            if (this.discount.rewards.data) {
                this.rewards = this.discount.rewards.data
            }
        },
        methods: {
            add() {
                this.rewards.push(this.newReward);
                this.newReward = {
                    type: 'percentage_amount',
                    amount: ''
                };
            },
            remove(index) {
                this.rewards.splice(index, 1);
            },
            reorder ({oldIndex, newIndex}) {
                const movedItem = this.rewards.splice(oldIndex, 1)[0];
                this.rewards.splice(newIndex, 0, movedItem);
            }
        }
    }
</script>
<template>
    <div>
        <div class="row" style="min-width:917px">
            <div class="col-md-12">
                <table class="table sortable">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th width="5%"></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th>
                                <select v-model="newReward.type" class="form-control">
                                    <option value="percentage_amount">Percentage</option>
                                    <option value="fixed_amount">Fixed amount</option>
                                    <option value="to_fixed_price">Fixed Price</option>
                                </select>
                            </th>
                            <th>
                                <input type="text" v-model="newReward.value" class="form-control">
                            </th>
                            <th>
                                <button @click="add" class="btn btn-sm btn-success btn-action"><fa icon="plus" aria-hidden="true" title="Add"></fa></button>
                            </th>
                        </tr>
                    </tfoot>
                    <tbody v-sortable="sortableOptions">
                        <tr v-for="(reward, index) in rewards" :key="index">
                            <td class="handle">
                                <svg width="13px" viewBox="0 0 13 19" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
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
                            <td>
                                {{ reward.type }}
                            </td>
                            <td>{{ reward.value }}</td>
                            <td>
                                <button class="btn btn-sm btn-default btn-action" @click="remove(reward)"><fa icon="trash" aria-hidden="true" title="Delete"></fa></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>