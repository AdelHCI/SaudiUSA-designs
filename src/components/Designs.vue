<template>
  <v-row align="center" justify="center" fluid>
    <v-col cols="12">
      <v-card color="primary" dark>
        <v-card-title class="justify-center">
          <h1>
            اختر تصميم
            <template v-if="mode != ''"> {{ mode }}</template>
          </h1>
        </v-card-title>
      </v-card>

      <v-tabs show-arrows centered center-active>
        <v-tab @click="chooseType('الكل')">الكل</v-tab>
        <template v-for="t in types">
          <v-tab :key="t" @click="chooseType(t)">{{ t }}</v-tab>
        </template>
      </v-tabs>
    </v-col>
    <template
      v-for="img in Object.values(imgs).filter(
        (k) => k.type == currType || currType == 'الكل'
      )"
    >
      <v-col :key="img.name" cols="12" xs="12" md="6" lg="6" xl="4">
        <v-card class="elevation-12">
          <v-img
            :lazy-src="loadingSrc"
            :id="img.name"
            :src="img.src.replace('uploads', 'thumbs')"
          ></v-img>
          <v-card-actions>
            <v-btn v-if="remove" color="red" text @click="removeImg(img.name)"
              >احذف</v-btn
            ><v-btn v-else color="green" text @click="chooseImg(img.name)"
              >اختر</v-btn
            >
          </v-card-actions>
        </v-card>
      </v-col>
    </template>
  </v-row>
</template>

<script>
export default {
  name: "Designs",
  props: {
    remove: Boolean,
    edit: Boolean,
    types: Object,
    imgs: Object,
    currType: String,
  },
  data() {
    return {
      remove: false,
      edit: false,
      mode: this.remove ? "للحذف" : this.edit ? "للتعديل" : "",
      loadingSrc: require("../assets/loading.gif"),
      currImg: null,
      src: null,
      warn:
        navigator.userAgent.includes("iPhone") &&
        navigator.userAgent.includes("CriOS"),
    };
  },
  methods: {
    chooseType(type) {
      this.currType = type;
    },
    chooseImg(name) {
      // document.getElementById("info").scrollIntoView();
      this.$emit("selected", { name: name, op: "edit" });
    },
    removeImg(name) {
      this.$emit("selected", { name: name, op: "remove" });
    },
  },
};
</script>

<style scoped>
#warn {
  font-size: 50%;
  color: red;
  height: fit-content;
}
.form-group {
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
}

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
ul {
  list-style-type: none;
  padding: 0;
}
li {
  display: inline-block;
  margin: 0 10px;
}
a {
  color: #42b983;
}
</style>
