<template>
  <div>
    {{ value }}
  </div>
</template>

<script>
export default {
  name: "component",
  data() {
    return {value: ''}
  },
  created() {
    this.init();
  },
  methods: {
    init() {
      this.value = Math.floor(Math.random() * 9999);
    },
  }
}
</script>

<style scoped>
</style>