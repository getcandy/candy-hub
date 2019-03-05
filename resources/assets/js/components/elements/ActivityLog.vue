<template>
    <div>
        <h3>Activity Log</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Public</th>
                    <th>System Generated</th>
                    <th>Performed by</th>
                    <th>Description</th>
                    <th>Details</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in items">
                    <td  width="5%">
                        <template v-if="item.type == 'public'">
                            <i class="fa fa-check text-success"></i>
                        </template>
                        <template v-else>
                            <i class="fa fa-times text-danger"></i>
                        </template>
                    </td>
                    <td width="10%">
                        <template v-if="item.type == 'system'">
                            <i class="fa fa-check text-success"></i>
                        </template>
                        <template v-else>
                            <i class="fa fa-times text-danger"></i>
                        </template>
                    </td>
                    <td>
                        <template v-if="item.user.data">
                            {{ item.user.data.details.data.firstname }} {{ item.user.data.details.data.lastname }}

                        </template>
                        <template v-else>
                            N/A
                        </template>
                    </td>
                    <td>{{ item.description }}</td>
                    <td>
                        <div v-for="(detail, handle) in item.properties">
                            <template v-if="handle == 'attributes'">
                                <template v-if="getChanges(item).length">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th width="30%">Field</th>
                                                <th width="30%">Old Value</th>
                                                <th>New Value</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="change in getChanges(item)" :key="change.field">
                                                <td>{{ change.field }}</td>
                                                <td>{{ change.old ? change.old : '-' }}</td>
                                                <td>{{ change.new }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </template>
                                <template v-else>
                                    No changes detected
                                </template>
                            </template>
                            <template v-else-if="handle !== 'old'">
                                <strong>{{ handle }}:</strong> {{ detail }}
                            </template>
                        </div>
                    </td>
                    <td>
                        {{ item.created_at.date|formatDate }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        props: {
            id: {
                type: String,
                required: true,
            },
            type: {
                type: String,
                required: true,
            },
        },
        data() {
            return {
                items: [],
            };
        },
        methods: {
            getChanges(item) {
                let changes = [];

                let newFields = item.properties.attributes;

                _.each(item.properties.old, (oValue, field) => {
                    if (newFields[field] != oValue && field != 'updated_at') {
                        changes.push({
                            'field' : field,
                            'new': newFields[field],
                            'old': oValue,
                        });
                    }
                });
                return changes;
            },
            load() {
                apiRequest.send('GET', '/activity-log', {}, {
                    type: this.type,
                    id: this.id,
                }).then(response => {
                    this.items = response.data;
                });
            }
        },
        mounted() {
           this.load();
           CandyEvent.$on('log-updated', event => {
               this.load();
           });
        }
    }
</script>

<style scoped>

</style>