<template>
  <div>
    <div class="flex justify-center items-center h-screen">
      <div class="container shadow w-[500px] h-auto p-8">
        <router-view />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "App",
  methods: {},
};
</script>

<style lang="stylus" scoped></style>
