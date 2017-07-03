<template>
  <div class="row">
    <div class="col-xs-12 col-md-11">
      <h4>Channels</h4>
      <hr>
      <table class="table">
      <thead>
        <tr>
          <th>Channel</th>
          <th>Visible</th>
          <th>Publish Date</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Storefront</td>
          <td>
            <div class="checkbox">
              <input id="storefrontVisible" type="checkbox" checked>
              <label for="storefrontVisible"><span class="check"></span></label>
            </div>
          </td>
          <td class="publish-date"><a href="#" class="btn-pop-over"><i class="fa fa-calendar-o" aria-hidden="true"></i></a></td>
        </tr>
        <tr>
          <td>Facebook</td>
          <td>
            <div class="checkbox">
              <input id="facebookVisible" type="checkbox">
              <label for="facebookVisible"><span class="check"></span></label>
            </div>
          </td>
          <td class="publish-date"><em>Will publish on 03/30/2017 - 9:00pm</em> <a href="#" class="btn-pop-over"><i class="fa fa-calendar-o" aria-hidden="true"></i></a></td>
        </tr>
        <tr>
          <td>eBay</td>
          <td>
            <div class="checkbox">
              <input id="eBayVisible" type="checkbox">
              <label for="eBayVisible"><span class="check"></span></label>
            </div>
          </td>
          <td class="publish-date">
            <a href="#" class="btn-pop-over"><i class="fa fa-calendar-o" aria-hidden="true"></i></a>
          </td>
        </tr>
        <tr>
          <td>Amazon</td>
          <td>
            <div class="checkbox">
              <input id="amazonVisible" type="checkbox">
              <label for="amazonVisible"><span class="check"></span></label>
            </div>
          </td>
          <td class="publish-date">
            <a href="#" class="btn-pop-over"><i class="fa fa-calendar-o" aria-hidden="true"></i></a>
            <div class="pop-over publish-date">
              <form>
                <label>Publish this product on:</label>
                <div class="form-group">
                  <div class="input-group input-group-full date" data-provide="datepicker">
                    <span class="input-group-addon">
                      <i class="fa fa-calendar" aria-hidden="true"></i>
                    </span>
                    <label class="sr-only" for="search">Date</label>
                    <input type="text" class="form-control">
                  </div>
                </div>
                <span class="form-link">
                  at
                </span>
                <div class="form-group">
                  <div class="input-group input-group-full">
                    <span class="input-group-addon">
                      <i class="fa fa-clock-o" aria-hidden="true"></i>
                    </span>
                    <label class="sr-only" for="search">Time</label>
                    <select class="form-control selectpicker">
                      <option selected>12:00 am</option>
                      <option>12:30 am</option>
                      <option>1:00 am</option>
                      <option>1:30 am</option>
                      <option>2:00 am</option>
                      <option>2:30 am</option>
                      <option>3:00 am</option>
                      <option>Etc.</option>
                    </select>
                  </div>
                </div>
                <button type="button" class="btn btn-default">Set publish date</button>
              </form>
            </div>
          </td>
        </tr>
      </tbody>
      </table>
    </div>
  </div>
</template>

<script>
    export default {
        data() {
            return {
            }
        }
    }
</script>