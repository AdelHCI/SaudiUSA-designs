<template>
  <div id="app">
    <v-app>
      <v-row align="center" justify="center">
        <v-col cols="12" xs="6" sm="8" md="10">
          <v-img alt="SaudiUSA logo" :src="require('./assets/logo.png')" />
        </v-col>
      </v-row>
      <router-view />
    </v-app>
    <br /><br /><br />
  </div>
</template>

<script>
export default {
  name: "App",
  mounted() {
    if (
      window.location.protocol !== "https:" &&
      process.env.NODE_ENV === "production"
    )
      window.location.protocol = "https:";
  },
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
}
.v-tab {
  padding: 0;
}
.v-btn {
  padding: 2%;
  margin: 2%;
  max-height: 100%;
}
.row {
  max-width: 95%;
  margin: auto;
}
/* .v-toolbar__content {
  max-height: 100%;
} */
/* @font-face {
  font-family: "DriodKufi";
  src: url("assets/Kufi.ttf");
}
@font-face {
  font-family: "DriodKufiBold";
  src: url("assets/Kufi_Bold.ttf");
} */
</style>
