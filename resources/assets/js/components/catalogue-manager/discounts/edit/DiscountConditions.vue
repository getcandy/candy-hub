<script>

    export default {
        data() {
            return {
                sets: []
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
            if (this.discount.sets.data) {
                this.sets = this.discount.sets.data;
            }
        },
        methods: {
            getType(item) {
                return 'candy-discounts-' + item.type;
            }
        }
    }
</script>
<template>
    <div>
                            <!-- <div class="tab-content sub-content section block">
                        <div class="row">
                            <div class="col-md-12"> -->
        <div class="condition-sets">
            <div v-for="(set,index) in sets" :key="index">
                <div class="set">
                    <header class="clearfix">
                        <section class="pull-left">
                            <span>If</span>
                            <select class="form-control" v-model="set.scope">
                                <option value="any">Any</option>
                                <option value="all">All</option>
                            </select>
                            <span>of these conditions are</span>
                            <select class="form-control" v-model="set.outcome">
                                <option value="true">True</option>
                                <option value="false">False</option>
                            </select>
                        </section>
                        <section class="pull-right">
                            <button class="btn btn-default">Add Condition</button>
                        </section>
                    </header>
                    <div class="condition clearfix" v-for="(item, itemIndex) in set.items.data" :key="set.id">
                        <div class="divider" v-if="itemIndex > 0">
                            <span v-if="set.scope == 'all'">AND</span>
                            <span v-else>OR</span>
                        </div>
                        <section>
                            <component :is="getType(item)" :criteria="item.criteria"></component>
                        </section>
                    </div>
                </div>
            </div>
            <button></button>
        </div>
    </div>
</template>

<style lang="scss">
    .condition-sets {
        margin: 2em 0;
        padding:1em 20px;
    }
    .condition {
        .divider {
            text-align:center;
            color:#979797;
            display:block;
            margin:1em 0;
        }
        > section {
            background-color:#fff;
            padding:1em 20px;
        }
    }
    .set {
        border:1px solid #979797;
        padding:1em 20px;
        position: relative;
        header {
            margin-bottom:1em;
            select {
                width:60px;
                display:inline-block;
                margin: 0 5px;
            }
        }
    }
</style>
