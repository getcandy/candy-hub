<template>
  <div class="sub-panel">
    <div class="sub-content section block">
      <h4>Choose this products display template</h4>
      <hr>
      <template v-if="loading">
          <i class="fa fa-refresh fa-spin"></i> Fetching Layouts
      </template>
      <template v-else>
            <div class="radio-layout" v-for="layout in layouts" :key="layout.id">
                <input type="radio" name="layouts" :id="layout.id" :value="layout.id" v-model="chosen" @change="update">
                <label :for="layout.id">
                <span class="title">{{ layout.name }} <a href="#" class="btn btn-default btn-action" target="_blank"><i class="fa fa-eye" aria-hidden="true"></i></a></span>
                <span class="layout"><img src="/candy-hub/images/placeholder/no-image.svg" alt="Layout title"></span>
                <span class="layout-content">
                    <span class="description">A brief description on the product layout, benefits etc.</span>
                </span>
                </label>
            </div>
      </template>
    </div>
  </div>
</template>

<script>
    export default {
        props: ['value'],
        data() {
            return {
                loading: true,
                chosen: null,
                layouts: []
            }
        },
        mounted() {
            this.chosen = this.value;
            apiRequest.send('get', 'layouts').then(response => {
                this.loading = false;
                this.layouts = response.data;
                // response.data.forEach(lang => {
                //     // this.languages.push({
                //     //     label: lang.name,
                //     //     value: lang.lang,
                //     //     content: '<span class=\'flag-icon flag-icon-' + lang.iso + '\'></span> ' + lang.name
                //     // });
                // });
            });
        },
        methods: {
            update() {
                this.$emit('input', this.chosen);
            }
        }
    }
</script>