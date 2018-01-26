<script>
    export default {
        data() {
            return {
                groups: [],
                keywords: null,
                loading: false,
                value: null,
                selected: [],
                params: {
                    per_page: 25,
                    current_page: 1
                }
            }
        },
        props: {
            criteria: {
                type: String
            },
            eligibles: {
                type: Object
            }
        },
        mounted() {
            this.value = _.map(this.eligibles.data, item => {
                return item.id;
            });
            this.selected = this.value;
            apiRequest.send('GET', 'customers/groups', []).then(response => {
                this.groups = response.data;
            });
        },
        methods: {
            sync() {
                this.value = this.selected;
            },
            remove(id) {
                this.criteria.groups.splice(this.criteria.groups.indexOf(id), 1);
            },
            unselect(index) {
                this.selected.splice(index, 1);
                this.sync();
            }
        }
    }
</script>


<template>
    <div>
        <h5>Customer groups</h5>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="group in groups" :key="group.id">
                    <td>
                        <label>
                            <input type="checkbox" v-model="selected" @change="sync" :value="group.id">
                            {{ group.name }}
                        </label>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
