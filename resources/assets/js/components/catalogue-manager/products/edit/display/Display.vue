<template>
  <div class="sub-panel">
    <div class="sub-content section block">
      <h4>Choose this products display template</h4>
      <hr>
      <div class="radio-layout">
        <input type="radio" name="layouts" id="layoutOne" checked>
        <label for="layoutOne">
          <span class="title">Test <a href="#" class="btn btn-default btn-action" target="_blank"><i class="fa fa-eye" aria-hidden="true"></i></a></span>
          <span class="layout"><img src="img/placeholder/no-image.png" alt="Layout title"></span>
          <span class="layout-content">
            <span class="description">A brief description on the product layout, benefits etc.</span>
          </span>
        </label>
      </div>
      <div class="radio-layout">
        <input type="radio" name="layouts" id="layoutTwo">
        <label for="layoutTwo">
          <span class="title">Test Two <a href="#" class="btn btn-default btn-action" target="_blank"><i class="fa fa-eye" aria-hidden="true"></i></a></span>
          <span class="layout"><img src="img/placeholder/no-image.png" alt="Layout title"> </span>
          <span class="layout-content">
            <span class="description">A brief description on the product layout, benefits etc.</span>
          </span>
        </label>
      </div>
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