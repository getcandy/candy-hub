<template>
  <div class="row">
    <div class="col-md-10">
      <input :value="value" ref="input" class="form-control" placeholder="YYYY-MM-DD">
    </div>
    <div class="col-md-2" v-if="clearable">
      <a href="#" @click.prevent="clear">Clear</a>
    </div>


  </div>
</template>

<script>
import Pikaday from "pikaday"
import "pikaday/css/pikaday.css"

export default {
  data() {
    return {
      picker: null
    };
  },
  props: {
    value: { required: true },
    clearable: { default: true },
    format: { default: "YYYY-MM-DD" },
    options: { default: () => {} }
  },
  mounted() {
    this.picker = new Pikaday({
      field: this.$refs.input,
      format: this.format,
      keyboardInput: false,
      onSelect: () => {
        this.$emit("input", this.picker.toString())
      },
      ...this.options
    })
  },
  methods: {
    clear() {
      this.picker.setDate(null);
      this.$emit("input", null)
    }
  }
}
</script>
