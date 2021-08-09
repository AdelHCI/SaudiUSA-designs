<template>
  <v-row v-if="src != null" align="center" justify="center">
    <v-col cols="12" align="center" justify="center">
      <v-card class="elevation-12" id="info">
        <v-img :lazy-src="loadingSrc" :src="src"></v-img>
      </v-card>
      <v-card v-show="warn" class="elevation-12">
        <p id="warn">
          في حال تم تنزيل الملف بإسم document قم بإعادة التسمية إلى document.png
        </p>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
export default {
  name: "Preview",
  props: {
    src: String,
  },
  data() {
    return {
      loadingSrc: require("../assets/loading.gif"),
      warn:
        (navigator.userAgent.includes("iPhone") ||
          navigator.userAgent.includes("Mac")) &&
        navigator.userAgent.includes("CriOS"),
    };
  },
};
</script>

<style scoped>
#warn {
  font-size: 50%;
  color: red;
  height: fit-content;
}
/* .form-group {
  width: 80%;
  margin: auto;
  padding: 15px;
  margin-top: 2rem;
  display: flex;
  flex-flow: column;
  align-items: center;
  padding-bottom: 2rem;
}

.form-control {
  width: 80%;
  margin: auto;
  text-align: center;
  margin: 2px;
} */

img {
  display: block;
  margin: auto;
}
.card {
  margin: auto;
}
h3 {
  margin: 40px 0 0;
}
/* ul {
  list-style-type: none;
  padding: 0;
}
li {
  display: inline-block;
  margin: 0 10px;
} */
a {
  color: #42b983;
}
</style>
