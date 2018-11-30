<template>
  <div>
    <input :value="value" ref="input" class="form-control" placeholder="YYYY-MM-DD">
  </div>
</template>

<script>
import Pikaday from "pikaday"
import "pikaday/css/pikaday.css"

export default {
  props: {
    value: { required: true },
    format: { default: "YYYY-MM-DD" },
    options: { default: () => {} }
  },
  mounted() {
    const picker = new Pikaday({
      field: this.$refs.input,
      format: this.format,
      onSelect: () => {
        this.$emit("input", picker.toString())
      },
      ...this.options
    })
  }
}
</script>
