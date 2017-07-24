<template>
    <div>
        <div class="row">
          <div class="col-xs-12 col-md-11">
            <div class="row">
              <div class="col-xs-12 col-sm-6">
                <h4>Locale URLs</h4>
              </div>
              <div class="col-xs-12 col-sm-6 text-right">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addLocaleURL">Add Locale URL</button>
              </div>
            </div>
            <hr>
            <table class="table">
              <thead>
                <tr>
                  <td>URL</td>
                  <td colspan="3">Locale</td>
                </tr>
              </thead>
              <tbody>
                <tr v-for="route in routes">
                  <td><input type="text" class="form-control" :value="route.slug"></td>
                  <td><span class="flag-icon" :class="getFlag(route.locale)"></span></td>
                  <td><em v-if="route.default">Default</em></td>
                  <td align="right"><button class="btn btn-sm btn-default btn-action" data-toggle="modal" data-target="#deleteURL"><i class="fa fa-trash-o" aria-hidden="true"></i></button></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
            }
        },
        props: {
          routes: {
            type: Array
          }
        },
        methods: {
          getFlag: function(locale) {
            return 'flag-icon-' + locale;
          }
        }
    }
</script>