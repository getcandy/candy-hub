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
                        <th>Visible</th>
                        <th>Purchasable</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="group in groups" :key="group.id">
                        <td>{{ group.name }}</td>
                        <td>
                            <div class="checkbox">
                                <input :id="'CGV' + group.id" type="checkbox" v-model="group.visible">
                                <label :for="'CGV' + group.id"><span class="check"></span></label>
                            </div>
                        </td>
                        <td>
                            <div class="checkbox">
                                <input :id="'CGP' + group.id" type="checkbox" v-model="group.purchasable">
                                <label :for="'CGP' + group.id"><span class="check"></span></label>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
