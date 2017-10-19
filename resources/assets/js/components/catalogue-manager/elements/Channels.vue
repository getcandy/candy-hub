<script>

    import flatPickr from 'vue-flatpickr-component';
    import 'flatpickr/dist/flatpickr.css';

    export default {
        data() {
            return {
                current: null,
                flatPickrConfig: {
                    enableTime: true,
                    defaultHour: 12,
                    defaultMinute: 0
                }
            }
        },
        methods: {
            status(channel) {
                var status = {
                    class: 'status-disabled',
                    level: 0,
                    text: 'Unpublished'
                }
                if (channel.published_at) {
                    let now = moment();
                    let publish_date = moment(channel.published_at);

                    if (publish_date.isAfter(now)) {
                        status.text = 'Publishes ' + publish_date.fromNow();
                        status.level = 2;
                        status.class = 'status-pending';
                    } else {
                        status.text = 'Published ' + publish_date.fromNow();
                        status.level = 1;
                        status.class = 'status-live';
                    }
                }

                return status;
            },
            togglePublish(channel) {
                if (channel.published_at) {
                    channel.published_at = null;
                } else {
                    channel.published_at = moment().toISOString();
                }
            }
        },
        props: {
            channels: {
                type: Array
            }
        },
        components: {
            flatPickr
        }
    }
</script>

<template>
    <div>
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <h4>Channels</h4>
                <hr>
                <table class="table">
                    <thead>
                    <tr>
                        <th>Channel</th>
                        <th>Visible</th>
                        <th>Published</th>
                        <th>Publish date</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="channel in channels" :key="channel.id">
                        <td>
                            <figure class="status-icon">
                                <span class="status" :class="status(channel).class" v-if="status(channel).level != 2"></span>
                                <i class="fa fa-clock-o" aria-hidden="true" v-else></i>
                            </figure>
                            {{ channel.name }}
                        </td>
                        <td>
                            <div class="checkbox">
                                <input :id="'CH' + channel.id" type="checkbox" v-model="channel.visible">
                                <label :for="'CH' + channel.id"><span class="check"></span></label>
                            </div>
                        </td>
                        <td width="5%">
                            <div class="checkbox">
                                <input :id="'CHP' + channel.id" type="checkbox" @change="togglePublish(channel)" :checked="channel.published_at">
                                <label :for="'CHP' + channel.id"><span class="check"></span></label>
                            </div>
                        </td>
                        <td>
                            <template v-if="channel.published_at">
                                <flat-pickr class="datetimepicker" v-model="channel.published_at" :config="flatPickrConfig"></flat-pickr>
                            </template>
                            <span class="input-alt text-muted" v-else>Never</span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
