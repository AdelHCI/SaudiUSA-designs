<template>
  <div id="app">
    <v-app>
      <v-row align="center" justify="center">
        <v-col cols="6" sm="6" md="6" lg="2" xl="2">
          <v-img alt="SaudiUSA logo" :src="require('./assets/logo.png')" />
        </v-col>
      </v-row>
      <router-view />
    </v-app>
    <br><br><br>
  </div>
</template>

<script>
// import Home from "./views/Home.vue";
// import router from "@/router.js";
export default {
  name: "App",
  // components: {
  //   Home
  // }
  mounted(){
    if (window.location.protocol !== 'https:' && process.env.NODE_ENV === 'production') window.location.protocol = 'https:'
  }
};
</script>

<style>
/* #logo{
  width: 30%;
  height: 30%;
} */
#app {
  /* font-family: Avenir, Helvetica, Arial, sans-serif; */
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
@font-face {
  font-family: "DriodKufi";
  src: url("assets/Kufi.ttf");
}
@font-face {
  font-family: "DriodKufiBold";
  src: url("assets/Kufi_Bold.ttf");
}
</style>
