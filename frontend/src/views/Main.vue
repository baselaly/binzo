<template>
  <div>
    <navbar></navbar>
    <v-content>
      <v-container fluid fill-height>
        <v-layout justify-center align-center>
          <v-flex>
            <router-view :key="$route.fullPath"/>
          </v-flex>
        </v-layout>
      </v-container>
    </v-content>
  </div>
</template>

<script>
export default {
  components: {
    navbar: () => import("@/components/core/navbar")
  }
};
</script>
<style>
</style>