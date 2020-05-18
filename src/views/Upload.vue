<template>
  <div class="upload">
    <LogIn v-on:loggedIn="loggedIn=true" v-if="!loggedIn" />
    <v-row v-else align="center" justify="center" fluid>
      <v-col cols="10" sm="10" md="8 " lg="6" xl="4">
        <v-card class="elevation-12">
          <v-toolbar color="primary" dark flat>
            <v-toolbar-title>بيانات الملف</v-toolbar-title>
            <v-spacer></v-spacer>
          </v-toolbar>
          <v-card-text>
            <v-text-field label="طول الصورة" type="text" v-model="height"></v-text-field>
            <v-text-field label="عرض الصورة" type="text" v-model="width"></v-text-field>
            <v-text-field label="موقع النص بالعرض" type="text" v-model="x"></v-text-field>
            <v-text-field label="موقع النص بالطول" type="text" v-model="y"></v-text-field>
            <v-text-field label="قيمة اللون الأحمر" type="text" v-model="r"></v-text-field>
            <v-text-field label="قيمة اللون الأخضر" type="text" v-model="g"></v-text-field>
            <v-text-field label="قيمة اللون الأزرق" type="text" v-model="b"></v-text-field>
            <v-file-input accept="image/jpeg, image/png" chips :label="fileLabel" v-model="file"></v-file-input>
            <!-- <input
            type="file"
            ref="file"
            id="image-file"
            accept="image/jpeg, image/png"
            class="custom-file-input"
            @change="saveImg()"
            />-->
            <v-btn color="success" class="mr-4" @click="uploadImg">Upload</v-btn>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
    <br />
    <img id="display" />
  </div>
</template>

<script>
// import $ from "jquery";
import axios from "axios";
// const json =require("../files.json")
// console.log(require("../files.json"))
import LogIn from "../components/LogIn.vue";
export default {
  name: "Upload",
  components: {
    LogIn
  },
  data() {
    return {
      height: null,
      width: null,
      y: null,
      x: null,
      r: null,
      g: null,
      b: null,
      fileLabel: "اختر ملف",
      file: null,
      formData: new FormData(),
      loggedIn: false,
      jsonfiles: null
    };
  },
  mounted() {
    this.check();
  },
  methods: {
    check() {
      axios
        .post("check.php", this.formData, {
          headers: {
            "Content-Type": "multipart/form-data"
          }
        })
        .then(res => {
          console.log(res.data);
          this.loggedIn = parseInt(res.data) == 1;
        })
        .catch(res => {
          console.log(res);
        });
    },
    uploadImg() {
      axios.get("files.json").then(response => {
        this.jsonfiles = response.data;
      });
      this.formData.append("file", this.file);
      this.jsonfiles.push({
        name: this.file.name,
        src: "./uploads/" + this.file.name,
        width: this.width,
        height: this.height,
        textX: this.x,
        textY: this.y,
        r: this.r,
        g: this.g,
        b: this.b
      });
      this.formData.append("json", JSON.stringify(this.jsonfiles));
      console.log(this.jsonfiles);
      axios
        .post("upload.php", this.formData, {
          headers: {
            "Content-Type": "multipart/form-data"
          }
        })
        .then(function(res) {
          console.log("SUCCESS!!");
          console.log(res);
        })
        .catch(function(res) {
          console.log("FAILURE!! " + res);
          console.log(res);
        });
    }
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
@font-face {
  font-family: "DriodKufi";
  src: url("../assets/Kufi.ttf");
}
@font-face {
  font-family: "DriodKufiBold";
  src: url("../assets/Kufi_Bold.ttf");
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
.row {
  max-width: 80%;
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
