<script>
    export default {
        data() {
            return {
                zones: [],
                selected: []
            }
        },
        props: {
            method: {
                type: Object
            }
        },
        mounted() {
            this.loadZones();
            Dispatcher.add('save-shipping-method-zones', this);

            this.selected = _.map(this.method.zones.data, item => {
                return item.id;
            });
        },
        methods: {
            save() {
                apiRequest.send('PUT', '/shipping/' + this.method.id + '/zones', {
                    'zones' : this.selected
                }).then(response => {
                    CandyEvent.$emit('notification', {
                        level: 'success'
                    });
                })
            },
            loadZones() {
                apiRequest.send('GET', '/shipping/zones').then(response => {
                    this.zones = response.data;
                })
            }
        }
    }
</script>

<style lang="scss">

</style>

<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <h4>Shipping Zones</h4>
                <p>Choose which shipping zones are eligible for "{{ method|attribute('name') }}" shipping</p>
                <hr>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="zone in zones">
                            <td>
                                <label><input type="checkbox" :value="zone.id" v-model="selected"> {{ zone.name }}</label>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
