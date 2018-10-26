<template>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel">
                <div class="panel-body">
                    <div v-for="group in groups" :key="group.id">
                        <h4>{{ group.name|t }}</h4>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th width="25%">Name</th>
                                    <th width="25%">Handle</th>
                                    <th width="25%">Type</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="attribute in group.attributes.data" :key="attribute.id">
                                    <td>{{ attribute.name|t }}</td>
                                    <td><code>{{ attribute.handle }}</code></td>
                                    <td>{{ attribute.type }}</td>
                                    <td>
                                        <a href="#">View</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            groups: {
                type: Array,
                default() {
                    return [];
                }
            }
        }
    }
</script>

<style scoped>

</style>