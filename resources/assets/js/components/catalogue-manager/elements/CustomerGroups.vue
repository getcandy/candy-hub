<script>
    export default {
        data() {
            return {

            }
        },
        props: {
            dispatch: {
                type: String
            },
            cols: {
                type: Array,
                default() {
                    return [
                        'visible',
                        'purchasable'
                    ]
                }
            },
            groups: {
                type: Array
            }
        }
    }
</script>

<template>
    <div>
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <h4>Customer Groups</h4>
                <hr>
                <table class="table">
                    <thead>
                    <tr>
                        <th>Group</th>
                        <th v-for="col in cols">{{ col|capitalize }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="group in groups" :key="group.id">
                        <td>{{ group.name }}</td>
                        <td v-for="col in cols">
                            <div class="checkbox">
                                <input :id="'CG' + col + group.id" type="checkbox" v-model="group[col]">
                                <label :for="'CG' + col + group.id"><span class="check"></span></label>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
